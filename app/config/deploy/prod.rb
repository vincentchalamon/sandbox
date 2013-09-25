set :stage_files, ["app/config/parameters.prod.yml"]
set :deploy_to, "/var/www/prod"
set :symfony_env_prod, "prod"
set :controllers_to_clear, []