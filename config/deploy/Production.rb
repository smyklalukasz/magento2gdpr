set :stage, :Production
server ENV['DEPLOY_PRODUCTION_SERVER'], user: ENV['DEPLOY_PRODUCTION_USER'], roles: %w{app db web}
set :current_domain, ENV['DEPLOY_PRODUCTION_DOMAIN']
set :default_domain, ENV['DEPLOY_PRODUCTION_DOMAIN']
set :default_deploy_to, "/var/www/vhosts/#{fetch(:default_domain)}"
set :deploy_to, "/var/www/vhosts/#{fetch(:current_domain)}"