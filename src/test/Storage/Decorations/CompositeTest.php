<?php

namespace Chemisus\Storage\Decorations;

use Chemisus\Serialization\JsonSerializer;
use Chemisus\Storage\StorageDecorationTest;

class CompositeTest extends StorageDecorationTest
{
    public function factory()
    {
        return new Composite(array(
            new TTL(),
            new Serialize(new JsonSerializer())
        ));
    }

    public function testConstruct()
    {
        $decorations = array(
            new TTL(),
            new Serialize(new JsonSerializer())
        );

        $decoration = new Composite($decorations);

        self::assertEquals($decorations, $decoration->decorations());
    }
}