<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class HandleMethodReturnsFalseEvent extends AbstractEvent
{
    protected $handle = 'handle-method-returns-false-event';

    public function handle(): bool
    {
        return false;
    }
}
