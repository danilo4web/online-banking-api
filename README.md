
## Online Banking API 

#### Rodar para configurar setup (containers docker, composer e banco de dados)
```
make setup
```
![Alt text](docs/docker-containers.png)

#### Processar filas (Emails são enfileirados para não causar gargalos e em caso de falhas podem até ser reprocessados)
```
docker exec -it bank-api-php sh -c "php artisan queue:work"
```
![Alt text](docs/queue-jobs.png)

#### Rodar switch de tests:
```
docker exec -it bank-api-php sh -c "php artisan test --colors=always tests/Integration/App/Http/Controllers/API/"
```
![Alt text](docs/integration-tests.png)

##### Modelo do banco de dados:
![Alt text](docs/db-model.png)

##### Migrations:
![Alt text](docs/migrations.png)
