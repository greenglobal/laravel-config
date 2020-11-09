# Laravel Config

[![Actions Status](https://github.com/greenglobal/laravel-config/workflows/Build/badge.svg)](https://github.com/greenglobal/laravel-config/actions)

This package is used for adding field options to system and configuring the api throttle.

## Installation

### Install with package folder
1. Unzip all the files to **packages/GGPHP/Config**.
2. Open `config/app.php` and add **GGPHP\Config\Providers\ConfigServiceProvider::class**.
3. Open `composer.json` of root project and add **"GGPHP\\Config\\": "packages/GGPHP/Config/src"**.
4. Run the following command
```php
composer dump-autoload
```

You are now able to add the new fields for system.

## Usage

### Use Firebase real time database
- configure `STORE_DB=firebase` at `.env` file, default is `database`.

### Add the configuration fields as need for system
- Open `packages/GGPHP/Config/config/system.php` and add config data to `fields` (refer configuration at example data)
- Go to `https://<your-site>/configuration/field/edit` , edit value then save.
- Use `getConfigByCode` method to get field data.

### Dynamic api rate limiting
- Add `throttle` to `middleware` at `prefix => api`.
- Go to `https://<your-site>/configuration/throttles`.

### Testing

``` bash
composer test
```
## Example data

Field options:
```php
[
   'code' => 'text-field',
   'type' => 'text',
   'name' => 'Text field',
   'title' => 'title field',
   'default' => 'default value'
],
[
   'code' => 'number-field',
   'type' => 'number',
   'name' => 'Number field',
   'title' => 'title field',
   'default' => 'default value',
   'validation' => 'required|min:1'
],
[
   'code' => 'boolean-field',
   'type' => 'boolean',
   'name' => 'Boolean field',
   'title' => 'title field',
   'default' => false,
   'value' => true
],
[
   'code' => 'selection field',
   'type' => 'select',
   'name' => 'selection field',
   'title' => 'title field',
   'options' => [
      [
         'title' => 'option 1',
         'value' => 1
      ],
      [
         'title' => 'option 2',
         'value' => 2
      ]
      ...
   ]
],

```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email tuannt@greenglobal.vn instead of using the issue tracker.

## Credits

- [TuanNT](https://github.com/ggphp)
- [HungHBM](https://github.com/HuynhHungManh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
