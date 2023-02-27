## Instalation

1. composer install
2. php artisan migrate
3. php artisan db:seed
4. cp .env.example .env
5. Edit DB_CONNECTION, DB_HOST, DB_PORTm DB_DATABASE, DB_USERNAME, DB_PASSWORD
6. php artisan key:generate
7. php artisan serve

## Endpoints
1. GET: http://127.0.0.1:8000/api/get-token?email=[users.email]&password=qwerty

    Responce:
    {
        "type": "Bearer",
        "token": "l2hfcnQrcxixsNdzRzPuEd2QHSxCIfZ0Y8YSOlll"
    }

    Headers for others requests:
        Authorization: Bearer l2hfcnQrcxixsNdzRzPuEd2QHSxCIfZ0Y8YSOlll
        Accept: application/json
2. GET: http://127.0.0.1:8000/api/users - Show all users
3. GET: http://127.0.0.1:8000/api/posts - Show all posts of current user
4. POST: http://127.0.0.1:8000/api/posts - Show all posts, or posts of given user
    params: user_id
5. POST: http://127.0.0.1:8000/api/comments - Show all comments, or comments of given user / post
    params: user_id, post_id
6. GET: http://127.0.0.1:8000/api/calls/{duration_sec} / Display by months of the current year how many interruptions each user had more than 5 minutes between calls
