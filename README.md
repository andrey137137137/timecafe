# Установка проекта

- исходная база данных timecafe.sql
- в папках `common/config`,`console/config` и `frontend/config` все файлы конфигурации example скопировать в рабочие версии (точно такое же имя только без example)
- запускаем `composer update` 
- запускаем `php yii migrate` 
- настраиваем запуск сайта из папки `frontend/web`. Так же можно воспользоваться командой `php -S 0.0.0.0:8080 -t frontend/web`

# авторизация

Что б авторизироваться на сайте можно использовать

Artur

1234567890


# требования
- PHP7 и выше
- composer