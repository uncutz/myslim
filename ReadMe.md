```
NGINX 8082
PMA 8083
DB 3360
```

install image for the first time:

1. create a network, that all our containers will be visible by each other
```bash
$ docker network create labnet
```
2. build image
```bash
$ docker-compose build
```
3. start container
```bash
$ docker-compose up -d
```

to start container
```bash
$ docker-compose up -d
```

to update container 
```bash
$ docker-compose build
```

to close container 
```bash
$ docker-compose down
```