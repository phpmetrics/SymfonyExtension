REPLACE=`semver tag`

# Build phar
build: test
	@echo Copying sources
	@mkdir -p /tmp/phpmetrics-build
	@cp * -R /tmp/phpmetrics-build

	@echo Building phar
	@cd /tmp/phpmetrics-build && php build.php
	@cp /tmp/phpmetrics-build/symfony-extension.phar symfony-extension.phar
	@rm -Rf /tmp/phpmetrics-build

# Run unit tests
test:


# Publish new release. Usage:
#   make tag VERSION=(major|minor|patch)
# You need to install https://github.com/flazz/semver/ before
tag:
	@semver inc $(VERSION)
	@echo "New release: `semver tag`"


# Tag git with last release
release: build
	git add .semver symfony-extension.phar
	git commit -m "releasing `semver tag`"
	git tag `semver tag`
	git push -u origin master
	git push origin `semver tag`
