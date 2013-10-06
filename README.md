Silex-ORM-Security-Authentication
=================================

Using Silex + Doctrine2 ORM Provider + native SecurityServiceProvider to perform an authentication system

"Sosa" namespace is an acronym for the title above.

Test:

* Login = test@test.com
* Password : test

Installation
------------

```bash
    $ git clone https://github.com/ronanguilloux/Silex-ORM-Security-Authentication.git
    $ curl -sS https://getcomposer.org/installer | php
    $ composer.phar install --dev
    $ make install
```


ORM & Entities
--------------

* (re-)generate entities & accessors:

```bash
./console orm:generate-entities src/
```


Database
--------

* generate schema (= MySQL tables)
* generate fixtures (= MySQL test datas)

```bash
    $  ./console dbal:fixtures:load --purge
```

The `--purge` option (remove & re-)generates schema

For reverse engeneering purpose only, you can use the DatabaseDriver to obtain an array of ClassMetadataInfo instances from the database schema, and generate YAML from them:

```bash
    $ ./console orm:convert-mapping --from-database yml resources/mapping
```


Tests
-----

Tests need --dev option while installing dependecing using composer:

```bash
	$ ln -s vendor/phpunit/phpunit/phpunit.php phpunit
	$ /usr/bin/php phpunit
    # or just:
	$ ./phpunit
``


License
-------

Copyright (c) 2013 Ronan Guilloux
MIT License


Credits
-------

* [Silex][s]: PHP micro-framework based on the Symfony2 Components
* [Silex-Kitchen-Edition][ske]): a bootstrap silex application
* [Bootstrap][b]: Twitter's front-end framework
* [Twig][t]: flexible, fast, and secure template language for PHP

[c]: http://cupcakeipsum.com
[s]: http://silex.sensiolabs.org/documentation
[ske]: https://github.com/lyrixx/Silex-Kitchen-Edition
[d2]: http://www.doctrine-project.org/projects/orm.html
[t]: http://twig.sensiolabs.org/
[b]: http://twitter.github.com/bootstrap
