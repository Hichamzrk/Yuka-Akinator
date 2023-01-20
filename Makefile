install:
	composer install
	php bin/console doctrine:database:create
	php bin/console make:migration
	php bin/console doctrine:migrations:migrate
	php bin/console doctrine:fixtures:load

start:
	symfony server:start