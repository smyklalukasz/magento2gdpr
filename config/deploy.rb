set :application, "magento2gdpr"
set :branch, ENV['BRANCH'] || 'master'
set :exclude_dir, [".svn", ".git", ".gitignore", "Capfile", "README.md", "readme.txt", "README.txt", "Changelog.txt", "CHANGELOG.txt", "CHANGELOG.md", "vendor", "web"]
set :include_dir, []
set :linked_dirs, ["web/pub/media", "web/pub/static", "web/var"]
set :log_level, :info
set :repo_url, "git@github.com:AdFabConnect/magento2gdpr.git"
set :scm, :copy
set :stages, %w(Continuous Test Production)
set :use_sudo, false

namespace :deploy do
  before :starting, :preparehosting
  before :publishing, :install
  before :finishing, :clean
end
