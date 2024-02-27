<?php
namespace App\Jobs;

use App\Services\ProcessamentoPostagemService;
use App\Services\ScheduleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $postagemService;

    public function __construct(ScheduleService $postagemService)
    {
        $this->postagemService = $postagemService;
    }

    public function handle()
    {
        $this->postagemService->processScheduledPosts();
    }
}
