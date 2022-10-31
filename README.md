## Instalation

1. php composer install
2. php artisan migrate
3. php db:seed
4. cp .env.example .env
5. Edit DB_CONNECTION, DB_HOST, DB_PORTm DB_DATABASE, DB_USERNAME, DB_PASSWORD
6. php artisan key:generate
7. php artisan serve

## Endpoints
1. GET: /api/get-token?email=[users.email]&password=qwerty

    Responce:
    {
        "type": "Bearer",
        "token": "l2hfcnQrcxixsNdzRzPuEd2QHSxCIfZ0Y8YSOlll"
    }
    
    This token should be use in all other requests as Authorization header
2. GET: /api/users - Show all users
3. GET: /api/posts - Show all posts of current user
4. POST: /api/posts - Show all posts, or posts of given user
    params: user_id
5. POST: /api/comments - Show all comments, or comments of given user / post
    params: user_id, post_id
6. GET: /api/calls/{duration_sec} / Display by months of the current year how many interruptions each user had more than 5 minutes between calls
