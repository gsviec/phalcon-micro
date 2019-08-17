## Project information

Exmaple build micro service on phalcon php

## Wercker check code

[![wercker status](https://app.wercker.com/status/42b1ea647417f1e02844d7363ea34d23/m/ "wercker status")](https://app.wercker.com/project/byKey/42b1ea647417f1e02844d7363ea34d23)

### Setup environment to development

First you need install docker and docker composer, after that just running the command below:

```
docker-compose up -d 
```

Then waiting a moment to download on image, but frm api need 
library php so that you also need running command below

``` 
docker-compose exec php bash
cd /app/ && composer install
cp env.example env
phalcon migratetion
```
Then go to url http://localhost:9090 to import database, to get database
file go to directory [database](./databases), when you finish just open again http://localhost


### Fix and check code PSR2

```
vendor/bin/phpcs app --standard=PSR2 --ignore=app/migrations
vendor/bin/phpcbf app --standard=PSR2 --ignore=app/migrations
```
