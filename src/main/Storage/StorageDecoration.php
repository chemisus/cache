<?php

namespace Chemisus\Storage;

interface StorageDecoration
{
    public function beforeGet(array &$keys);

    public function afterGet(array &$entries);

    public function beforePut(array &$entries);

    public function afterPut(array &$entries);

    public function beforeDelete(array &$keys);

    public function afterDelete(array &$keys);
}
