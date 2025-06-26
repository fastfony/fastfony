.PHONY: fixtures oauth2-server-init vendor node_modules setup_tests start stop tests coverage phpstan phpinsights

fixtures: ## Generate fixtures for dev environment
	$(MAKE) vendor
	$(MAKE) node_modules
	bin/console doctrine:database:drop --force --env=dev
	bin/console doctrine:database:create --env=dev
	bin/console doctrine:schema:create --env=dev
	bin/console doctrine:fixtures:load --no-interaction --env=dev

oauth2-server-init: ## Generate private and public keys for OAuth2 Server
	mkdir -p config/jwt
	openssl genrsa -out config/jwt/private.pem 2048
	openssl rsa -in config/jwt/private.pem -pubout -out config/jwt/public.pem
	php -r 'echo "OAUTH_ENCRYPTION_KEY=".base64_encode(random_bytes(32)), PHP_EOL;' >> .env.local

vendor: ## Install composer dependencies
	composer install
	vendor/bin/grumphp git:init

node_modules: ## Install npm dependencies
	npm install

setup_tests: ## Setup test environment
	bin/console doctrine:database:drop --force --env=test
	bin/console doctrine:database:create --env=test
	bin/console doctrine:schema:create --env=test
	bin/console doctrine:fixtures:load --group=test --no-interaction --env=test
	bin/console cache:clear --env=test
	bin/console cache:warmup --env=test

start: ## Start the development server
	$(MAKE) vendor
	$(MAKE) node_modules
	npm run build
	chmod -R 777 public/uploads/
	bin/console app:install --env=dev
	bin/console doctrine:schema:update --force --env=dev
	bin/console assets:install
	npm run dev
	symfony proxy:start
	symfony server:start -d
	rm -rf var/cache/*

stop: ## Stop Docker containers, Symfony server & kill npm dev-server
	symfony server:stop
	symfony proxy:stop

tests: ## Run tests
	$(MAKE) setup_tests
	symfony run bin/phpunit

coverage: ## Run tests with coverage (requires Xdebug)
	$(MAKE) setup_tests
	XDEBUG_MODE=coverage php bin/phpunit --coverage-html=docs/coverage

phpstan: ## Run PHPStan
	vendor/bin/phpstan analyse src tests

phpinsights: ## Run PHP Insights
	vendor/bin/phpinsights

help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help
