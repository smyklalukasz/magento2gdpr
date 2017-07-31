namespace :deploy do
	task :clean do
		on roles(:all) do
			execute "cd #{release_path} && DOMAIN=#{fetch(:current_domain)} bin/clean.sh"
		end
	end
end
