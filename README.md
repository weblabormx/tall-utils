# Weblabor's TALL Utilities

A package of traits, wrappers and helpers to solve common PHP/TALL workflow day-to-day struggles.

## Installation

You can install this package using Composer:

```
composer require weblabormx/tall-utils
```

## Usage

List of classes available:

### Enums

`\WeblaborMx\TallUtils\Enums\WithSelectInput`:
- Adds a `keyValue()` static function to format your Enum cases in `[$value => label()]`.
- Adds an `options()` static function to output a `<options>` `HtmlString` directly to your blade.

### Models

`WeblaborMx\TallUtils\Models\WithActivityLog`:
- Opinionated default options for [Activity Log](https://github.com/spatie/laravel-activitylog).

`WeblaborMx\TallUtils\Models\WithFilters`:
- Adds a `filterBy()` scope with a custom filtering system based on array options.
- [Check the example](./examples/LivewireComponent.php.example)

### Livewire
`WeblaborMx\TallUtils\Livewire\WithConstants`:
- Adds a `$constants` array, to pass the name of variables that shouldn't be changed after the first request.

`WeblaborMx\TallUtils\Livewire\WithFilters`:
- Optional livewire trait to pair with `WeblaborMx\TallUtils\Models\WithFilters`.

### WireUI

- Adds `notify()` and `dialog()` functions inspired by [Laravel Flash](https://github.com/spatie/laravel-flash) to use the WireUI components between requests.