<?php

namespace Suitcore\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Suitcore\Notification\Contracts\NotifierAdapterInterface;

class SendingNotification implements SelfHandling, ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $adapter;
    protected $recipients;
    protected $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(NotifierAdapterInterface $adapter, $recipients, $message)
    {
        $this->adapter = $adapter;
        $this->recipients = $recipients;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->adapter->sendToQueue($this->recipients, $this->message);
    }
}
