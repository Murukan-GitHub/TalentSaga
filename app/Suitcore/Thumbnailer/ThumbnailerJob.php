<?php

namespace Suitcore\Thumbnailer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Thumb;

class ThumbnailerJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $properties = [];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(...$args)
    {
        $this->properties = $args;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $args = $this->properties;
        Thumb::fromQueue(...$args);
    }
}
