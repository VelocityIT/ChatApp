<?php

namespace App\Jobs;

use App\Mail\ForgetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendResetPasswordMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $incoming;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($incoming)
    {
        $this->incoming = $incoming;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->incoming['sendto'])->send(new ForgetPassword( $this->incoming['token'], $this->incoming['username']));
    }
}
