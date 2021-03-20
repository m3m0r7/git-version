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
    protected $tagsDirectory = 'refs/tags';

    protected function __construct(string $directory, ?string $branchName = null)
    {
        $this->directory = $directory;
        $this->branchName = $branchName ?? $this->getBranchName();
    }

    public static function make(string $directory, ?string $branchName = null)
    {
        return new static($directory, $branchName);
    }

    protected function getVersionInfoObjectClass(): string
    {
        return VersionInfo::class;
    }

    public function getVersionedTag(string $specifiedVersion = null): VersionInfoInterface
    {
        $versions = $this->getVersions();
        if ($specifiedVersion === null) {
            $specifiedVersion = array_pop($versions);
        } else {
            if (!in_array($specifiedVersion, $versions, true)) {
                throw new VersionException("The specified version `{$specifiedVersion}` is not found");
            }
        }

        $versionInfoObject = $this->getVersionInfoObjectClass();
        return new $versionInfoObject(
            $specifiedVersion,
            trim(
                file_get_contents(
                    static::getBaseDirectory() . '/' . $this->tagsDirectory . '/' . $specifiedVersion
                )
            )
        );
    }

    public function getVersions(): array
    {
        $tags = array_map(
            function ($fileName) {
                return basename($fileName);
            },
            glob(static::getBaseDirectory() . '/' . $this->tagsDirectory . '/*')
        );

        sort($tags);

        return $tags;
    }

    public function getHash(bool $short = false): string
    {
        $hash = trim(
            file_get_contents(
                static::getFile($this->branchName)
            )
        );

        if ($short) {
            return substr($hash, 0, static::SHORT_HASH_SIZE);
        }

        return $hash;
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

    public function getTagsDirectory()
    {
        return $this->tagsDirectory;
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

    public function setTagsDirectory(string $directory): self
    {
        $this->tagsDirectory = $directory;

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
        $fileName = static::getBaseDirectory() . '/' . $path;

        if (!is_file($fileName)) {
            throw new VersionException("{$fileName} file is not found");
        }

        return $fileName;
    }

    protected function getBaseDirectory(): string
    {
        return rtrim(static::getDirectory(), '/') . '/' . static::getGitDirectory();
    }
}
