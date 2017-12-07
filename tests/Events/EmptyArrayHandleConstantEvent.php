<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class EmptyArrayHandleConstantEvent extends AbstractEvent
{
    const HANDLE = [];
}
