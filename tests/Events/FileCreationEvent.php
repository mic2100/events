<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class FileCreationEvent extends AbstractEvent
{
    const HANDLE = 'file-creation-event';

    public function handle(): bool
    {
        file_put_contents($this->params['destination'], $this->params['contents']);

        return true;
    }
}
