deploy:
	docker-compose up -d
	docker exec back composer install
	docker exec back php artisan migrate:fresh
	docker exec back php artisan 'optimize:clear'
