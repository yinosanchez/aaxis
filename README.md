# How to migrate and populate the database
Run the following commands to migrate the database
'''
docker-compose run --rm web php bin/console make:migration
docker-compose run --rm web php bin/console doctrine:migrations:migrate
'''

Additionally you can populate the database with the following command
'''
docker-compose run --rm web php bin/console doctrine:fixtures:load
'''

Remember to add --env=test to migrate and populate the test database
# How to run tests:

First migrate and populate the database aaxis_test running the previously 
'''
docker-compose run --rm web php bin/phpunit
'''