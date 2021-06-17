# A collection of helper classes to make fighting the good fight easier

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bluefyn-international/sidekick.svg?style=flat-square)](https://packagist.org/packages/bluefyn-international/sidekick)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/bluefyn-international/sidekick/run-tests?label=tests)](https://github.com/bluefyn-international/sidekick/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/bluefyn-international/sidekick/Check%20&%20fix%20styling?label=code%20style)](https://github.com/bluefyn-international/sidekick/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bluefyn-international/sidekick.svg?style=flat-square)](https://packagist.org/packages/bluefyn-international/sidekick)

Collection of helper classes to make fighting the good fight even easier.

## Installation

You can install the package via composer:

```bash
composer require bluefyn-international/sidekick
```

## Usage

```php
$ids = BluefynInternational\Helpers\Strings::stringIdsToCollection('1,3,45, asdf,66,1,45,3');
var_dump($ids);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [BluefynInternational](https://github.com/bluefyn-international)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
