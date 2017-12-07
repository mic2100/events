<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class MissingHandleEvent extends AbstractEvent
{
    const HANDLE = 'missing-handle-event';

    //Missing handle
}
