VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.define :hg do |config|
    config.vm.box = "precise64"
    config.vm.box_url = "http://files.vagrantup.com/precise64.box"

    # Uncomment the next line if you like to watch.
    # config.vm.boot_mode = :gui

    config.vm.network "forwarded_port", guest: 80, host: 8000, auto_correct: true
    
    # Here's a folder for passing stuff back and forth
    config.vm.synced_folder "./www", "/var/www/html"

    config.vm.provision :chef_solo do |chef|
        chef.json = {
          :mysql => {
            :server_root_password   => "six pasta obsessed wreck",
            :server_repl_password   => "six pasta obsessed wreck",
            :server_debian_password => "six pasta obsessed wreck"
          }
        }
        chef.cookbooks_path = "cookbooks"
        chef.add_recipe "apt"
        chef.add_recipe "ohai"
        chef.add_recipe "configure"
    end
    end
end