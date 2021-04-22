# Movie Night

## Setup
This _should_ work but I haven't tested it yet.  Will update if I missed anything.

1. Copy `.env.example` to `.env`
2. Get an API key from the [Open Movie Database](http://www.omdbapi.com/apikey.aspx)
and add it to the `OMDB_KEY` field in `.env`
3. Go into the `frontend` directory and run `../vendor/bin/sail run web npm install`
4. Once that's done, go back up a directory run the DB migrations with `vendor/bin/sail php artisan migrate`
5. Go to `http://localhost:8080` in your browser to run the app.  The api docs can be found at `http://localhost/api/docs`
