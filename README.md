# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ggphp/configuration.svg?style=flat-square)](https://packagist.org/packages/ggphp/configuration)
[![Build Status](https://img.shields.io/travis/ggphp/configuration/master.svg?style=flat-square)](https://travis-ci.org/ggphp/configuration)
[![Quality Score](https://img.shields.io/scrutinizer/g/ggphp/configuration.svg?style=flat-square)](https://scrutinizer-ci.com/g/ggphp/configuration)
[![Total Downloads](https://img.shields.io/packagist/dt/ggphp/configuration.svg?style=flat-square)](https://packagist.org/packages/ggphp/configuration)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require ggphp/configuration
```

## Usage

### Dynamic api rate limiting
- Add `throttle` to `middleware` at `prefix => api`.
- Go to `https://<your-site>/configuration/throttles`.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email tuannt@greenglobal.vn instead of using the issue tracker.

## Credits

- [TuanNT](https://github.com/ggphp)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
