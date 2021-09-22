#!/bin/bash
function addApiKey() {
    echo "Enter your OMDb key.  You can get one from http://www.omdbapi.com/apikey.aspx."
    read OMDB_KEY

    if [ "$OMDB_KEY" != "" ]; then
        echo "===== Adding Key to .env ====="
        echo "OMDB_KEY=$OMDB_KEY" >> .env
    else
        addApiKey
    fi
}

echo "Create .env file? [y/n]"
read CREATE_FILE

if [ "$CREATE_FILE" == "y" ]; then
    cp .env.example .env
    echo "File created!"
fi

echo "Add movie search API key? [y/n]"
read ADD_KEY

if [ "$ADD_KEY" == "y" ]; then
    addApiKey
else
    echo "Ok, just don't forget to add it to your .env file!"
fi

echo "===== Installing PHP dependencies ====="
composer install

echo "===== Building application ====="
vendor/bin/sail up --build -d
vendor/bin/sail run web npm install
vendor/bin/sail php artisan migrate

echo "===== Generating API documentation ====="
vendor/bin/sail php artisan l5-swagger:generate


echo "===== Restarting Container ====="
vendor/bin/sail restart
vendor/bin/sail logs -f
