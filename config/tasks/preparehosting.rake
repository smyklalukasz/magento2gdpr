namespace :deploy do
	task :preparehosting do
		if "#{fetch(:stage)}" == "Continuous" || "#{fetch(:stage)}" == "Test"
			on roles(:all) do |host|
				run_locally do
					execute "ssh createhosting@#{host.hostname} 'sudo /usr/local/bin/create-hosting.sh #{fetch(:current_domain)} #{host.user} Magento'", interaction_handler: StreamOutputInteractionHandler.new
				end
			end
		end
	end
end
