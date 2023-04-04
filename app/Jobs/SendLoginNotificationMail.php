<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\LoginNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendLoginNotificationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $incoming;
    /**
     * Create a new job instance.
     */
    public function __construct($incoming)
    {
        $this->incoming = $incoming;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->incoming['sendto'])->send(new LoginNotification( $this->incoming['name'], $this->incoming['ip']));
    }
}
