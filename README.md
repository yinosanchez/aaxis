# How to build the project
First, create the environment files. You can use the ones provided in the code, but you can use yours.

```
mv .env.dist .env
mv .env.test.dist .env.test
```

Then, wen need to create the private and public keys to encode the jwt token:

```
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096

openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

Update the environment files with the private key, public key and the passphrase
```
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=
JWT_PUBLIC_KEY=
JWT_PASSPHRASE=
```

## How to migrate and populate the database
Run the following commands to migrate the database

```
docker-compose run --rm web php bin/console make:migration

docker-compose run --rm web php bin/console doctrine:migrations:migrate
```

Additionally you can populate the database with the following command

```
docker-compose run --rm web php bin/console doctrine:fixtures:load
```

Remember to add --env=test to migrate and populate the test database

Finally build the containers and start them
```
docker-compose up -d --build
```

That's it, start using the api as you like
# How to run tests:

First migrate and populate the database aaxis_test running the previously shown command

```
docker-compose run --rm web php bin/phpunit
```