set :domain, "62.210.114.206"
set :user, "vincent"
role :web, domain
role :app, domain
role :db, domain, :primary => true
set :permission_method, :acl

set :stage_files, ["app/config/parameters.yml"]
set :deploy_to, "/var/www/dev"
set :symfony_env_prod, "prod"
set :controllers_to_clear, []