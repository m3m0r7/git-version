<?php

declare(strict_types=1);

namespace GitVersion;

class Version
{
    public const SHORT_HASH_SIZE = 8;

    protected $directory;
    protected $branchName;
    protected $gitDirectory = '.git';
    protected $headFilePath = 'HEAD';
    protected $logDirectory = 'logs';

    protected function __construct(string $directory, ?string $branchName = null)
    {
        $this->directory = $directory;
        $this->branchName = $branchName ?? $this->getBranchName();
    }

    public static function make(string $directory, ?string $branchName = null)
    {
        return new static($directory, $branchName);
    }

    public function getLastHash(bool $short = false): string
    {
        $branchName = $this->branchName;

        $handle = fopen(
            static::getFile(static::getLogDirectory() . '/' . $branchName),
            'r'
        );

        if (!flock($handle, LOCK_EX)) {
            throw new VersionException('The git log is cannot lock.');
        }

        try {
            $line = null;
            while (true) {
                $tmpLine = fgets($handle);
                if (!$tmpLine || feof($handle)) {
                    break;
                }
                $line = $tmpLine;
            }

            [, $currentHash] = explode(
                ' ',
                preg_replace(
                    '/\s+/',
                    ' ',
                    $line
                )
            );

            if ($short) {
                return substr($currentHash, 0, static::SHORT_HASH_SIZE);
            }

            return $currentHash;
        } finally {
            flock($handle, LOCK_UN);
        }
    }

    public function getHEADFilePath()
    {
        return $this->headFilePath;
    }

    public function getLogDirectory()
    {
        return $this->logDirectory;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function getGitDirectory()
    {
        return $this->gitDirectory;
    }

    public function setHEADFilePath(string $filePath): self
    {
        $this->headFilePath = $filePath;

        return $this;
    }

    public function setLogDirectory(string $directory): self
    {
        $this->logDirectory = $directory;

        return $this;
    }

    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    public function setGitDirectory(string $directory): self
    {
        $this->gitDirectory = $directory;

        return $this;
    }

    public function getBranchName(): string
    {
        $ref = file_get_contents(
            $this->getFile(static::getHEADFilePath())
        );
        [, $branchName] = explode(':', $ref, 2);

        return trim($branchName);
    }

    protected function getFile(string $path): string
    {
        $fileName = rtrim(static::getDirectory(), '/') . '/' . static::getGitDirectory() . '/' . $path;

        if (!is_file($fileName)) {
            throw new VersionException("{$fileName} file not found");
        }

        return $fileName;
    }
}
