# Posts Search API

By Alexandre da Costa.

This is a simple Laravel REST API project to demonstrate my skills with it and with PHP.
Although there is a welcome page for quickly testing server status, the API is the main focus of this project.
A standalone Vue.js SPA to consume this API is available at https://github.com/alexandre-da-costa/simple-posts-search-spa.

## Requirements

1. Docker
2. Docker Compose

## Installation

1. Clone the repository
2. Copy or rename `.env.example` to `.env`
3. Make sure to verify that these ports are not in use, otherwise you may change them in the .env file
    1. `APP_PORT=8002`: This is the port that the application will be served on.
    2. `FORWARD_DB_PORT=3332`: This is the port that the database will be served on for external users.
4. From the project root, run the following command to install the dependencies:

    ```
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
    ```

5. Run `./vendor/bin/sail up -d` to start the containers.
6. After the containers are up, run `./vendor/bin/sail artisan migrate --seed`. This will seed 100 random posts and
   some random users to the database.
7. The application should be available at `http://localhost:8002`. The default Laravel welcome view has been left here for this test and should render when accessing from the browser. If you have changed the port, it will be
   available at `http://localhost:<APP_PORT>`.
8. The API endpoints are prefixed with `/api/v1`. For example, to get a list of posts, you can make a GET request to
   `http://localhost:8002/api/v1/posts`.
8. To run tests, run `./vendor/bin/sail test`

## API Documentation

- [Postman Documentation](https://documenter.getpostman.com/view/19198317/2s93CGRb6y)


