<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class FalseBooleanHandleConstantEvent extends AbstractEvent
{
    protected $handle = false;
}
