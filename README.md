# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nrbusinesssystems/maximo-query.svg?style=flat-square)](https://packagist.org/packages/nrbusinesssystems/maximo-query)
[![Build Status](https://img.shields.io/travis/nrbusinesssystems/maximo-query/master.svg?style=flat-square)](https://travis-ci.org/nrbusinesssystems/maximo-query)
[![Quality Score](https://img.shields.io/scrutinizer/g/nrbusinesssystems/maximo-query.svg?style=flat-square)](https://scrutinizer-ci.com/g/nrbusinesssystems/maximo-query)
[![Total Downloads](https://img.shields.io/packagist/dt/nrbusinesssystems/maximo-query.svg?style=flat-square)](https://packagist.org/packages/nrbusinesssystems/maximo-query)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require nrbusinesssystems/maximo-query
```

## Usage

``` php
//This is fully custom and not an extension of eloquent builder
$response = MaximoQuery::withObjectStructure('nrroadveh')
  ->select(['assetnum', 'costcentre', 'nrmanufacturer', 'description', 'nrcolour', 'leasecompany', 'motduedate'])
  ->where('siteid', 'rams')
  ->where('status', 'In Service')
  ->paginate(20)
  ->get(); //creates a new instance of MaximoHttp and returns a MaximoResponse object

$response
  ->filter('rdfs:member') //recursively searches response data for given key and returns the corresponding value
  ->toCollection(); //any collection method can now be called
// ->pluck('spi:motduedate', 'spi:assetnum');
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email christopher.abey@networkrail.co.uk instead of using the issue tracker.

## Credits

- [Christopher Abey](https://github.com/nrbusinesssystems)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
