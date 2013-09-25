set :stage_files, ["app/config/parameters.dev.yml"]
set :deploy_to, "/var/www/dev"
set :symfony_env_prod, "dev"
set :controllers_to_clear, []

before "symfony:cache:warmup", "symfony:doctrine:schema:update"