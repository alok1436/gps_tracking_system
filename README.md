## About Laravel GPS Tracking system
 
Laravel gps tracker is refers to connect with the one location to other locations using live tracking on google map

### Install & setup

composer install

## .env file setup

cp .env.example .env

Change the database name, user and password

## Run migrations

php artisan migrate

## Clear application cache

php artisan optimize

## Start the server

php artisan serve

## other .env changes

PUSHER_APP_ID=\
PUSHER_APP_KEY=\
PUSHER_APP_SECRET=\
PUSHER_HOST=\
PUSHER_PORT=443\
PUSHER_SCHEME="https"\
PUSHER_APP_CLUSTER="ap2"

GOOGLE_MAP_API_KEY=

BROADCAST_CONNECTION=pusher\
QUEUE_CONNECTION=sync

##keep the queue with notification on

php artisan queue:work --queue=notifications

## Contributing

Thank you for considering contributing to the Laravel gps tracking system