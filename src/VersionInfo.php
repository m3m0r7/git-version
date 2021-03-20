<?php

declare(strict_types=1);

namespace GitVersion;

class VersionInfo implements VersionInfoInterface
{
    public const SHORT_HASH_SIZE = 8;

    protected $version;
    protected $hash;

    public function __construct(string $version, string $hash)
    {
        $this->version = $version;
        $this->hash = $hash;
    }

    public function getHash(bool $short = false): string
    {
        if ($short) {
            return substr(
                $this->hash,
                0,
                static::SHORT_HASH_SIZE
            );
        }

        return $this->hash;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
