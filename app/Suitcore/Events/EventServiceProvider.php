<?php

namespace Suitcore\Events;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        // code in your model must be
        // $this->event()->fire('someevent', [$this->status, $this->location_id, $this->id]);
        $events->listen('someevent', function ($arg1 = null, $arg2 = null, $arg3 = null) {
            // do something
            // info('event fire');

        });
    }
}
