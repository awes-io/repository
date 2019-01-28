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

### Create a Model

Create your model:

```php
namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model 
{
    ...
}
```

### Create a Repository

Extend it from AwesIO\Repository\Eloquent\BaseRepository and provide entity() method to return full model class name:

```php
namespace App;

use AwesIO\Repository\Eloquent\BaseRepository;

class NewsRepository extends BaseRepository
{
    public function entity()
    {
        return News::class;
    }
}
```

### Use

```php
use App\NewsRepository;

class NewsController extends BaseController 
{
    protected $news;

    public function __construct(NewsRepository $news)
    {
        $this->news = $news;
    }
    ....
}
```

Execute the query as a "select" statement or get all results:

```php
$news = $this->news->get();
```

Execute the query and get the first result:

```php
$news = $this->news->first();
```

Find a model by its primary key:

```php
$news = $this->news->find(1);
```

Add basic where clauses and execute the query:

```php
$news = $this->news->->findWhere([
        // where id equals 1
        'id' => '1',
        // other "where" operations
        ['news_category_id', '<', '3'],
        ...
    ]);
```

### Create a Criteria

Criteria are a way to build up specific query conditions.

```php
use AwesIO\Repository\Contracts\CriterionInterface;

class MyCriteria implements CriterionInterface {

    protected $conditions;
    
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }

    public function apply($entity)
    {
        foreach ($this->conditions as $field => $value) {
            $entity = $entity->where($field, '=', $value);
        }
        return $entity;
    }
}
```

Multiple Criteria can be applied:

```php
use App\NewsRepository;

class NewsController extends BaseController 
{
    protected $news;

    public function __construct(NewsRepository $news)
    {
        $this->news = $news;
    }

    public function index()
    {
        return $this->news->withCriteria([
            new MyCriteria([
                'category_id' => '1', 'name' => 'Name'
            ])
        ])->get();
    }
}
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
