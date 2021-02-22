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
composer update
```

```php
composer dump-autoload
```

You are now able to add the new fields for system.

## Features and Usage

### Firebase

#### Real time database
- Configure `STORE_DB=firebase` and `FIREBASE_CREDENTIALS={file-path}` at `.env` file, default is `database`.
- Go to https://<your-site>/configuration/field/edit, edit value and save, data will dispatch to real time database on firebase.

- Use `retrieveData($reference)` function in FirebaseService to get data by $reference.

``` Example:
$results = retrieveData('configuration/system/fields')
```
- Use `getDataByCode($code)` function in FirebaseService to get data by $code (code name)

``` Example:
$results = getDataByCode('firebase')
```

#### Storage

- Use `uploadFile($file, $type, $reference, $expiresAt)` function in FirebaseService to upload file.

``` Example:
 $image = $request->file('image'); //image file from frontend
 $type = image/png;
 $reference = 'images';
 $expiresAt = 'today';
 $results = uploadFile($image, $type, $reference, $expiresAt)
```

- Use `getFile($reference)` function in FirebaseService to get url and file info.

``` Example:
 $reference = 'images';
 $results = getFile($reference);
```

### Configuration fields

#### Create field
- Open `packages/GGPHP/Config/config/system.php` and add config data to `fields` (refer configuration at example data)
- Go to `ConfigController.php` file and override `$userRole` variable (allow user can edit) then add role to the fields `GGPHP\Config\config\system`.
- Go to `https://<your-site>/configuration/field/edit` , edit value then save.

#### Get field
- Use `getConfigByCode` method to get field data.

``` Example:
$results = getDataByCode('firebase')
```

### Dynamic api rate limiting
- Add `throttle` to `middleware` at `prefix => api`.
- Go to `https://<your-site>/configuration/throttles` to view all apis in the system.
- Click to `Edit` button to edit `Max Attempts` and `Decay Minutes`.

## API

### Configuration fields

#### Get all field
- Use `GET` method | `https://<your-site>/api/configuration/fields`

#### Get field by id
- Use `GET` method | `https://<your-site>/api/configuration/fields/{id}`

#### Update field
- Use `PATCH` method | `https://<your-site>/api/configuration/fields/reset`

``` Example param:
{
    "id" : 1,
    "code": "test",
    "value": {
        "max_attempts": 60,
        "decay_minutes": 1
    },
    "type": "text"
}
```
#### Reset all field to default value
- Use `GET` | `https://<your-site>/api/configuration/fields/reset`

### Throttles route

#### Get all throttles
- Use `GET` method | `https://<your-site>/api/configuration/throttles`

#### Get throttle data by name
- Use `GET` method | `https://<your-site>/api/configuration/throttles/{name}`

#### Reset all throttles value to default
- Use `GET` method | `https://<your-site>/api/configuration/throttles/reset`

#### Update value for throttle
- Use `POST` method | `https://<your-site>/api/configuration/throttles`

``` Example param:
{
    "name": "Route name",
    "max_attempts": 30,
    "decay_minutes": 1
}
```

## Testing

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
   'default' => 'default value',
   'access' => ['admin'],
],
[
   'code' => 'number-field',
   'type' => 'number',
   'name' => 'Number field',
   'title' => 'title field',
   'default' => 'default value',
   'validation' => 'required|min:1',
   'access' => ['user'],
],
[
   'code' => 'boolean-field',
   'type' => 'boolean',
   'name' => 'Boolean field',
   'title' => 'title field',
   'default' => false,
   'value' => true,
   'access' => ['admin'],
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
