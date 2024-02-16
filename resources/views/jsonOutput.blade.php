<!-- resources/views/json_output.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>JSON Output</title>
</head>
<body>
    @csrf <!-- {{ csrf_field() }} -->
    @foreach (json_decode($jsonData, true) as $item)
        <div>
            <strong>{{ $item['FATURAS'] }}</strong>
            <ul>
                @foreach ($item as $key => $value)
                    <li>{{ $key }}: {{ $value }}</li>
                @endforeach
            </ul>
        </div>
    @endforeach
    <div>heyyy</div>
</body>
</html>
