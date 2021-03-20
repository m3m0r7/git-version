|日本語|[English](./README.md)|

# Git Version - PHP code version manager with Git
このライブラリを使用することで、PHP コードのバージョンを表すために Git のコミットハッシュやタグを用いて表現することができるようになります。
多くのライブラリの場合、原則 `git` コマンドの実行を必須としており、そのためにわざわざ不必要に別プロセスを起動するといったことが行われています。
さらに `git` コマンドをカスタマイズやエイリアスを登録して挙動を変更しているデベロッパーも少なからずおり（私もカスタマイズしています）、git コマンドを差し替えられないという問題点がありました。
このライブラリは `git` コマンドを一切使用せず、コミットハッシュやバージョニングされたタグの値を取得することで、安全に PHP コードのバージョニングを行えるようになっています。

# 最終のコミットハッシュを取得する
最終のコミットハッシュを取得するのは簡単で、以下のように実行します。

```php
<?php
$version = Version::make(
    // .git ディレクトリが設置されている場所までのパス
    __DIR__
);

echo $version->getHash() . "\n";
```

上記は以下のように出力されます。

```
8bd8cfcff3b2b0fb22ab5e42be7f38f5a74e3d5f
```

もし、短いハッシュ値が必要な場合は、第一引数に true を渡すことで短いハッシュ値を取得することができます。

```php
<?php
$version = Version::make(
    // .git ディレクトリが設置されている場所までのパス
    __DIR__
);

echo $version->getHash(true) . "\n";
```


上記は以下のように出力されます。


```
8bd8cfcf
```

# バージョニングされたタグを取得する
バージョニングされたタグを取得するには以下のようにします。

```php
<?php
$version = Version::make(
    // .git ディレクトリが設置されている場所までのパス
    __DIR__
);

$versionedTag = $version->getVersionedTag();

echo $versionedTag->getVersion() . "\n";
echo $versionedTag->getHash() . "\n";
```

上記は以下のように出力されます。

```
0.0.2
2c80da2c2aa5767a5a8f89b4c78135a4dbc3e8e9
```

もし特定のバージョンのハッシュ値が必要な場合は `Version::getVersionedTag` の第一引数にバージョンを指定することも可能です。

```php
<?php
$version = Version::make(
    // .git ディレクトリが設置されている場所までのパス
    __DIR__
);

$versionedTag = $version->getVersionedTag('0.0.1');

echo $versionedTag->getVersion() . "\n";
echo $versionedTag->getHash() . "\n";
```

上記は以下のように出力されます。

```
0.0.1
b425291e8eaf03c0c0b6948015826bb2e5049019
```

もちろん、このハッシュ値も短いハッシュ値として取得することも可能です。最初の方に解説した方法同様に第一引数に true を渡すことで可能です。


```php
<?php
$version = Version::make(
    // .git ディレクトリが設置されている場所までのパス
    __DIR__
);

$versionedTag = $version->getVersionedTag();

echo $versionedTag->getVersion() . "\n";
echo $versionedTag->getHash(true) . "\n";
```

上記は以下のように出力されます。

```
0.0.2
2c80da2c
```

# PHPUnit test

```shell script
./vendor/bin/phpunit ./tests
```
