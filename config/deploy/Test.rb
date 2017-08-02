server ENV['DEPLOY_TEST_SERVER'], user: ENV['DEPLOY_TEST_USER'], roles: %w{app db web}
if "#{fetch(:branch)}" == "master"
  set :current_domain, "#{fetch(:application)}." + ENV['DEPLOY_TEST_DOMAIN']
else
  set :current_domain, "#{fetch(:application)}-#{fetch(:branch)}." + ENV['DEPLOY_TEST_DOMAIN']
end
set :default_domain, "#{fetch(:application)}." + ENV['DEPLOY_TEST_DOMAIN']
set :default_deploy_to, "/var/www/vhosts/#{fetch(:default_domain)}"
set :deploy_to, "/var/www/vhosts/#{fetch(:current_domain)}"
set :keep_releases, 1
