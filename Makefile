build:
	docker build . -t php_slim_cors_image -f Dockerfile

composer-update: build
	docker run -w /app -v $(shell pwd):/app php_slim_cors_image composer update
	sudo chmod 777 -R vendor

composer-install: build
	docker run -w /app -v $(shell pwd):/app php_slim_cors_image composer install
	sudo chmod 777 -R vendor
