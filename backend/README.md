# Event Reminder App - Backend

### Command to run project locally

1. run `composer install` to install dependencies
2. run `docker compose up` to run containers
3. run `docker exec -it event-reminder-app-laravel.test-1 bash` to enter container and run command below

### Command need to run first time

1. copy `.env.example` to `.env` and do adjustment if needed
2. run `php artisan key:generate` to generate or rotate application key
3. run `php artisan migrate` to migrate database
4. run `php artisan passport:keys --force` to generate oauth2 keys and run `sudo chmod -R 777 /var/www/html/storage` if got permission problem
5. run `php artisan passport:client --password --provider users` to support oauth2 client on `User` and fill `PASSPORT_USER_CLIENT_ID` and `PASSPORT_USER_CLIENT_SECRET`
6. run `php artisan db:seed` to seed data
