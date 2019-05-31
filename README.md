<p align="center">
    <a href="https://www.awes.io/?utm_source=github&utm_medium=repository" target="_blank" rel="noopener noreferrer">
        <img width="100" src="https://static.awes.io/promo/Logo_sign_color.svg" alt="Awes.io logo">
    </a>
</p>

<h1 align="center">Repository</h1>

<p align="center">Implementation of repository pattern for Laravel. The package allows out-of-the-box filtering of data based on parameters in the <b>request</b>, and also allows you to quickly integrate the list filters and custom criteria.</p>

<p align="center">
    <a href="https://www.awes.io/?utm_source=github&amp;utm_medium=shields">
        <img src="https://repo.pkgkit.com/4GBWO/awes-io/repository/badges/master/coverage.svg" alt="Coverage report" >
    </a>
    <a href="https://www.awes.io/?utm_source=github&amp;utm_medium=shields">
        <img src="https://www.pkgkit.com/4GBWO/awes-io/repository/version.svg" alt="Last version" >
    </a>
    <a href="https://www.awes.io/?utm_source=github&amp;utm_medium=shields">
        <img src="https://repo.pkgkit.com/4GBWO/awes-io/repository/badges/master/build.svg" alt="Build status" >
    </a>
    <a href="https://www.awes.io/?utm_source=github&amp;utm_medium=shields">
        <img src="https://www.pkgkit.com/4GBWO/awes-io/repository/downloads.svg" alt="Downloads" >
    </a>
    <a href="https://www.awes.io/?utm_source=github&amp;utm_medium=shields">
        <img src="https://img.shields.io/github/license/awes-io/repository.svg" alt="License" />
    </a>
    <a href="https://www.awes.io/?utm_source=github&amp;utm_medium=shields">
        <img src="https://www.pkgkit.com/4GBWO/awes-io/repository/status.svg" alt="CDN Ready" /> 
    </a>
    <a href="https://www.awes.io/?utm_source=github&amp;utm_medium=shields" target="_blank">
        <img src="https://static.pkgkit.com/badges/laravel.svg" alt="laravel" />
    </a>
    <a href="https://www.awes.io/?utm_source=github&amp;utm_medium=shields">
        <img src="https://img.shields.io/github/last-commit/awes-io/repository.svg" alt="Last commit" />
    </a>
    <a href="https://github.com/awes-io/awes-io">
        <img src="https://ga-beacon.appspot.com/UA-134431636-1/awes-io/repository" alt="Analytics" />
    </a>
    <a href="https://www.pkgkit.com/?utm_source=github&amp;utm_medium=shields">
        <img src="https://www.pkgkit.com/badges/hosted.svg" alt="Hosted by Package Kit" />
    </a>
    <a href="https://www.patreon.com/join/awesdotio">
        <img src="https://static.pkgkit.com/badges/patreon.svg" alt="Patreon" />
    </a>
</p>

##
<p align="center">
    <img src="../.github/IMAGES/repository-cover.png?raw=true" alt="Repository Laravel" />
</p>


## Table of Contents

- <a href="#installation">Installation</a>
- <a href="#configuration">Configuration</a>
- <a href="#overview">Overview</a>
- <a href="#usage">Usage</a>
    - <a href="#create-a-model">Create a Model</a>
    - <a href="#create-a-repository">Create a Repository</a>
    - <a href="#use-built-in-methods">Use built-in methods</a>
    - <a href="#create-a-criteria">Create a Criteria</a>
    - <a href="#scope-filter-and-order">Scope, Filter, and Order</a>
- <a href="#testing">Testing</a>

## Installation

Via Composer

``` bash
$ composer require awes-io/repository
```

The package will automatically register itself.

## Configuration

First publish config:

```bash
php artisan vendor:publish --provider="AwesIO\Repository\RepositoryServiceProvider" --tag="config"
```

```php
// $repository->smartPaginate() related parameters
'smart_paginate' => [
    // name of request parameter to take paginate by value from
    'request_parameter' => 'limit',
    // default paginate by value
    'default_limit' => 10,
    // max paginate by value
    'max_limit' => 100,
]
```

## Overview


##### Package allows you to filter data based on incoming request parameters:

```
https://example.com/news?title=Title&custom=value&orderBy=name_desc
```

It will automatically apply built-in constraints onto the query as well as any custom scopes and criteria you need:

```php
protected $searchable = [
    // where 'title' equals 'Title'
    'title',
];

protected $scopes = [
    // and custom parameter used in your scope
    'custom' => MyScope::class,
];
```

```php
class MyScope extends ScopeAbstract
{
    public function scope($builder, $value, $scope)
    {
        return $builder->where($scope, $value)->orWhere(...);
    }
}
```

Ordering by any field is available:

```php
protected $scopes = [
    // orderBy field
    'orderBy' => OrderByScope::class,
];
```

Package can also apply any custom criteria:

```php
return $this->news->withCriteria([
    new MyCriteria([
        'category_id' => '1', 'name' => 'Name'
    ])
    ...
])->get();
```

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

Extend it from `AwesIO\Repository\Eloquent\BaseRepository` and provide `entity()` method to return full model class name:

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

### Use built-in methods

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

Paginate the given query:

```php
$news = $this->news->paginate(15);
```

Paginate the given query into a simple paginator:

```php
$news = $this->news->simplePaginate(15);
```

Paginate the given query by 'limit' request parameter:

```php
$news = $this->news->smartPaginate();
```

Add an "order by" clause to the query:

```php
$news = $this->news->orderBy('title', 'desc')->get();
```

Save a new model and return the instance:

```php
$news = $this->news->create($request->all());
```

Update a record:

```php
$this->news->update($request->all(), $id);
```

Delete a record by id:

```php
$this->news->destroy($id);
```

Attach models to the parent:

```php
$this->news->attach($parentId, $relationship, $idsToAttach);
```

Detach models from the relationship:

```php
$this->news->detach($parentId, $relationship, $idsToDetach);
```

Find model or throw an exception if not found:

```php
$this->news->findOrFail($id);
```

Execute the query and get the first result or throw an exception:

```php
$this->news->firstOrFail();
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
            ]),
            new WhereAdmin(),
            ...
        ])->get();
    }
}
```

### Scope, Filter and Order

In your repository define which fields can be used to scope your queries by setting `$searchable` property.

```php
protected $searchable = [
    // where 'title' equals parameter value
    'title',
    // orWhere equals
    'body' => 'or',
    // where like
    'author' => 'like',
    // orWhere like
    'email' => 'orLike',
];
```

Search by searchables:

```php
public function index($request)
{
    return $this->news->scope($request)->get();
}
```

```
https://example.com/news?title=Title&body=Text&author=&email=gmail
```

Also several serchables enabled by default:

```php
protected $scopes = [
    // orderBy field
    'orderBy' => OrderByScope::class,
    // where created_at date is after
    'begin' => WhereDateGreaterScope::class,
    // where created_at date is before
    'end' => WhereDateLessScope::class,
];
```

```php
$this->news->scope($request)->get();
```

Enable ordering for specific fields by adding `$orderable` property to your model class:

```php
public $orderable = ['email'];
```

```
https://example.com/news?orderBy=email_desc&begin=2019-01-24&end=2019-01-26
```

`orderBy=email_desc` will order by email in descending order, `orderBy=email` - in ascending

You can also build your own custom scopes. In your repository override `scope()` method:

```php
public function scope($request)
{
    // apply build-in scopes
    parent::scope($request);

    // apply custom scopes
    $this->entity = (new NewsScopes($request))->scope($this->entity);

    return $this;
}
```

Create your `scopes` class and extend `ScopesAbstract`

```php
use AwesIO\Repository\Scopes\ScopesAbstract;

class NewsScopes extends ScopesAbstract
{
    protected $scopes = [
        // here you can add field-scope mappings
        'field' => MyScope::class,
    ];
}
```

Now you can build any scopes you need:

```php
use AwesIO\Repository\Scopes\ScopeAbstract;

class MyScope extends ScopeAbstract
{
    public function scope($builder, $value, $scope)
    {
        return $builder->where($scope, $value);
    }
}
```

## Testing

The coverage of the package is <a href="https://www.awes.io/?utm_source=github&amp;utm_medium=shields"><img src="https://repo.pkgkit.com/4GBWO/awes-io/repository/badges/master/coverage.svg" alt="Coverage report"></a>.
                                   
You can run the tests with:

```bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Credits

- [Galymzhan Begimov](https://github.com/begimov)
- [All Contributors](contributing.md)

## License

[MIT](http://opensource.org/licenses/MIT)
