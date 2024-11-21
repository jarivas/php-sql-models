# Description
It is a tool to generate Models from differets kinds automatically, those models can CRUD by them selves,
even supports relationships throught the method join

# How to install
composer require jarivas/sql-models

# How to use
Please check the tests code on tests/Unit

# Tests
* Requires Docker
* ./vendor/bin/phpunit --testsuite Mysql --filter ModelTest
* ./vendor/bin/phpunit --testsuite Pgsql --filter ModelTest
* ./vendor/bin/phpunit --testsuite Sqlite --filter ModelTest
* ./vendor/bin/phpunit --testsuite Mssql --filter ModelTest