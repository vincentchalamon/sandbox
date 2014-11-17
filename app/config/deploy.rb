# Server
set :application, "Sandbox"
set :use_sudo,    false
set :webserver_user, "www-data"
set :use_set_permissions, true
ssh_options[:forward_agent] = true
default_run_options[:pty] = true
ssh_options[:port] = 22

# Multistaging
set :stage_dir, "app/config/deploy"
set :stages do
    names = []
    for filename in Dir["#{stage_dir}/*.rb"]
        names << File.basename(filename, ".rb")
    end
    names
end
set :default_stage, "development"
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
    default_tag = 'master' if default_tag.nil?
    tag = Capistrano::CLI.ui.ask "Version to deploy (#{default_tag}): "
    tag = default_tag if tag.empty?
    tag
end
set :deploy_via,  :remote_cache
set :keep_releases, 3

# Symfony
set :symfony_env_prod, "prod"
set :assets_symlinks, true
set :dump_assetic_assets, true
set :use_composer, true
set :app_path,    "app"
set :web_path,    "web"
set :model_manager, "doctrine"
set :shared_files, ["#{app_path}/config/parameters.yml"]
set :shared_children, ["#{app_path}/logs", "#{app_path}/sessions", "#{app_path}/spool", "bin", "#{web_path}/uploads", "vendor"]
set :interactive_mode, false

namespace :symfony do
    namespace :fos do
        namespace :routing do
            desc "Dump routing"
            task :dump do
                run "cd #{current_release} && php app/console fos:js-routing:dump"
            end
        end
    end

    namespace :assetic do
        namespace :admin do
            desc "Dump assetic for admin env"
            task :dump do
                run "cd #{current_release} && php app/console assets:install --symlink --env=admin admin"
                run "cd #{current_release} && php app/console assetic:dump --no-debug --env=admin admin"
                run "ln -s #{shared_path}/web/uploads #{current_release}/admin/uploads"
            end
        end
    end

    desc "Reset database"
    task :reset do
        run "cd #{current_release} && php app/console doctrine:database:drop --force"
        run "cd #{current_release} && php app/console doctrine:database:create"
        run "cd #{current_release} && php app/console doctrine:schema:update --force"
        run "cd #{current_release} && php app/console doctrine:fixtures:load -n"
    end
end

logger.level = Logger::MAX_LEVEL

# Backup remote database to local
before "symfony:doctrine:migrations:down", "database:dump:remote"
before "symfony:doctrine:migrations:migrate", "database:dump:remote"
before "deploy:rollback:revision", "database:dump:remote"

# Migrate remote database
#before "symfony:cache:warmup", "symfony:doctrine:migrations:migrate"

# Dump routing
before "symfony:assetic:dump", "symfony:fos:routing:dump"
after "symfony:assetic:dump", "symfony:assetic:admin:dump"

# Run deployment
after "deploy", "deploy:cleanup" # Clean old releases at the end
after "deploy:setup", "upload_parameters" # Upload parameters file on setup server

# Install project on first deploy
before "symfony:cache:warmup", "symfony:reset"