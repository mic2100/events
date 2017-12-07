<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class EmptyStringHandleConstantEvent extends AbstractEvent
{
    const HANDLE = '';
}
