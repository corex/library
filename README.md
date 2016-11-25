# CoRex Support
Support classes and helpers.

### Config
Basic configuration class/helper which works the same way as Laravel, but with a twist. You can have multiple configuration locations.
- Support for multiple locations through "apps".
- Support for getting configuration as object.
- Support for getting configuration as closure.
- Support for dot notation on configuration values.
- Support for getting complete section (values) of configuration.
- Support for default config-path at {root}/config.

### Path
Basic path getters.
- Support for getting root (option to add segments).
- Support for getting package (option to add segments).
- Support for getting vendor-name and package-name (requires overriding getPackagePath()).

### Arr
Various array helpers.
- Support for getting value from data.
- Support for getting last element of array.
- Support for removing last element from array.
- Support for checking if array is list (0..n).
- Support for converting array to associative array.
