<?php

namespace Mic2100\Events;

/**
 * Interface EventInterface
 *
 * @package Mic2100\Events
 */
interface EventInterface
{
    /**
     * The handle that will be used when calling the event
     *
     * @var string
     */
    const HANDLE = '';

    /**
     * @param array $params
     *
     * $params will consist of what ever variables are needed throughout the event
     */
    public function __construct(array $params = []);

    /**
     * Handle the event
     *
     * @return bool
     */
    public function handle() : bool;
}
