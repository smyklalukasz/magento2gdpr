namespace :deploy do
	task :install do
		on roles(:all) do
			execute "cd #{release_path} && BRANCH=#{fetch(:branch)} DEPLOY_ENVIRONMENT=#{fetch(:stage)} SHARED_DIR=#{shared_path} bin/install.sh && bin/run.sh", interaction_handler: StreamOutputInteractionHandler.new
		end
	end
end
