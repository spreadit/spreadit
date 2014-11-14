#!/bin/sh
cd ..
php artisan down
git pull origin master
php artisan optimize
php composer.phar dump-autoload --optimize
grunt
php artisan up
