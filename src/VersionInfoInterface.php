<?php

declare(strict_types=1);

namespace GitVersion;

interface VersionInfoInterface
{
    public function getHash(bool $short = false): string;

    public function getVersion(): string;
}
