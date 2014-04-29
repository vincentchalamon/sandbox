# Server
set :application, "Sandbox"
set :use_sudo,    false
set :webserver_user, "www-data"
set :use_set_permissions, true
ssh_options[:forward_agent] = true
default_run_options[:pty] = true
ssh_options[:port] = 22

# Multistaging
set :stages,      %w(production)
set :default_stage, "production"
set :stage_dir,   "app/config/deploy"
require 'capistrano/ext/multistage'
set :stage_files, false

# Upload parameters file (on deploy:setup call)
task :upload_parameters do
  if stage_files
    for origin_file in stage_files
      if File.exists?(origin_file)
        relative_path = File.dirname(origin_file) + "/" + File.basename(origin_file).gsub(/\.[^\.]+$/, '').gsub(/\.[^\.]+$/, '') + File.extname(origin_file)
        if shared_files && shared_files.include?(relative_path)
          destination_file = shared_path + "/" + relative_path
        else
          destination_file = latest_release + "/" + relative_path
        end
        try_sudo "mkdir -p #{File.dirname(destination_file)}"
        top.upload(origin_file, destination_file)
      end
    end
  end
end

# Repository
set :repository,  "git@github.com:vincentchalamon/sandbox.git"
set :scm,         :git
set :branch do
    default_tag = `git tag`.split("\n").last
    tag = Capistrano::CLI.ui.ask "Version to deploy (#{default_tag}): "
    tag = default_tag if tag.empty?
    tag
end
set :deploy_via,  :remote_cache
set :keep_releases, 3

# Symfony
set :symfony_env_prod, "prod"
set :assets_symlinks, true
set :use_composer, true
set :app_path,    "app"
set :web_path,    "web"
set :model_manager, "doctrine"
set :shared_files, ["#{app_path}/config/parameters.yml"]
set :shared_children, ["#{app_path}/logs", "#{app_path}/sessions", "#{web_path}/uploads", "vendor"]
set :interactive_mode, false

﻿namespace :symfony do
    namespace :doctrine do
        namespace :migrations do
            desc "Migrate down database"
            task :down do
                default_version = `ls app/DoctrineMigrations`.split("\n")[-2].gsub("Version", "").gsub(".php", "")
                version = Capistrano::CLI.ui.ask "Target version (#{default_version}): "
                version = default_version if version.empty?
                run "cd #{current_release} && php app/console doctrine:migrations:migrate #{version}"
            end
        end
    end
end

logger.level = Logger::MAX_LEVEL

# Backup remote database to local
#before "symfony:doctrine:migrations:down", "database:dump:remote"
#before "symfony:doctrine:migrations:migrate", "database:dump:remote"
#before "deploy:rollback:revision", "database:dump:remote"

# Migrate remote database
#before "symfony:cache:warmup", "symfony:doctrine:migrations:migrate"

# Run deployment
after "deploy", "deploy:cleanup" # Clean old releases at the end
after "deploy:setup", "upload_parameters" # Upload parameters file on setup server