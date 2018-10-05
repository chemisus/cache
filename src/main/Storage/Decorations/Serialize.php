<?php

namespace Chemisus\Storage\Decorations;

use Chemisus\Serialization\Serializer;

class Serialize extends AbstractStorageDecoration
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serializer()
    {
        return $this->serializer;
    }

    public function afterGet(array &$entries)
    {
        $entries = array_map(array($this->serializer, 'deserialize'), $entries);
    }

    public function beforePut(array &$entries)
    {
        $entries = array_map(array($this->serializer, 'serialize'), $entries);
    }
}
