#!/bin/bash

echo "Create .env file? [y/n]"
read CREATE_FILE

if [ "$CREATE_FILE" == "y" ]; then
    cp .env.example .env
    echo "File created!"
fi

echo "Enter your OMDb key.  You can get one from http://www.omdbapi.com/apikey.aspx."
read OMDB_KEY

if [ "$OMDB_KEY" != "" ]; then
    echo "===== Adding Key to .env ====="
    echo "OMDB_KEY=$OMDB_KEY" >> .env
fi

echo "===== Installing PHP dependencies ====="
composer install

echo "===== Building application ====="
vendor/bin/sail up --build -d
vendor/bin/sail run web npm install frontend/
vendor/bin/sail php artisan migrate

echo "===== Generating API documentation ====="
vendor/bin/sail php artisan l5-swagger:generate
