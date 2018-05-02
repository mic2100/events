<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class HandleMethodReturnsTrueEvent extends AbstractEvent
{
    protected $handle = 'handle-method-returns-true-event';

    public function handle(array $params = null): bool
    {
        return true;
    }
}
