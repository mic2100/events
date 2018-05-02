<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class FileCreationEvent extends AbstractEvent
{
    protected $handle = 'file-creation-event';

    public function handle($params = null): bool
    {
        file_put_contents($this->params['destination'], $this->params['contents']);

        return true;
    }
}
