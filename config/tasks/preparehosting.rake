namespace :deploy do
	task :preparehosting do
		on roles(:all) do
			if "#{fetch(:branch)}" != "master"
				execute "sudo /usr/local/bin/branch-hosting.sh #{fetch(:current_domain)}", interaction_handler: StreamOutputInteractionHandler.new
			end
		end
	end
end
