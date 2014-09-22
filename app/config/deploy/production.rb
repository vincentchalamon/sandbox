set :stage_files, ["app/config/parameters.production.yml"]
set :deploy_to, "/path/to/sandbox"
set :domain, "1.2.3.4"
set :user, "user"
role :web, domain
role :app, domain, :primary => true
role :db, domain, :primary => true
set :permission_method, :acl