rm -rf app/cache/* app/logs/*
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load --no-interaction
rm -rf app/cache/* app/logs/*
php app/console fos:elastica:populate
php app/console assets:install web --symlink