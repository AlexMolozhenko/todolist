## Порядок запуска:
1) composer install
2) npm install
## Настройте домен
##Настройте подключение к mysql в файле .env
## Выполните команды для создания БД , миграций и заполнение БД сидерами
1) php artisan db:create
2) php artisan migrate
3) php artisan php artisan db:seed
## Сгенерируйте Swagger документацию

1)php artisan l5-swagger:generate
