# Repository

[![Coverage report](http://gitlab.awescode.com/awes-io/repository/badges/master/coverage.svg)](https://www.awes.io/)
[![Build status](http://gitlab.awescode.com/awes-io/repository/badges/master/build.svg)](https://www.awes.io/)
[![Composer Ready](https://www.awc.wtf/awes-io/repository/status.svg)](https://www.awes.io/)
[![Downloads](https://www.awc.wtf/awes-io/repository/downloads.svg)](https://www.awes.io/)
[![Last version](https://www.awc.wtf/awes-io/repository/version.svg)](https://www.awes.io/) 


PHP Repository package. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require awes-io/repository
```

The package will automatically register itself.

## Usage

```php
use AwesIO\Repository\Facades\Repository;

Repository::lowerStr('Some String'); // 'some string'

Repository::count(); // 1
```

## Testing

You can run the tests with:

```bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [:author_name][link-author]
- [All Contributors][link-contributors]

## License

GNU General Public License v3.0. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/awes-io/repository.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/awes-io/repository.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/awes-io/repository/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/awes-io/repository
[link-downloads]: https://packagist.org/packages/awes-io/repository
[link-travis]: https://travis-ci.org/awes-io/repository
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/awes-io
[link-contributors]: ../../contributors]
