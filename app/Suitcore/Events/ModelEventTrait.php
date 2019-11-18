<?php

namespace Suitcore\Events;

use Illuminate\Events\Dispatcher;

trait ModelEventTrait
{
    protected function event()
    {
        return app(Dispatcher::class);
    }
}
