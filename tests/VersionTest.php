<?php

declare(strict_types=1);

namespace Tests;

use GitVersion\Version;
use GitVersion\VersionException;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function testGetLongHash()
    {
        $hash = Version::make(__DIR__ . '/../')->getLastHash();
        $this->assertSame(
            40,
            strlen($hash)
        );
        $this->assertMatchesRegularExpression(
            '/\A[A-Fa-f0-9]+\z/',
            $hash
        );
    }

    public function testGetShortHash()
    {
        $hash = Version::make(__DIR__ . '/../')->getLastHash(true);
        $this->assertSame(
            Version::SHORT_HASH_SIZE,
            strlen($hash)
        );
        $this->assertMatchesRegularExpression(
            '/\A[A-Fa-f0-9]+\z/',
            $hash
        );
    }

    public function testChangeBranch()
    {
        $hash = Version::make(__DIR__ . '/../', 'refs/heads/master')
            ->getLastHash();

        $this->assertSame(
            40,
            strlen($hash)
        );
        $this->assertMatchesRegularExpression(
            '/\A[A-Fa-f0-9]+\z/',
            $hash
        );
    }

    public function testInvalidBranchName()
    {
        $this->expectException(VersionException::class);
        Version::make(__DIR__ . '/../', 'invalid-branch-name')
            ->getLastHash();
    }


    public function testInvalidDirectory()
    {
        $this->expectException(VersionException::class);
        Version::make(__DIR__ . '/invalid-directory')
            ->getLastHash();
    }

    public function testGetBranchName()
    {
        $this->assertSame(
            'refs/heads/master',
            Version::make(__DIR__ . '/../')->getBranchName()
        );
    }

    public function testSettings()
    {
        $version = Version::make(__DIR__ . '/../');

        $this->assertSame('HEAD', $version->getHEADFilePath());
        $this->assertSame('.git', $version->getGitDirectory());
        $this->assertSame('logs', $version->getLogDirectory());
        $this->assertSame(__DIR__ . '/../', $version->getDirectory());

        $version
            ->setHEADFilePath('HEAD-changed')
            ->setGitDirectory('.git-changed')
            ->setDirectory('dir-changed')
            ->setLogDirectory('logs-changed');


        $this->assertSame('HEAD-changed', $version->getHEADFilePath());
        $this->assertSame('.git-changed', $version->getGitDirectory());
        $this->assertSame('logs-changed', $version->getLogDirectory());
        $this->assertSame('dir-changed', $version->getDirectory());
    }
}