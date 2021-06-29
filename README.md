# Maximo Query

This package enables you to request and retrieve data from Maximo using a custom query builder.

**IMPORTANT**: v2 of the package has breaking changes and should not be used in existing projects without taking the necessary refactoring into account. Please see the upgrade section for more information.

## Installation

Add the following to your `composer.json` file:

```
"require": {
    ...
    "nrbusinesssystems/maximo-query": "^2.0"
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

#### Executing The Query And Retrieving The Resource

Almost all the methods return the current instance and as such can be chained to your heart's content.

Once you are finished building the query, simply calling `get()` will execute the query and return  an instance of the `MaximoResponse` class.

#### Authentication

Upon executing the query, the package make an initial request to authenticate using the `maximo_username` and `maximo_password` variables set in the config file.

The cookies returned are then sent as part of the main request payload.

The authentication cookies are stored in the cache for the configured cache lifetime specified in the `cache_ttl_minutes` config variable thus removing the need to authenticate for subsequent requests.

If you are running multiple sites from the same domain (e.g. https://system/one and https://system/two) each will require its own cookie key to avoid cross-site interference. This can be set in the config or the system `.env` using the `MAXIMO_KEY` setting.

### Creating Resources

Creating a new resource is as simple as calling the `create` method and passing an array:

```
$response = MaximoQuery::withObjectStructure('trim')
	->create([
		'class' => 'SR',
		'assetsiteid' => 'TRIM',
		'siteid' => 'TRIM',
		'nrbusinessarea' => 'Internal to NR',
		'assetnum' => 'MAXIMO',
		'description' => 'Some Title',
		'description_longdescription' => 'Some description',
		'reportdate' => Carbon::Now()->format('Y-m-d\TH:i:s+00:00'),
		'nraffectedperson' => 'Christopher Abey',
		'nraffectedemail' => 'christopher.abey@networkrail.co.uk',
	]);
```

#### Adding Attachments To Resources

Adding files to the resource to be created is as simple as calling the `withAttachments` method before `create`
and passing in one or more `Illuminate\Http\UploadedFile` objects:

```
$fileOne = $request->fileOne;
$fileTwo = $request->fileTwo;

$response = MaximoQuery::withObjectStructure('trim')
	->withAttachments($fileOne, $fileTwo)
	->create([
		...
	]);

```

This will create the necessary structure for each file, extract the file name, base64 encode the content and append it to the data passed into the `create` method.

#### Properties

By default, the response from the `create` method will only return the `href` of the newly created resource. To retrieve additional data in the response, you can pass in an array of properties as the 2nd parameter of the `create` method:

```
$response = MaximoQuery::withObjectStructure('trim')
	->withAttachments($fileOne, $fileTwo)
	->create(
		[...],
		['href', 'ticketid', 'description']
	);
```

### Updating Resources

In order to update a resource in Maximo, you must first have the unique URL of the resource in question. By fluently constructing your query to contain one or more where clauses, the package will retrieve the resource url and then use it to make the update request:

```
$response = MaximoQuery::withObjectStructure('trim')
	->where('ticketid', 'ABEY12345')
	->update([
		'description' => 'A new title',
	]);
```

Like the `create` method, by default only the `href` of the resource is returned and additional data can be returned by passing in an array of properties as the 2nd parameter of the `update` method.

**IMPORTANT**
There are several instances where an exception will be thrown when using the `update` method:

* If no where clause has been specifed
* If more than one resource is returned
* If no resources where found

While attachments cannot be deleted via the API, they can be added while updating a resource using the `withAttachments` method described above.


### MaximoResponse Object

All successful responses return a `MaximoResponse` object. The response object is immutable so all the methods below can be called without affecting the original object. 

To get the raw `Illuminate\Http\Client\Response` object, simply call

```
$obj = $response->raw(); 
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
$value = $response->filter('member');
```

You can also choose to return the filtered data as a `Illuminate\Support\Collection` by passing in the 2nd boolean parameter:

```
$collection = $response->filter('member', true);
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

### Upgrading To V2

The response returned from Maximo is no longer namespaced i.e. `rdfs:member` to simplify and reduce the response payload. Simply removing the prefix is all that is needed for this change.

The `raw` method of the `MaximoResponse` class now returns an instance of `Illuminate\Http\Client\Response` instead of a JSON string.

A new `MaximoQuery` instance is returned when using the Facade rather than the cached singleton as with previous versions. This means calling `MaximoQuery::withObjectStructure('trim')` is the same as calling `(new MaximoQuery())->withObjectStructure('trim')`.





### Testing

When utilising MaximoQuery in your tests, you can apply your expectations directly to the class instead of making your own mocks:

```
MaximoQuery::shouldReceive('withObjectStructure')
    -> andThrow(new InvalidResponse());
```

## Credits

- [Christopher Abey](https://github.com/nrbusinesssystems)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
