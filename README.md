# Model Policy  Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mystamystinc/model-policy-generator.svg?style=flat-square)](https://packagist.org/packages/mystamystinc/model-policy-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mystamystinc/model-policy-generator/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mystamystinc/model-policy-generator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/mystamystinc/model-policy-generator/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/mystamystinc/model-policy-generator/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mystamystinc/model-policy-generator.svg?style=flat-square)](https://packagist.org/packages/mystamystinc/model-policy-generator)

This is a package that simply creates policies for all the existing models in the project. 
It is customizable and easy to use.

## Installation

You can install the package via composer:

```bash
composer require mystamystinc/model-policy-generator
``` 

You can publish the config file with:

```bash
php artisan myst:install 

This is the contents of the published config file:

```php
 
return [ 
    'permissions' => [
        'view',
        'view_any',
        'create',
        'update',
        'restore',
        'restore_any',
        'replicate',
        'reorder',
        'delete',
        'delete_any',
        'force_delete',
        'force_delete_any',
    ],
    'models_directory' => 'app/Models',
    'policies_directory' => 'app/Policies',
];
``` 
 
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Gitonga George](https://github.com/GitongaGeorge)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
