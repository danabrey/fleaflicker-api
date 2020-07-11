# Fleaflicker API

This is a PHP library for interacting with the Fleaflicker API.

## Installation

`composer require danabrey/fleaflicker-api`

## Usage

Create an instance of the client

`$client = new DanAbrey\FleaflickerApi\FleaflickerApiClient(2020);`

The league year is required.

Use the client methods to make requests to the API, e.g.:

`$client->players()`

`$client->league('XXXXX')` where XXXXX is the league ID.

`$client->rosters('XXXXX')` where XXXXX is the league ID.

All methods return either a single instance or an array of plain PHP objects that represent the data returned. e.g. `FleaflickerLeaguePlayer`, `FleaflickerLeagueProPlayer`, `FleaflickerLeagueRoster[]`, etc.

### Running tests

`./vendor/bin/phpunit`