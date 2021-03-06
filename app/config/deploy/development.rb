set :stage_files, ["app/config/parameters.development.yml"]
set :deploy_to, "/path/to/sandbox"
set :domain, "1.2.3.4"
set :user, "user"
role :web, domain
role :app, domain, :primary => true
role :db, domain, :primary => true
set :permission_method, :acl
set :controllers_to_clear, []
before "symfony:assetic:dump", "symfony:reset"