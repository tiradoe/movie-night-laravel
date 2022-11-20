# Movie Night

## Requirements

[Docker](https://www.docker.com/get-started)

- On Windows and Mac choose "Docker Desktop".
- On Linux just use your distro's
package manager and laugh at the GUI using n00bs.

[Docker Compose](https://docs.docker.com/compose/install/)

[Composer](https://getcomposer.org/download/)

- The setup script assumes you have Composer installed globally.
- Update the command in `firstRun.sh` if that's not the case.

[Node.js](https://nodejs.org/en/download/)

An API key from the [Open Movie Database](http://www.omdbapi.com/apikey.aspx)

## Setup

Run the `firstRun.sh` bash script and follow the prompts.

This will probably take a while but when it's done, go to [http://localhost:8080](http://localhost:8080)
to see the site. API docs can be found at [http://localhost:80/api/docs](http://localhost:80/api/docs)

Once everything loads, generate a new Laravel app key or all backend requests will fail:
`vendor/bin/sail artisan key:generate`

Then migrate the database:
`vendor/bin/sail artisan migrate`

This project uses Laravel Sail to control the containers.  Commands can be found
[here](https://laravel.com/docs/8.x/sail)

## Testing

Run tests with `vendor/bin/sail artisan test`

Generate test coverage with
`vendor/bin/sail run laravel.test vendor/bin/phpunit --coverage-html tests/coverage`

Once that finishes, open `tests/coverage/index.html` in your browser to view
the current test coverage.
