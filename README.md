# Posts Search API

By Alexandre da Costa.

This is a simple Laravel project to demonstrate my skills with it and with PHP.

## Requirements

1. Docker
2. Docker Compose

## Installation

1. Clone the repository
2. Copy or rename `.env.example` to `.env`
3. Make sure to verify that these ports are not in use, otherwise you may change them in the .env file
    1. `APP_PORT=8002`: This is the port that the application will be served on.
    2. `FORWARD_DB_PORT=3332`: This is the port that the database will be served on for external users.
4. From the project root, run `./vendor/bin/sail up -d`
5. After the containers are up, run `./vendor/bin/sail artisan migrate --seed`. This will seed 100 random posts and users to the database.
6. The application should be available at `http://localhost:8002`. If you have changed the port, it will be available at `http://localhost:<APP_PORT>`
7. To run tests, run `./vendor/bin/sail test`

## API Documentation

- [Postman Documentation](https://documenter.getpostman.com/view/19198317/2s93CGRb6y)


