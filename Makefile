install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR2 bin src tests