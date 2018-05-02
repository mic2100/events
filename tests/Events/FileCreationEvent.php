<?php

namespace Mic2100EventsTests\Events;

use Mic2100\Events\AbstractEvent;

class FileCreationEvent extends AbstractEvent
{
    protected $handle = 'file-creation-event';

    public function handle(array $params = null): bool
    {
        if ($params) {
            $this->params = !empty($this->params) ? array_merge($this->params, $params) : $params;

        }
        file_put_contents($this->params['destination'], $this->params['contents']);

        return true;
    }
}
