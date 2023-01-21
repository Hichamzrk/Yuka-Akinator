install:
	composer install
	php bin/console doctrine:database:drop --if-exists --force
	php bin/console doctrine:database:drop --if-exists --force --env=test
	php bin/console doctrine:database:create
	php bin/console doctrine:database:create --env=test
	php bin/console make:migration
	php bin/console doctrine:schema:update --force
	php bin/console doctrine:schema:update --force --env=test
	# php bin/console doctrine:migrations:migrate --no-interaction
	php bin/console doctrine:fixtures:load --group=dev --no-interaction

start:
	symfony server:start

stop:
	symfony server:stop

loadFixtures:
	php bin/console doctrine:fixtures:load --group=dev --no-interaction

test:
	 php bin/phpunit
