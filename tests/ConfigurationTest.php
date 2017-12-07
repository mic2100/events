<?php

namespace Mic2100EventsTests;

use Mic2100\Events\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigurationTest
 *
 * @category Events Testing
 * @package  Mic2100EventsTests
 * @author   Michael Bardsley @mic_bardsley
 * @link     http://github.com/mic2100/events
 * @licence  MIT
 */
class ConfigurationTest extends TestCase
{
    /**
     * @var Configuration
     */
    private $config;

    public function setUp()
    {
        parent::setUp();

        $this->config = new Configuration();
    }

    /**
     * @dataProvider dataWildcardsAndLengths
     *
     * @param string $wildcard
     * @param int $expectedLength
     */
    public function testGettersAndSetters(string $wildcard, int $expectedLength)
    {
        $this->config->setWildcard($wildcard);
        $this->assertSame(
            $wildcard,
            $this->config->getWildcard(),
            'Wildcards do not match'
        );
        $this->assertSame(
            $expectedLength,
            $this->config->getWildcardLength(),
            'Wildcard length is incorrect'
        );
    }

    /**
     * @return array
     */
    public function dataWildcardsAndLengths() : array
    {
        return [
            ['%', 1],
            ['%*', 2],
            ['*%*', 3],
            ['*%%*', 4],
        ];
    }
}
