set :domain, "1.2.3.4"
set :user, "user"
role :web, domain
role :app, domain
role :db, domain, :primary => true
set :permission_method, :acl

set :stage_files, ["app/config/parameters.yml"]
set :deploy_to, "/var/www/dev"
set :symfony_env_prod, "prod"