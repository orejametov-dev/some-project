First you need to download docker and docker-compose on your host-machine 
Then you need to install databases that will serve all of our local projects
1. Clone [git@gitlab.alifshop.uz](mailto:git@gitlab.alifshop.uz):alifuz/backend/backend-infra.git

2. Run `make build`


Then you can simply follow these steps to up you project with docker
1. Clone git@gitlab.alifshop.uz:alifuz/backend/service-merchant-laravel.git
2. Run cp env.example .env
3. Run `make first-run` to configure your project

   Works on linux and Mac!

   При первом запуске у вас выйдет окно которое запросит Username и Password от [gitlab.alifshop.uz](http://gitlab.alifshop.uz) !!! Это нужно будет ввести один раз как видно из названия команды

   также если у вас mac или linux у вас запросит пароль от рута


4. Run `make install` to install composer

5. Run `make laravel {name}`

   Позволяет запускать команды `php artisan`

   ex. : `make laravel name="key:generate"`

6. Run `make laravel name="migrate:fresh --seed""`




