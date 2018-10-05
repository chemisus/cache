<?php

namespace Chemisus\Storage\Decorations;

class TTL extends AbstractStorageDecoration
{
    const SECOND = 1;
    const MINUTE = 60;
    const HOUR = 3600;
    const DAY = 86400;
    const WEEK = 604800;
    const DAY30 = 2592000;

    const EXPIRATION_KEY = 'expiration';
    const VALUE_KEY = 'data';

    /**
     * @var
     */
    private $ttl;

    /**
     * @var string
     */
    private $expirationKey;

    /**
     * @var string
     */
    private $valueKey;

    /**
     * @var null
     */
    private $now;

    public function __construct($ttl = self::HOUR, $now = null, $expirationKey = self::EXPIRATION_KEY, $valueKey = self::VALUE_KEY)
    {
        $this->ttl = $ttl;
        $this->expirationKey = $expirationKey;
        $this->valueKey = $valueKey;
        $this->now = $now;
    }

    public function ttl()
    {
        return $this->ttl;
    }

    public function expirationKey()
    {
        return $this->expirationKey;
    }

    public function valueKey()
    {
        return $this->valueKey;
    }

    public function now()
    {
        return $this->now !== null ? $this->now : time();
    }

    public function valid($data)
    {
        $data = ((array)$data);
        return $this->now() < $data[$this->expirationKey];
    }

    public function value($data)
    {
        $data = ((array)$data);
        return $data[$this->valueKey];
    }

    public function data($value)
    {
        $expiration = $this->now() + $this->ttl;

        return array(
            $this->expirationKey => $expiration,
            $this->valueKey => $value,
        );
    }

    public function afterGet(array &$entries)
    {
        $entries = array_map(
            array($this, 'value'),
            array_filter($entries, array($this, 'valid'))
        );
    }

    public function beforePut(array &$entries)
    {
        $entries = array_map(array($this, 'data'), $entries);
    }
}
