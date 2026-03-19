## Installation Guide
- Run `docker-compose up -d --build`
- Run `docker-compose exec app composer install`
- Run `docker-compose exec app npm install`
- Run `docker-compose exec app php artisan migrate:refresh --seed`
- Run `docker-compose exec app php artisan key:generate`


## URL 
- Web : `http://localhost:8080`
- Mysql : `http://localhost:27018`
