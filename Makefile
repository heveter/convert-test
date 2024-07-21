build-no-cache:
	docker compose build --no-cache

run:
	docker compose up -d

generate-swagger:
	docker exec -it convert-test-php php artisan l5-swagger:generate --env=tesing

test-process:
	docker exec -it convert-test-php php artisan migrate --env=tesing
	docker exec -it convert-test-php php artisan passport:keys --force --env=tesing
	docker exec -it convert-test-php php artisan passport:client --personal --name="convertTest" --env=tesing
	docker exec -it convert-test-php php artisan test --env=tesing

down:
	docker compose down


