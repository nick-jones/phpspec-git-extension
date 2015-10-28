.PHONY: all test

all: test

test:
	./vendor/bin/phpspec run
	./vendor/bin/behat
