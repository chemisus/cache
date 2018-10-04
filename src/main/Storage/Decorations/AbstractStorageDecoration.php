<?php

namespace Chemisus\Storage\Decorations;

use Chemisus\Storage\StorageDecoration;

class AbstractStorageDecoration implements StorageDecoration
{
    public function beforeGet(array &$keys)
    {
    }

    public function afterGet(array &$entries)
    {
    }

    public function beforePut(array &$entries)
    {
    }

    public function afterPut(array &$entries)
    {
    }

    public function beforeDelete(array &$keys)
    {
    }

    public function afterDelete(array &$keys)
    {
    }
}