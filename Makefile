entities:
	    ./console orm:generate-entities src/

fixtures:
	    ./console dbal:fixtures:load --purge

tests:
	    ./phpunit

install: entities fixtures
