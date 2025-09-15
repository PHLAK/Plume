dev development: # Install and build application developemnt dependencies
	@composer install --no-interaction --ignore-platform-reqs
	@npm install --no-audit --no-fund

prod production: # Build application for production
	@composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
	@npm install --no-audit --no-fund --no-save && npm run build && npm prune --production

update upgrade: # Update application dependencies
	@composer update && npm update && npm install && npm audit fix

rebuild: # Rebuild and pull new images and recreate containers
	@docker compose build --pull
	@docker compose pull --ignore-buildable
	@docker compose up -d --force-recreate
