<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class MissingHandleEvent extends AbstractEvent
{
    protected $handle = 'missing-handle-event';

    //Missing handle
}
