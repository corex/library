# CoRex Support
Support classes and helpers.


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


### System/Directory
Various directory helpers.
```php
// Test if directory exists.
$exist = Directory::exist('/my/path');

// Check if directory is writeable.
$isWriteable = Directory::isWritable('/my/path');

// Make directory.
Directory::make('/my/path');

// Get entries of a directory.
$entries = Directory::entries('/my/path', '*', true, true, true);
```


### System/File
Various file helpers (i.e. stub, json, etc.)

```php
// Check if file exists.
$exist = File::exist($filename);

// Load file.
$content = File::load($filename);

// Load lines.
$lines = File::loadLines($filename);

// Save content.
File::save($filename, $content);

// Save lines.
File::saveLines($filename, $lines);

// Get stub.
$stub = File::getStub($filename, [
    'firstname' => 'Roger',
    'lastname' => 'Moore'
]);

// Get template.
$template = File::getTemplate($filename, [
    'firstname' => 'Roger',
    'lastname' => 'Moore'
]);

// Load json.
$array = File::loadJson($filename);

// Save json.
File::saveJson($filename, $array);

// Get temp filename.
$filename = File::getTempFilename();

// Delete file.
File::delete($filename);
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
