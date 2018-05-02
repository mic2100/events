<?php

namespace Mic2100\Events;

trait DispatcherAwareTrait
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @return Dispatcher
     */
    public function getDispatcher() : Dispatcher
    {
        return $this->dispatcher;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
