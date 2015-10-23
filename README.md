# PhpSpec Git Extension

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