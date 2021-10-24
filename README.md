## Installation process

- composer update
- .env.example edit to .env
- .env file set database connection credantial
- php artisan migrate

## Api Documentation
    - We use swgger for api documentation

    Command

    - composer update
    - php artisan l5-swagger:generate    

## Api Versioning
    - Current Version (v1)

### versioning Folder Structure

    /app
        /controllers
            /Api
                /v1
                    /UserController.php
                /v2
                    /UserController.php

### Author
<a href="https://bd.linkedin.com/in/ahmad-shafik-392a71109">Ahmad shofiq</a>

<p align="center">
<a href="#"><img src="https://scontent.fdac5-2.fna.fbcdn.net/v/t1.6435-9/71516503_1212941158888623_7562451657426993152_n.jpg?_nc_cat=103&ccb=1-5&_nc_sid=174925&_nc_ohc=OkZYcYiFbGEAX9v9C5m&_nc_ht=scontent.fdac5-2.fna&oh=fe7bceba9d26ac75bff76c902ee8aca6&oe=619A8566" alt="Build Status"></a>
</p>
    


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
