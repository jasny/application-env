Jasny ApplicationEnv
===

[![Build Status](https://travis-ci.org/jasny/application-env.svg?branch=master)](https://travis-ci.org/jasny/{{library}})
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/application-env/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/{{library}}/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/application-env/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/{{library}}/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6c5ec45d-5570-4e50-87ce-39cabc237f2b/mini.png)](https://insight.sensiolabs.com/projects/6c5ec45d-5570-4e50-87ce-39cabc237f2b)
[![BCH compliance](https://bettercodehub.com/edge/badge/jasny/application-env?branch=master)](https://bettercodehub.com/)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/application-env.svg)](https://packagist.org/packages/jasny/{{library}})
[![Packagist License](https://img.shields.io/packagist/l/jasny/application-env.svg)](https://packagist.org/packages/jasny/{{library}})

Logic around the common `APPLICATION_ENV` environment variable.

Check and process dot-separated sub-environments like `dev.testers.john`.

Installation
---

    composer require jasny/application-env

Usage
---

```php
use Jasny\ApplictionEnv;

$env = new ApplicationEnv(getenv('APPLICATION_ENV') ?? 'dev');

echo "Application environment: ", (string)$env;
```

### Check environment

The `is` method checks if the current application env matches the given one, or is a sub-environment of it.

```php
if ($env->is("dev")) {
   ini_set('display_errors', true);
}
```

This would match `dev` and `dev.testers.john`, but not `staging` or `prod.worker`.

### Get levels

    array getLevels(int from = 1, ?int to = null, callable $callback = null)

The `getLevels` method returns an array with environment variable levels. Starting from level 1 starts with the first
level, while starting from 0 will include an empty string.

The `to` parameter allows to limit how deep to traverse. By default the full application env is the last entry. 

A `callback` may be specified, which is called on each level.

**Example**

If the `APPLICATION_ENV` is `dev.testers.john` than `getLevels()` would yield;

```
[
  'dev',
  'dev.testers',
  'dev.testers.john'
]
```

To create a list of configuration files to be considered, you might use

```php
$configFiles = $env->getLevels(0, null, function(string $env) {
    return $env === '' ? "settings.{$env}.yml" : "settings.yml";
});
```
