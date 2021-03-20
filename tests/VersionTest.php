<?php

declare(strict_types=1);

namespace Tests;

use GitVersion\Version;
use GitVersion\VersionException;
use GitVersion\VersionInfo;
use GitVersion\VersionInfoInterface;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function testGetLongHash()
    {
        $hash = Version::make(__DIR__ . '/../')->getHash();
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
        $hash = Version::make(__DIR__ . '/../')->getHash(true);
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
            ->getHash();

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
            ->getHash();
    }


    public function testInvalidDirectory()
    {
        $this->expectException(VersionException::class);
        Version::make(__DIR__ . '/invalid-directory')
            ->getHash();
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

    public function testGetVersions()
    {
        $version = Version::make(__DIR__ . '/../');
        $this->assertIsArray($version->getVersions());
    }

    public function testGetVersionedTag()
    {
        $version = Version::make(__DIR__ . '/../');
        $versionedTag = $version->getVersionedTag();

        $this->assertInstanceOf(VersionInfoInterface::class, $versionedTag);

        $this->assertSame(
            40,
            strlen($versionedTag->getHash())
        );

        $this->assertMatchesRegularExpression(
            '/\A[A-Fa-f0-9]+\z/',
            $versionedTag->getHash()
        );

        $this->assertMatchesRegularExpression(
            '/\A[0-9\.]+\z/',
            $versionedTag->getVersion()
        );
    }

    public function testGetVersionedTagShortly()
    {
        $version = Version::make(__DIR__ . '/../');
        $versionedTag = $version->getVersionedTag();

        $this->assertInstanceOf(VersionInfoInterface::class, $versionedTag);

        $this->assertSame(
            VersionInfo::SHORT_HASH_SIZE,
            strlen($versionedTag->getHash(true))
        );

        $this->assertMatchesRegularExpression(
            '/\A[A-Fa-f0-9]+\z/',
            $versionedTag->getHash(true)
        );
    }

    public function testGetVersionedTagSpecified()
    {
        $version = Version::make(__DIR__ . '/../');
        $versionedTag = $version->getVersionedTag('0.0.1');

        $this->assertInstanceOf(VersionInfoInterface::class, $versionedTag);

        $this->assertSame(
            40,
            strlen($versionedTag->getHash())
        );

        $this->assertMatchesRegularExpression(
            '/\A[A-Fa-f0-9]+\z/',
            $versionedTag->getHash()
        );

        $this->assertMatchesRegularExpression(
            '/\A[0-9\.]+\z/',
            $versionedTag->getVersion()
        );
    }

    public function testGetVersionedTagInvalidSpecified()
    {
        $this->expectException(VersionException::class);
        $version = Version::make(__DIR__ . '/../');
        $versionedTag = $version->getVersionedTag('invalid-specified-version');
    }
}