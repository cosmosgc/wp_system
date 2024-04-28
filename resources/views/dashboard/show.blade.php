<!-- resources/views/dashboard/show.blade.php -->
@extends('layouts.app')
@php
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use App\Models\Editor;
    use App\Models\Wp_credential;
    use App\Models\Wp_post_content;

    $valorCodificado = request()->cookie('editor');
    $user=explode('+',base64_decode($valorCodificado));

    $editors = Editor::all();
    $credentialsCount = Wp_credential::count();
    $wpPostsCount = Wp_post_content::count();
    // Fetch data for the last 30 days
$data = Wp_post_content::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
    ->where('created_at', '>=', now()->subDays(30)) // Filter for the last 30 days
    ->groupBy('date')
    ->orderBy('date')
    ->get();

// Prepare data for chart
$dates = $data->pluck('date')->toArray();
$counts = $data->pluck('count')->toArray();

    //dd($counts);
@endphp

@section('content')
<div class="container">
    <div class="dashboard-content">
        <h1 class="mt-5">Bem-vindo {{ $user[0] }}</h1>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Editores</h5>
                        <p class="card-text">{{ $editors->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sites</h5>
                        <p class="card-text">{{ $credentialsCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Artigos</h5>
                        <p class="card-text">{{ $wpPostsCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Example of using Chart.js -->
        <div class="mt-5">
            <canvas id="lineChart" width="800" height="400"></canvas>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('lineChart').getContext('2d');
        var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [{
                    label: 'Artigos por dia',
                    data: {!! json_encode($counts) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },

        });
    </script>

@endsection
