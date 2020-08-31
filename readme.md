# ReviseOperation for Backpack for Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

Adds an interface for [```venturecraft/revisionable```](https://github.com/VentureCraft/revisionable) to your Backpack CRUDs, so that the admin can:
- see the changes that have been made to an entry;
- undo changes;

[```venturecraft/revisionable```](https://github.com/VentureCraft/revisionable) allows you to store, see and undo changes to entries on an Eloquent model. This package just provides an admin interface for it, in the form of a Backpack operation, that you can use on the CrudControllers of entities that have the Revisionable trait.

When used, this operation will show another button for each entry in the table view. On click, that button opens another page, which will allow an admin to see all changes and who made them:

![https://backpackforlaravel.com/uploads/docs-4-0/operations/revisions.png](https://backpackforlaravel.com/uploads/docs-4-0/operations/revisions.png)


## Installation

**Step 1.** Require the package:

``` bash
composer require backpack/revise-operation
```

This will automatically install ```venturecraft/revisionable``` too, if it's not already installed.

**Step 2.** Create the Revisions table:

``` bash
cp vendor/venturecraft/revisionable/src/migrations/2013_04_09_062329_create_revisions_table.php database/migrations/ && php artisan migrate
```

**Step 3.** Use RevisionableTrait on your model, and an ```identifiableName()``` method that returns an attribute on the model that the admin can use to distiguish between entries (ex: name, title, etc). If you are using another bootable trait be sure to override the boot method in your model.

```php
namespace MyApp\Models;

class Article extends Eloquent {
    use \Backpack\CRUD\CrudTrait, \Venturecraft\Revisionable\RevisionableTrait;

    public function identifiableName()
    {
        return $this->name;
    }

    // If you are using another bootable trait
    // be sure to override the boot method in your model
    public static function boot()
    {
        parent::boot();
    }
}
```

**Step 4.** In your CrudController, use the operation trait:
```php
<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

class CategoryCrudController extends CrudController
{
    use \Backpack\ReviseOperation\ReviseOperation;
```

For complex usage, head on over to [VentureCraft/revisionable](https://github.com/VentureCraft/revisionable) to see the full documentation and extra configuration options.

## Customizing views

If you need to change the operation views in any way, you can do so by creating a blade file with the same name in your `resources/views/vendor/backpack/revise-operation` directory. Blade files there take priority over files in the package.


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email hello@tabacitu.ro instead of using the issue tracker.

## Credits

- [Cristian Tabacitu][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/backpack/revise-operation.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/backpack/revise-operation.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/backpack/revise-operation/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/248960912/shield

[link-packagist]: https://packagist.org/packages/backpack/revise-operation
[link-downloads]: https://packagist.org/packages/backpack/revise-operation
[link-travis]: https://travis-ci.org/backpack/revise-operation
[link-styleci]: https://styleci.io/repos/248960912
[link-author]: https://github.com/backpack
[link-contributors]: ../../contributors
