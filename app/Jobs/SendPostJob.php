<?php

namespace App\Jobs;

use App\Services\Wp_service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $post;
    protected $post_service;
    public function __construct($post,Wp_service $post_service)
    {
        //
        $this->post=$post;
        $this->post_service=$post_service;
    }

    /**
     * Execute the job. 
     */
    public function handle(): void
    {
        //
    }
}
