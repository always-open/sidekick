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

### Helpers

#### String Helper
```php
$ids = BluefynInternational\Helpers\Strings::stringIdsToCollection('1,3,45, asdf,66,1,45,3');
var_dump($ids);
```
#### Routes Helper
This helper stops redirect loops where a `url()->previous()` might be used but the user could have directly input the 
url so `previous` and `current` are the same. This helper stops that from happening while allowing you to specify where 
to go if that scenario happens.

If the user can edit user profiles and reach it from multiple screens the redirect response after saving might look like 
this:
```php
return response()->redirectTo(
    BluefynInternational\Helpers\Routes::toRouteIfBackIsLoop('user.report')
);
```

Here the user will either go to their previous URL or get sent to the user report.


### Traits

#### ByName
Add the trait to your model:
```php
<?php

namespace App\Models;

namespace BluefynInternational\Sidekick\Models\Traits\ByName;

class OrderStage extends Model
{
    use ByName;
    
    const NEXT_DAY = 'Next Day';
   ...
}
```
Use the trait to get the model by its name:
```php
$overnight = OrderStage::byName('overnight');
```

Works nicely when you're doing work with consts:
```php
$overnight = OrderStage::byName(OrderStage::NEXT_DAY);
```

#### CascadeUpdate
This trait is good if you need to update a last updated timestamp on related models such as a parent child relationship 
or line items on a document.

In the example class `Docuemnt` has multiple `LineItem` instances as children.

Within this exmaple you will need to override the `getRelationshipsToUpdate` method:
```php
class LineItem extends Model
{
    use CascadeUpdate;

    public function getRelationshipsToUpdate() : array
    {
        return [
            'Document',
        ];
    }

    public function CascadeUpdate() : HasOne
```

When an instance of LineItem is saved the `UPDATED_AT` column on the owner `Document` will be updated as well.

#### Ordered
This trait ensures that all instances have a sort column value that is next in line. Future TODO is to make it update 
other instances when one of their sort values is updated to keep all in proper order.


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
