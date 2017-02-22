# CoRex Support
Support classes and helpers.


### Config/Config
Basic configuration class/helper which works almost the same way as Laravel.
- Multiple locations are supported through apps.
- Configuration files (sections) live in a directory named "config" in the root directory
of your project. It is possible to change this path via registerApp().

Generel usage.
```php
// Register path for myApp.
Config::registerApp('/my/app/path', 'myApp');

// Get firstname of actor from global access.
$firstname = Config::get('actor.firstname');

// Get firstname of actor from myApp.
$firstname = Config::get('actor.firstname', null, 'myApp');
```

Getting configuration as object.
```php
$myObject = Config::getObject('actor', MyObject::class);
```

Getting configuration parsed on closure.
```php
$data = Config::getClosure('actor', function ($data) {
    return $data;
});
```

Getting section.
```php
$data = Config::get('actor');
```


### Code/Convention
Helpers to convert to studly-case, pascal-case, camel-case, snake-case and kebab-case.

```php
// Convert to studly case.
$data = Convention::studly($data);

// Convert to pascal case.
$data = Convention::pascal($data);

// Convert to camel case.
$data = Convention::camel($data);

// Convert to snake case.
$data = Convention::snake($data);

// Convert to kebab case.
$data = Convention::kebab($data);
```

### System/Directory
Various directory helpers.
```php
```


### System/File
Various file helpers (i.e. stub, json, etc.)
```php
```


### System/Input
```php
```


### System/Path
Basic path getters (can be used in other packages by overriding getPackagePath()).
```php
```


### Arr
Various array helpers.
```php
```


### Collection
```php
```


### Container
Container to help with data manipulation ie. array/json.
```php
```


### Properties (abstract)
Simple abstract class with option to parse array of data which will be parsed to existing properties on class (private, protected and public).
```php
class Properties extends \CoRex\Support\Properties
{
    private $privateValue;
    protected $protectedValue;
    public $publicValue;
}
$properties = new Properties([
    'privateValue' => 'something',
    'protectedValue' => 'something',
    'publicValue' => 'something'
]);
```


### Str
Various string helpers (multi-byte).
```php
```
