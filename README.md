# PhpSpec Git Extension

[![Travis](https://img.shields.io/travis/nick-jones/phpspec-git-extension.svg?style=flat-square)](https://travis-ci.org/nick-jones/phpspec-git-extension)
[![Packagist](https://img.shields.io/packagist/v/nick-jones/phpspec-git-extension.svg?style=flat-square)](https://packagist.org/packages/nick-jones/phpspec-git-extension)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg?style=flat-square)](https://php.net/)

This is a quick and simple extension for [phpspec](https://github.com/phpspec/phpspec) that will automatically add
generated classes and specifications to git.

## Installation

You can install this extension via [composer](http://getcomposer.org):

`composer require nick-jones/phpspec-git-extension`

You will then need to list configure this as an extension within your `phpspec.yml`:

```yaml
extensions:
  - PhpSpecExtension\Git\Extension
```