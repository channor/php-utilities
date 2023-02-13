# PHP Utilities
This repo was created to collect useful functions. 

## Install
No package is released.

Put the following in composer.json:
```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/channor/php-utilities.git"
    }
],
"require": {
    "channor/php-utilities": "dev-main"
}
```

## Classes and functions
### Random
* **randIntervalsMax()** - Generate a random float/decimal between min and max value in multiple intervals with a max sum per interval.

## Contribute
Clone repo with `git clone`.

Make a merge request.

### Docker commands
* `docker-compose run composer`
* `docker-compose run php`
* `docker-compose run phpunit`
