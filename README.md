|[日本語](./README-ja.md)|English|

# Git Version - Git commit hash-based version manager for PHP
Many libraries require the `git` command to be executed in principle, which unnecessarily starts a separate process.
In addition, there are many developers who customize the `git` command or register aliases to change its behavior, and there was a problem that the `git` command could not be replaced in the libraries.
This library never use the `git` command at all, but allows you to safely version your PHP code by retrieving commit hashes and versioned tag values.

# Requirements
- PHP >= 7.2

# Install
```
composer require m3m0r7/git-version
```

# Get last commit hash
Write code as same as below if you want to get last commit hash.

```php
<?php

declare(strict_types=1);

use GitVersion\Version;

require __DIR__ . '/vendor/autoload.php';

$version = Version::make(
    // Specified to `.git` directory path
    __DIR__
);

echo $version->getHash() . "\n";
```

The result is below:

```
8bd8cfcff3b2b0fb22ab5e42be7f38f5a74e3d5f
```

Set `true` to the first argument if you want to get short commit hash.

```php
<?php
declare(strict_types=1);

use GitVersion\Version;

require __DIR__ . '/vendor/autoload.php';

$version = Version::make(
    // Specified to `.git` directory path
    __DIR__
);

echo $version->getHash(true) . "\n";
```

The result is below:

```
8bd8cfcf
```

# Get versioned tag
Write code as same as below if you want to get versioned tag.

```php
<?php

declare(strict_types=1);

use GitVersion\Version;

require __DIR__ . '/vendor/autoload.php';

$version = Version::make(
    // Specified to `.git` directory path
    __DIR__
);

$versionedTag = $version->getVersionedTag();

echo $versionedTag->getVersion() . "\n";
echo $versionedTag->getHash() . "\n";
```

The result is below:

```
0.0.2
2c80da2c2aa5767a5a8f89b4c78135a4dbc3e8e9
```

You can specify the version to the first arguments of `Version::getVersionedTag` if you want to get specified versioned tag.

```php
<?php

declare(strict_types=1);

use GitVersion\Version;

require __DIR__ . '/vendor/autoload.php';

$version = Version::make(
    // Specified to `.git` directory path
    __DIR__
);

$versionedTag = $version->getVersionedTag('0.0.1');

echo $versionedTag->getVersion() . "\n";
echo $versionedTag->getHash() . "\n";
```

The result is below:

```
0.0.1
b425291e8eaf03c0c0b6948015826bb2e5049019
```


Set `true` to the first argument if you want to get short commit hash as same as above.


```php
<?php

declare(strict_types=1);

use GitVersion\Version;

require __DIR__ . '/vendor/autoload.php';

$version = Version::make(
    // Specified to `.git` directory path
    __DIR__
);

$versionedTag = $version->getVersionedTag();

echo $versionedTag->getVersion() . "\n";
echo $versionedTag->getHash(true) . "\n";
```

The result is below:

```
0.0.2
2c80da2c
```

# PHPUnit test

```shell script
./vendor/bin/phpunit ./tests
```
