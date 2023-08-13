## Auth Api

Как поднять приложение на своем сервере

- Клонировать проект `git clone`
- Устновить зависимости `composer install`
- Создать `.env` файл и копировать из `.env.example`
- Добавить ключи `Google` и `Yandex`
- Настроить базу данных в `.env` файле
- Генерировать код для приложение `php artisan key:generate`
- Запустить миграцию `php artisan migrate`
- Создать ссылку на папку хранилище `php artisan storage:link`
- Готово, enjoy app :)