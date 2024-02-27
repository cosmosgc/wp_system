<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessScheduleJob;

class ProcessarPostagensAgendadas extends Command
{
    protected $signature = 'postagens:processar';
    protected $description = 'Processa postagens agendadas';

    public function handle()
    {
        ProcessScheduleJob::dispatch()->onQueue('post_schedule');
        $this->info('Job despachado com sucesso para processar postagens agendadas.');
    }
}   