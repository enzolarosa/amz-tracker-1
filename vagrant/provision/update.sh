#!/usr/bin/env bash

# update backend
cd /home/vagrant/amz-api
git fetch -p
#git checkout develop
git pull
php -r "file_exists('.env') || copy('/home/vagrant/amz-api/env.example', '.env');"
composer install
/home/vagrant/.nvm/versions/node/v14.15.4/bin/node /home/vagrant/.nvm/versions/node/v14.15.4/bin/npm install
/home/vagrant/.nvm/versions/node/v14.15.4/bin/node /home/vagrant/.nvm/versions/node/v14.15.4/bin/npm run dev
php artisan horizon:pause
php artisan migrate --force
php artisan horizon:terminate
