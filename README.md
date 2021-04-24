# Movie Night

## Requirements

[Docker](https://www.docker.com/get-started)
- On Windows and Mac choose "Docker Desktop". On Linux just use your distro's package manager and laugh at the GUI using n00bs.

[Docker Compose](https://docs.docker.com/compose/install/)
[Composer](https://getcomposer.org/download/)
[Node.js](https://nodejs.org/en/download/)
[Vue-cli](https://cli.vuejs.org/)

## Setup

This _should_ work but I haven't tested it yet.  Will update if I missed anything.

1. Copy `.env.example` to `.env`
2. Get an API key from the [Open Movie Database](http://www.omdbapi.com/apikey.aspx)
and add it to the `OMDB_KEY` field in `.env`
3. Go into the `frontend` directory and run `../vendor/bin/sail run web npm install`
4. Once that's done, go back up a directory run the DB migrations with `vendor/bin/sail php artisan migrate`
5. Go to `http://localhost:8080` in your browser to run the app.  The api docs can be found at `http://localhost/api/docs`

To regenerate the docs after any changes run `vendor/bin/sail php artisan l5-swagger:generate`
