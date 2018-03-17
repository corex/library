# CoRex Support
Support classes and helpers.
The purpose of this package is to have one package with the most basic classes and helpers available.
Some of the code is heavily inspired by Laravel, Yii and other frameworks.

**_Versioning for this package follows http://semver.org/. Backwards compatibility might break on upgrade to major versions._**

With version 3.0.0, this package has been thinned out. Removed packages has either been merged into existing code or been moved to separate packages.
- Code/Convention - merged into Str
- Config - Removed in favor of package corex/config.
- Collection - Removed in favor of package corex/collection.
- System/Cache - Removed in favor of package corex/cache.


### System/Console
Various console helpers.

A few examples.
```php
// Writeln text.
Console::writeln('this is a test');

// Writeln texts.
Console::writeln(['this is a test', 'this is line 2']);

// Show header.
Console::header('this is a test');

// Ask question.
$answer = Console::ask('Enter name');

// Enter password.
$password = Console::secret('Enter password');

// Show table.
Console::table($items, ['Header 1', 'Header 2']);

// Throw error (exception).
Console::throwError('this is an error');
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

// Get from file.
$content = File::get($filename);

// Load lines.
$lines = File::getLines($filename);

// Save content.
File::put($filename, $content);

// Save lines.
File::putLines($filename, $lines);

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

// Get json.
$array = File::getJson($filename);

// Put json.
File::putJson($filename, $array);

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
$pathRoot = Path::root();

// Get config-path of project-root.
$pathConfig = Path::root(['config']);

// Get name of package.
$package = Path::packageName();

// Get name of vendor.
$package = Path::vendorName();
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


### System/Template
Template.

A few examples.
```php
// Load a template and render.
$template = new Template($path/to/templates);
$template->loadTemplate('welcome');
$template->setToken('title', 'Welcome');
print($template->render());

// Set a template and render.
$template = new Template($path/to/templates);
$template->setTemplate('{title}');
$template->setToken('title', 'Welcome');
print($template->render());
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


### Container
Container to help with data manipulation ie. array/json.

A few examples.
```php
// Get json.
$container = new Container();
$container->getJson($filename);

// Get firstname of actor using dot notation.
$firstname = $container->get('actor.firstname');
```


### Base/BaseProperties (abstract)
Simple abstract class with option to parse array of data which will be parsed to existing properties on class (private, protected and public).
```php
class BaseProperties extends \CoRex\Support\Base\BaseProperties
{
    private $privateValue;
    protected $protectedValue;
    public $publicValue;
}
$properties = new BaseProperties([
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

// Create a unique string.
$identifier = Str::unique();

// Convert to pascal case.
$data = Convention::pascalCase($data);

// Convert to camel case.
$data = Convention::camelCase($data);

// Convert to snake case.
$data = Convention::snakeCase($data);

// Convert to kebab case.
$data = Convention::kebabCase($data);
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
