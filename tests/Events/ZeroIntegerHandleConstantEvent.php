<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class ZeroIntegerHandleConstantEvent extends AbstractEvent
{
    protected $handle = 0;
}
