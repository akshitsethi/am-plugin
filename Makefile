VERSION := 1.0.0
PLUGINSLUG := am-plugin
PLUGINPATH := $(shell pwd)

install:
	composer install
	composer dump-autoload -o

clover.xml: install test

update_version:
	sed -i "" "s/@##VERSION##@/${VERSION}/" $(PLUGINSLUG).php
	sed -i "" "s/@##VERSION##@/${VERSION}/" php/Config.php
	sed -i "" "s/@##VERSION##@/${VERSION}/" i18n/$(PLUGINSLUG).pot

remove_version:
	sed -i "" "s/${VERSION}/@##VERSION##@/" $(PLUGINSLUG).php
	sed -i "" "s/${VERSION}/@##VERSION##@/" php/Config.php
	sed -i "" "s/${VERSION}/@##VERSION##@/" i18n/$(PLUGINSLUG).pot

test:
	bin/phpunit --coverage-html=./reports

build: install update_version
	mkdir -p build
	rm -rf vendor
	composer install --no-dev
	composer dump-autoload -o
	make copy
	zip -r $(PLUGINSLUG).zip $(PLUGINSLUG)
	rm -rf $(PLUGINSLUG)
	mv $(PLUGINSLUG).zip build/
	make remove_version

copy:
	mkdir $(PLUGINSLUG)
	cp -r assets php i18n vendor $(PLUGINSLUG)/
	cp $(PLUGINSLUG).php uninstall.php license.txt $(PLUGINSLUG)/

dist: install update_version
	mkdir -p dist
	rm -rf vendor
	composer install --no-dev
	composer dump-autoload -o
	cp -r assets php i18n vendor dist/
	cp $(PLUGINSLUG).php uninstall.php license.txt dist/
	make remove_version

release:
	git stash
	git fetch -p
	git checkout main
	git pull -r
	git tag v$(VERSION)
	git push origin v$(VERSION)
	git pull -r

fmt:
	bin/phpcbf --standard=WordPress . --ignore=assets,bin,i18n,views,vendor

lint:
	bin/phpcs --standard=WordPress . --ignore=assets,bin,i18n,vendor

psr:
	composer dump-autoload -o

pot:
	wp i18n make-pot . i18n/$(PLUGINSLUG).pot --slug=$(PLUGINSLUG) --skip-js --exclude=vendor

cover:
	bin/coverage-check clover.xml 100

clean:
	rm -rf vendor/
