log:
	    mkdir -p resources/log
	    touch development.log

entities:
	    ./console orm:generate-entities src/

fixtures:
	    ./console dbal:fixtures:load --purge

tests:
	    ./phpunit

install: log entities fixtures
