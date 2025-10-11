dev development: # Install and build application developemnt dependencies
	@composer install --no-interaction --ignore-platform-reqs
	@npm install --no-audit --no-fund

prod production: # Build application for production
	@composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
	@npm install --no-audit --no-fund --no-save && npm run build && npm prune --production

update upgrade: # Update application dependencies
	@composer update && npm update && npm install && npm audit fix

coverage: # Generate an HTML coverage report
	@phpunit --coverage-html .coverage

clear-build: # Clear the compiled build assets
	@rm --recursive --force --verbose public/build

clear-cache: # Clear the application cache
	@rm --recursive --force --verbose cache/*
