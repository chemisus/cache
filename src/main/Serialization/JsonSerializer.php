<?php

namespace Chemisus\Serialization;

class JsonSerializer implements Serializer
{
    public function serialize($value)
    {
        return json_encode($value);
    }

    public function deserialize($string)
    {
        return json_decode($string);
    }
}
