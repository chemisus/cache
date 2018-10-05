<?php

namespace Chemisus\Storage\Decorations;

use PHPUnit_Framework_TestCase;

class TTLTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $ttl = 100;
        $expirationKey = 'a';
        $valueKey = 'b';
        $now = 10000;

        $decoration = new TTL($ttl, $now, $expirationKey, $valueKey);

        self::assertEquals($ttl, $decoration->ttl());
        self::assertEquals($now, $decoration->now());
        self::assertEquals($valueKey, $decoration->valueKey());
        self::assertEquals($expirationKey, $decoration->expirationKey());
    }

    public function testConstructWithoutNow()
    {
        $now = time();
        $decoration = new TTL();
        self::assertGreaterThanOrEqual($now, $decoration->now());
    }

    public function testAfterGet()
    {
        $decoration = new TTL();
        $entries = array('a' => 'A');
        $key = 'a';
        $data = $entries;
        $decoration->beforePut($entries);

        self::assertArrayHasKey($decoration->expirationKey(), $entries[$key]);
        self::assertArrayHasKey($decoration->valueKey(), $entries[$key]);
        self::assertEquals($data[$key], $decoration->value($entries[$key]));

        $decoration->afterGet($entries);
        self::assertEquals($data, $entries);
    }
}