<?php

namespace Mic2100\Events;

/**
 * Class Configuration
 *
 * @category Events
 * @package  Mic2100\Events
 * @author   Michael Bardsley @mic_bardsley
 * @link     http://github.com/mic2100/events
 * @licence  MIT
 */
class Configuration
{
    /**
     * Any string with length of 1 you want to use as the wildcard defaults to *
     *
     * @var string
     */
    private $wildcard = '*';

    /**
     * This is set automatically when setting the wildcard
     *
     * @var int
     */
    private $wildcardLength = 1;

    /**
     * Gets the wildcard var
     *
     * @return string
     */
    public function getWildcard(): string
    {
        return $this->wildcard;
    }

    /**
     * Sets the wildcard var
     *
     * @param string $wildcard
     */
    public function setWildcard(string $wildcard)
    {
        $this->wildcard = $wildcard;

        $this->wildcardLength = strlen($wildcard);
    }

    /**
     * @return int
     */
    public function getWildcardLength(): int
    {
        return $this->wildcardLength;
    }
}
