# ChangeLog

## 3.2.0

### Added
- Added Obj::hasMethod().


## 3.1.0

### Added
- Added Str::strpos().
- Added Str::indexOf().
- Added Str::contains().


## 3.0.1

### Fixed
- System/Input::getHost() now supports gethostname() if not set.
- Updated System/Input to handle server entries not set.


## 3.0.0
This release breaks code.

### Added
- Obj->getExtends() added.
- Obj->hasExtends() added.
- Arr::toArray() added.
- Arr::has() added.
- Arr::remove() added.
- Arr::toJson() added.
- System/Input::getUri() added.
- System/Input::getPort() added.
- System/Input::getQueryString() added.
- System/Input::getAuthUsername() added.
- System/Input::getAuthPassword() added.
- System/Input::getStandardPort() added.
- Bag->keys() added.
- Bag->prepareKey() added as protected to support changing key.

### Changed
- php 7 required.
- Class Code/Convention merged into class Str.
- Class Properties renamed and moved to Base/BaseProperties to have better structure.
- It is now possible to parse both object and class in Obj methods.
- Path::root() now supports both array and string (dot notation supported).
- Path::packageCurrent() now supports both array and string (dot notation supported).
- Path::package() now supports both array and string (dot notation supported).
- Class Container renamed to Bag.
- Bag->toArray() renamed to Bag->all().
- System/Input::getProtocol() renamed to getScheme() and updated to handle REQUEST_SCHEME.
- System/Input::getRemoteAddress() renamed to getRemoteIp().
- System/Input::getUri() updated to not return port if standard port.

### Removed
- Class Messages removed.
- Class Config removed in favor of package corex/config.
- Class System/Template removed.
