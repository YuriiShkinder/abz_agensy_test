#!/bin/bash

nameFolder="${PWD##*/}"

if [ ! -f ./.env ]; then
    cp "./.env.example" "./.env"
fi

echo "run docker"
docker-compose -p $nameFolder up --build -d &&

docker run --rm --interactive --tty \
    --volume $PWD:/app \
    composer install


echo "storage chmod 777"
docker exec ${nameFolder}_web_1 chmod 777 -R storage/ &&

echo "php artisan key:generate"
docker exec ${nameFolder}_web_1 php artisan key:generate &&

echo "create database abz if mot exists"
docker exec -i ${nameFolder}_db_1 mysql -u abz --password=abz -e "CREATE DATABASE IF NOT EXISTS abz" &&

echo "load dump  to database abz"
docker exec -i ${nameFolder}_db_1 mysql -u abz --password=abz abz  < dump.sql

echo "go to link: http://127.0.0.1:8080"