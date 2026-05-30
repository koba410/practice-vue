.PHONY: up down build restart logs shell artisan composer npm migrate seed fresh demo

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose up -d --build

restart:
	docker compose restart

logs:
	docker compose logs -f

shell:
	docker compose exec app bash

artisan:
	docker compose exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

composer:
	docker compose exec app composer $(filter-out $@,$(MAKECMDGOALS))

npm:
	docker compose exec node npm $(filter-out $@,$(MAKECMDGOALS))

migrate:
	docker compose exec app php artisan migrate

seed:
	docker compose exec app php artisan db:seed

fresh:
	docker compose exec app php artisan migrate:fresh --seed

demo:
	@echo "Laravel:     http://localhost:8080"
	@echo "Demo page:   http://localhost:8080/demo"
	@echo "phpMyAdmin:  http://localhost:8081"
	@echo "Vite HMR:    http://localhost:5173"

%:
	@:
