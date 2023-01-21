install:
	composer install
	php bin/console doctrine:database:create
	php bin/console doctrine:database:create --env=test
	php bin/console make:migration
	php bin/console doctrine:migrations:migrate
	php bin/console doctrine:migrations:migrate -n --env=test
	php bin/console doctrine:shema:update --force --env=test
	php bin/console doctrine:fixtures:load --group=dev --no-interaction

start:
	symfony server:start

loadFixtures:
	php bin/console doctrine:fixtures:load --group=dev --no-interaction

test:
	 php bin/phpunit
