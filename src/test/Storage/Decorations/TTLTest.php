<?php

namespace Chemisus\Storage\Decorations;

use Chemisus\Storage\ArrayStorage;
use Chemisus\Storage\StorageDecorationTest;
use Chemisus\Storage\StorageDecorator;

class TTLTest extends StorageDecorationTest
{
    public function factory()
    {
        return new TTL();
    }

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

    public function testExpire()
    {
        $array = new ArrayStorage();
        $ttl1 = new StorageDecorator($array, new TTL(150, 0));
        $ttl2 = new StorageDecorator($array, new TTL(150, 100));
        $ttl3 = new StorageDecorator($array, new TTL(150, 200));

        $ttl1->put(array('a' => 'A'));
        $ttl2->put(array('b' => 'B'));
        $ttl3->put(array('c' => 'C'));

        $expect = array('b' => 'B', 'c' => 'C');
        $actual = $ttl3->get(array('a', 'b', 'c'));
        self::assertEquals($expect, $actual);
    }
}