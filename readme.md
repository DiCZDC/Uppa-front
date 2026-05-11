# Instrucciones de ejecucion
## Paso 1: Correr fastApi
``` bash
    uvicorn main:app --host 0.0.0.0 --port 8000
```
## Paso 2: Correr laravel
``` bash
php artisan serve --host=172.21.194.97 --port=8001
```

## Paso 3: Correr Caddy
``` bash
sudo ./caddy_linux_amd64 run
```