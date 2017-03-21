# CoRex Support
Support classes and helpers.
The purpose of this package is to have one package with the most basic classes and helpers available.
Some of the code is heavily inspired by Laravel, Yii and other frameworks.

**_Versioning for this package follows http://semver.org/. Backwards compatibility might break on upgrade to major versions._**

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


### Config
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

A few examples.
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

A few examples.
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
Various input helpers to get information from environment.

A few examples.
```php
// Get base url.
$baseUrl = Input::getBaseUrl();

// Get user agent.
$userAgent = Input::getUserAgent();

// Get remote address.
$remoteAddress = Input::getRemoteAddress();

// Get headers.
$headers = Input::getHeaders();
```


### System/Path
Basic path getters (can be used in other packages by overriding getPackagePath()).

A few examples.
```php
// Get root of project.
$pathRoot = Path::getRoot();

// Get config-path of project-root.
$pathConfig = Path::getRoot(['config']);

// Get name of package.
$package = Path::getPackageName();

// Get name of vendor.
$package = Path::getVendorName();
```

### System/Session
Session handler.

A few examples.
```php
// Set session variable.
Session::set('actor', 'Roger Moore');

// Get session variable.
$actor = Session::get('actor');

// Check if session variable exists.
if (!Session::has('actor')) {
}
```

### System/Token
Token handler (uses Session handler).

A few examples.
```php
// Create csrf token.
$csrfToken = Token::create('csrf');

// Check csrf token.
if (!Token::isValid($csrfToken)) {
}
```

### Arr
Various array helpers.

A few examples.
```php
// Get firstname from array via dot notation.
$firstname = Arr::get($array, 'actor.firstname');

// Set firstname on array via dot notation.
Arr::set($array, 'actor.firstname', $firstname);

// Pluck firstnames from list of actors.
$firstnames = Arr::pluck($actors, 'firstname');
```


### Collection
Helper for manipulation of elements (collections).

A few examples.
```php
// Update each element in collection.
$collection = new Collection($actors);
$collection->each(function (&$actor) {
    $actor->firstname = 'Mr. ' . $actor->firstname;
});

// Get sum of value.
$collection = new Collection($values);
$sum = $collection->sum('amount');

// Loop through actors.
$collection = new Collection($actors);
foreach ($collection => $actor) {
    var_dump($actor->firstname);
};

// Get last element.
$collection = new Collection($actors);
$lastElement = $collection->last();
```


### Container
Container to help with data manipulation ie. array/json.

A few examples.
```php
// Load json.
$container = new Container();
$container->loadJson($filename);

// Get firstname of actor using dot notation.
$firstname = $container->get('actor.firstname');
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

A few examples.
```php
// Get first 4 characters of string.
$left = Str::left($string, 4);

// Check if string starts with 'Test'.
$startsWith = Str::startsWith($string, 'Test');

// Limit text to 20 characters with '...' at the end.
$text = Str::limit($text, 20, '...');

// Replace tokens.
$text = Str::replaceToken($text, [
    'firstname' => 'Roger',
    'lastname' => 'Moore'
]);
```


### StrList
Various string list helpers (multi-byte).

A few examples.
```php
// Add 'test' to string with separator '|'.
$string = StrList::add($string, 'test', '|');

// Remove 'test' from string.
$string = StrList::remove($string, 'test', '|');

// Check if 'test' exist in string.
$exist = StrList::exist($string, 'test');
```
