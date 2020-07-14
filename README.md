# Maximo Query

This package enables you to request and retrieve data from Maximo using a custom query builder.

## Installation

Add the following to your `composer.json` file:

```
"require": {
    ...
    "nrbusinesssystems/maximo-query": "master"
},
"repositories": [
    ...
    {
        "type" : "vcs",
        "url" : "git@bitbucket.org:nrbusinesssystems/maximo-query.git"
    }
]
```

#### Configuration

Publish the config file and configure the `maximo_url`, `maximo_username` and `maximo_password` parameters.

```bash
php artisan vendor:publish --provider="Nrbusinesssystems\MaximoQuery\Providers\MaximoQueryServiceProvider" --tag="config"
```

## Usage

### Query Builder

You can either use the facade

```
$query = MaximoQuery::withObjectStructure('mxperson');
```

or by creating a new instance of the `MaximoQuery` class

```
$query = (new MaximoQuery)->withObjectStructure('mxperson');
```

#### Query Object

You must define a Maximo data object to query against by using the `withObjectStructure` method which takes the name of the object structure as its only parameter.

Not defining the data object will result in an exception being thrown.

#### Selecting Columns

By default, the query will not select anything and the request will just return the resource links for the individual records.

You can select data using the select method and passing in the columns requested:

```
$query = MaximoQuery::withObjectStructure('mxperson')
    ->select('displayname');
```

Or an array of columns:

```
$query = MaximoQuery::withObjectStructure('mxperson')
    ->select(['personuid', 'displayname']);
```

You can also select all the column available on the given object using:

```
$query = MaximoQuery::withObjectStructure('mxperson')
    ->selectAll();
```

This should be used with **caution** as many objects in Maximo have a **LOT** of columns!
It is far better to specify exactly what columns are required in order to reduce the response payload.

#### Where Clauses

Adding a basic where cause can be done simply by calling the `where` method.

Like eloquent, the `where` method accepts three parameters `$column`, `$operator` and `$value` and if only two parameters are passed, the `=` operator is assumed.

The following `$operators` can be used:
* `=` equals
* `>=` greater than equals
* `>` greater than
* `<` less than
* `<=` less than equals
* `!=` not equals

##### Other Where Methods

``` 
whereIn('firstname', ["Christopher", "Anthony", "Travis"])

whereNotIn('personuid', [1191, 46835, 46596])

whereStartsWith('lastname', 'Abe')

whereEndsWith('primaryemail', 'networkrail.co.uk')

whereLike('primaryemail', 'networkrail')

whereNull('some_nullable_column')

whereNotNull('some_nullable_column')

```

#### Ordering

You can request that the returned data is ordered using the `orderBy` method which accepts the column name and the direction to sort by `desc / asc`

```
orderBy('lastname', 'desc')
```

Or you can sort by multiple columns

```
orderBy(
    [
        ['lastname, 'desc'],
        ['firstname, 'asc']
    ]
)
```

#### Paging

Because of the potential to request a large amount of data, the query builder has a default of `1000` resource items per page.

This can be overridden using the `paginate()` method:

```
$query = MaximoQuery::withObjectStructure('mxperson')
    ->paginate(20);
```

To retrieve a specific page, pass the page number to the `get()` method:

```
$query = MaximoQuery::withObjectStructure('mxperson')
    ->paginate(20)
    ->get(2);
```

If you wish to disable pagination completely use the `withoutPagination()` method:
 
```
$query = MaximoQuery::withObjectStructure('mxperson')
    ->withoutPagination();
```

Be **VERY** careful when disabling pagination and ensure that your query has been sufficiently filtered down or you could end up with a very **LARGE** response payload!

#### Record Count

If you only want to retrieve the number of records for your given query you can use the `count()` method. This will immediately execute your request and return the record count.

```
MaximoQuery::withObjectStructure('mxperson')
    ->where('lastname', 'Thompson')
    ->count();
```

If you wish to return the record count with the resource collection you can use the `withCount()` method. This will include a `totalCount` attribute with the requested data.

```
MaximoQuery::withObjectStructure('mxperson')
    ->select(['personuid', 'displayname'])
    ->where('lastname', 'Thompson')
    ->withCount()
    ->get();
``` 

#### Null Values

By default, all requested columns are returned regardless of their values. If you wish to reduce the response payload, you may request that `null` values are not included in the response by using the `filterNullValues()` method.

#### Retrieving A Specific Record

Like Eloquent, you can use the `find()` method to retrieve a single resource by passing in the unique ID. This will immediately send the request and, if found, return the requested resource as an array of `attribute => value` pairs.

#### Executing The Query

Almost all the methods return the current instance and as such can be chained to your heart's content.

Once you are finished building the query, simply calling `get()` will execute the query and return  an instance of the `MaximoResponse` class.

#### Authentication

Upon executing the query, the package make an initial request to authenticate using the `maximo_username` and `maximo_password` variables set in the config file.

The cookies returned are then sent as part of the main request payload.

The authentication cookies are stored in the cache for the configured cache lifetime specified in the `cache_ttl_minutes` config variable thus removing the need to authenticate for subsequent requests.

### MaximoResponse Object

All successful responses return a `MaximoResponse` object. The response object is immutable so all the methods below can be called without affecting the original object. 

To get the raw json response simply call

```
$json = $response->raw(); 
```

The response can be converted into an array

``` 
$array = $response->toArray();
```

or an `Illuminate\Support\Collection`

```
$collection = $response->toCollection();
```

To retrieve the query URL directly from the response object

```
$url = $response->getUrl();
```


You can search the response recursively for a key and have it return the corresponding value:

```
$value = $response->filter('rdfs:member');
```

You can also choose to return the filtered data as a `Illuminate\Support\Collection` by passing in the 2nd boolean parameter:

```
$collection = $response->filter('rdfs:member', true);
```

If the key cannot be found a `KeyNotFound` exception is thrown.

If you requested that the record count be returned with the response payload, you can call the `getCount()` method on the response to retrieve it.

```
$count = $response->getCount();
```

If you call this method without having called the `withCount()` method on the query, a count of `0` will be returned.

To retrieving the next or previous page of a paginated response can be done like so:

```
$pageTwo = $response->nextPage();
$pageOne = $pageTwo->previousPage();
$pageThree = $pageTwo->nextPage();
```

Calling either of these methods makes another http request and returns a new instance of the `MaximoResponse` object.

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
