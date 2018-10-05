<?php

namespace Chemisus\Storage\Decorations;

use Chemisus\Storage\StorageDecoration;

class Composite extends AbstractStorageDecoration
{
    /**
     * @var StorageDecoration[]
     */
    private $decorations;

    /**
     * @param StorageDecoration[] $decorations
     */
    public function __construct($decorations)
    {
        $this->decorations = $decorations;
    }

    public function beforeGet(array &$keys)
    {
        /**
         * @var StorageDecoration $decoration
         */
        foreach (array_reverse($this->decorations) as $decoration) {
            $decoration->beforeGet($keys);
        }
    }

    public function afterGet(array &$entries)
    {
        /**
         * @var StorageDecoration $decoration
         */
        foreach (array_reverse($this->decorations) as $decoration) {
            $decoration->afterGet($entries);
        }
    }

    public function beforePut(array &$entries)
    {
        foreach ($this->decorations as $decoration) {
            $decoration->beforePut($entries);
        }
    }

    public function afterPut(array &$entries)
    {
        foreach ($this->decorations as $decoration) {
            $decoration->afterPut($entries);
        }
    }

    public function beforeDelete(array &$keys)
    {
        foreach ($this->decorations as $decoration) {
            $decoration->beforeDelete($keys);
        }
    }

    public function afterDelete(array &$keys)
    {
        foreach ($this->decorations as $decoration) {
            $decoration->afterDelete($keys);
        }
    }

    public function decorations()
    {
        return $this->decorations;
    }
}
