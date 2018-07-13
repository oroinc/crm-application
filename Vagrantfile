# -*- mode: ruby -*-
# vi: set ft=ruby :

# Use this Vagrant configuration file for local installation of the Oro application.
# Please, refer to the Oro Applications installation guides for the detailed instructions:
# https://oroinc.com/orocrm/doc/current/install-upgrade/one-step-automated-installation/vagrant

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://vagrantcloud.com/search.
  config.vm.box = "centos/7"

  # Disable automatic box update checking. If you disable this, then
  # boxes will only be checked for updates when the user runs
  # `vagrant box outdated`. This is not recommended.
  # config.vm.box_check_update = false

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # NOTE: This will enable public access to the opened port
  config.vm.network "forwarded_port", guest: 80, host: 8000

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine and only allow access
  # via 127.0.0.1 to disable public access
  # config.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network "private_network", ip: "192.168.33.10"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # config.vm.synced_folder "../data", "/vagrant_data"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  #
   config.vm.provider "virtualbox" do |vb|
     # Display the VirtualBox GUI when booting the machine
  #   vb.gui = true
  
     # Customize the amount of memory on the VM:
     vb.memory = 2048
     vb.cpus = 2
   end
  #
  # View the documentation for the provider you are using for more
  # information on available options.

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.
   config.vm.provision "shell", inline: <<-SHELL

		echo "\n*****************************************************"
		echo "************* Provision process started *************"
		echo "*****************************************************\n"

		# --------------------- Provision configuration ---------------------
		
		# --- VM settings ---
		
		FORWARDED_PORT=8000
		
		# --- Database settings ---

		DB_USER="dbuser"
		DB_PASSWORD="DBP@ssword123"
		DB_NAME="oro"

		# --- Oro application settings ---

		APP_HOST="localhost"
		APP_USER="admin"
		APP_PASSWORD="adminpass"
		APP_LOAD_DEMO_DATA="y"		# y | n

		echo "\n*******************************************************"
		echo "************** Step 1: Environment Setup **************"
		echo "*******************************************************\n"

		echo "\n~~~~~~~~~~~~~~ Enable Required Package Repositories ~~~~~~~~~~~~~~\n"
		
		yum install -y epel-release
		yum update -y

		echo "\n~~~~~~~~~~~~~~ Install Nginx, NodeJS, Git, Supervisor, and Wget ~~~~~~~~~~~~~~\n"
		
		yum install -y nginx wget git nodejs supervisor yum-utils
		
		echo "\n~~~~~~~~~~~~~~ Install MySQL ~~~~~~~~~~~~~~\n"
		
		wget https://dev.mysql.com/get/mysql80-community-release-el7-1.noarch.rpm && rpm -ivh mysql80-community-release-el7-1.noarch.rpm
		yum-config-manager --disable mysql80-community
		yum-config-manager --enable mysql57-community
		
		yum install -y mysql-community-server
		
		echo "\n~~~~~~~~~~~~~~ Install PHP ~~~~~~~~~~~~~~\n"
		
		wget http://rpms.remirepo.net/enterprise/remi-release-7.rpm && rpm -Uvh remi-release-7.rpm
		yum-config-manager --enable remi-php71
		yum update -y

		yum install -y php-fpm php-cli php-pdo php-mysqlnd php-xml php-soap php-gd php-mbstring php-zip php-intl php-mcrypt php-opcache
		
		echo "\n~~~~~~~~~~~~~~ Install Composer ~~~~~~~~~~~~~~\n"
		
		php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php
		php -r "unlink('composer-setup.php');"
		mv composer.phar /usr/bin/composer
		
		echo "\n~~~~~~~~~~~~~~ Enable Installed Services ~~~~~~~~~~~~~~\n"

		systemctl start mysqld php-fpm nginx supervisord
		systemctl enable mysqld php-fpm nginx supervisord

		echo "********************************************************************************"
		echo "************** Step 2: Pre-installation Environment Configuration **************"
		echo "********************************************************************************"

		echo "\n~~~~~~~~~~~~~~ Perform Security Configuration ~~~~~~~~~~~~~~\n"

		sed -i 's/SELINUX=enforcing/SELINUX=permissive/g' /etc/selinux/config
		setenforce permissive

		echo "\n~~~~~~~~~~~~~~ Prepare MySQL Database ~~~~~~~~~~~~~~\n"

        # --- Change the MySQL Server Configuration ---

        echo "[client]" >> /etc/my.cnf
        echo "default-character-set = utf8mb4" >> /etc/my.cnf
        echo "" >> /etc/my.cnf
        echo "[mysql]" >> /etc/my.cnf
        echo "default-character-set = utf8mb4" >> /etc/my.cnf
        echo "" >> /etc/my.cnf
        echo "[mysqld]" >> /etc/my.cnf
        echo "innodb_file_per_table = 0" >> /etc/my.cnf
        echo "wait_timeout = 28800" >> /etc/my.cnf
        echo "character-set-client-handshake = FALSE" >> /etc/my.cnf
        echo "character-set-server = utf8mb4" >> /etc/my.cnf
        echo "collation-server = utf8mb4_unicode_ci" >> /etc/my.cnf

        systemctl restart mysqld

		# --- Change the Default MySQL Password for Root User ---

		MYSQL_INSTALLED_TMP_ROOT_PASSWORD=$(grep 'temporary password' /var/log/mysqld.log | awk '{print $NF}')
		mysqladmin --user=root --password=$MYSQL_INSTALLED_TMP_ROOT_PASSWORD password $DB_PASSWORD

		# --- Create a Database for OroPlatform Community Edition Application and a Dedicated Database User ---
		
		mysql -uroot -p$DB_PASSWORD -e "CREATE DATABASE $DB_NAME"
	  	mysql -uroot -p$DB_PASSWORD -e "GRANT ALL PRIVILEGES ON $DB_NAME.* to '$DB_USER'@'localhost' identified by '$DB_PASSWORD'"
	  	mysql -uroot -p$DB_PASSWORD -e "FLUSH PRIVILEGES"

		echo "\n~~~~~~~~~~~~~~ Configure Web Server ~~~~~~~~~~~~~~\n"

		cat > /etc/nginx/conf.d/default.conf <<____NGINXCONFIGTEMPLATE
server {
	server_name $APP_HOST www.$APP_HOST;
	root  /usr/share/nginx/html/oroapp/web;

	index app.php;

	gzip on;
	gzip_proxied any;
	gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;
	gzip_vary on;

	location / {
		# try to serve file directly, fallback to app.php
		try_files \\$uri /app.php\\$is_args\\$args;
	}

	location ~ ^/(app|app_dev|config|install)\\.php(/|$) {
		fastcgi_pass 127.0.0.1:9000;
		# or
		# fastcgi_pass unix:/var/run/php/php7-fpm.sock;
		fastcgi_split_path_info ^(.+\\.php)(/.*)$;
		include fastcgi_params;
		fastcgi_param SCRIPT_FILENAME \\$document_root\\$fastcgi_script_name;
		fastcgi_param HTTPS off;
		fastcgi_buffers 64 64k;
		fastcgi_buffer_size 128k;
	}

	location ~* ^[^(\\.php)]+\\.(jpg|jpeg|gif|png|ico|css|pdf|ppt|txt|bmp|rtf|js)$ {
		access_log off;
		expires 1h;
		add_header Cache-Control public;
	}

	error_log /var/log/nginx/${APP_HOST}_error.log;
	access_log /var/log/nginx/${APP_HOST}_access.log;
}
____NGINXCONFIGTEMPLATE
		
		systemctl restart nginx

		echo "\n~~~~~~~~~~~~~~ Configure PHP ~~~~~~~~~~~~~~\n"

		sed -i 's/user = apache/user = nginx/g' /etc/php-fpm.d/www.conf
		sed -i 's/group = apache/group = nginx/g' /etc/php-fpm.d/www.conf
		sed -i 's/;catch_workers_output/catch_workers_output/g' /etc/php-fpm.d/www.conf

		sed -i 's/memory_limit = [0-9MG]*/memory_limit = 1G/g' /etc/php.ini
		sed -i 's/;realpath_cache_size = [0-9MGk]*/realpath_cache_size = 4M/g' /etc/php.ini
		sed -i 's/;realpath_cache_ttl = [0-9]*/realpath_cache_ttl = 600/g' /etc/php.ini

		sed -i 's/opcache.enable=[0-1]/opcache.enable=1/g' /etc/php.d/10-opcache.ini
		sed -i 's/;opcache.enable_cli=[0-1]/opcache.enable_cli=0/g' /etc/php.d/10-opcache.ini
		sed -i 's/opcache.memory_consumption=[0-9]*/opcache.memory_consumption=512/g' /etc/php.d/10-opcache.ini
		sed -i 's/opcache.interned_strings_buffer=[0-9]*/opcache.interned_strings_buffer=32/g' /etc/php.d/10-opcache.ini
		sed -i 's/opcache.max_accelerated_files=[0-9]*/opcache.max_accelerated_files=32531/g' /etc/php.d/10-opcache.ini
		sed -i 's/;opcache.save_comments=[0-1]/opcache.save_comments=1/g' /etc/php.d/10-opcache.ini
		
		systemctl restart php-fpm

		echo "********************************************************************************************"
		echo "************** Step 3: OroPlatform Community Edition Application Installation **************"
		echo "********************************************************************************************"

		echo "\n~~~~~~~~~~~~~~ Get Application Source Code ~~~~~~~~~~~~~~\n"

		# --- Copy application source code from the current host folder to the nginx web folder ---

		cd /usr/share/nginx/html
		mkdir oroapp && cd oroapp
		cp -r /vagrant/* .

		echo "\n~~~~~~~~~~~~~~ Install Application Dependencies ~~~~~~~~~~~~~~\n"

		# --- Configure app/config/parameters.yml (to prevent composer interactive dialog) ---
		
		cp ./app/config/parameters.yml.dist ./app/config/parameters.yml
	 	sed -i "s/database_user:[ ]*root/database_user: $DB_USER/g" ./app/config/parameters.yml
	 	sed -i "s/database_password:[ ]*~/database_password: '$DB_PASSWORD'/g" ./app/config/parameters.yml
	 	sed -i "s/database_name:[ ]*[a-zA-Z0-9_]*/database_name: $DB_NAME/g" ./app/config/parameters.yml
		
		composer install --prefer-dist --no-dev

		echo "\n~~~~~~~~~~~~~~ Install OroPlatform Community Edition Application ~~~~~~~~~~~~~~\n"

		# --- Configure DBAL parameters before installation ---

		cat >> ./app/config/config.yml <<____DOCTRINECONFIG

doctrine:
    dbal:
        charset: utf8mb4
        default_table_options:
            charset: utf8mb4
            collate: utf8mb4_unicode_ci

____DOCTRINECONFIG

		# --- Run the installation command ---

		php ./app/console oro:install --env=prod --timeout=900 --no-debug --application-url="http://$APP_HOST/" --organization-name="Oro Inc" --user-name="$APP_USER" --user-email="admin@example.com" --user-firstname="Bob" --user-lastname="Dylan" --user-password="$APP_PASSWORD" --sample-data=$APP_LOAD_DEMO_DATA

		echo "\n~~~~~~~~~~~~~~ Add Required Permissions for the nginx User ~~~~~~~~~~~~~~\n"

		setfacl -b -R ./
		cd /usr/share/nginx/html/oroapp
		find . -type f -exec chmod 0644 {} \\;
		find . -type d -exec chmod 0755 {} \\;
		chown -R nginx:nginx ./app/{attachment,cache,import_export,logs}
		chown -R nginx:nginx ./web/{media,uploads,js}

		echo "\n*********************************************************************************"
		echo "************** Step 4: Post-installation Environment Configuration **************"
		echo "*********************************************************************************\n"

		echo "\n~~~~~~~~~~~~~~ Schedule Periodical Command Execution ~~~~~~~~~~~~~~\n"

		echo "*/1 * * * * php /usr/share/nginx/html/oroapp/app/console oro:cron --env=prod > /dev/null" > /var/spool/cron/nginx

		echo "\n~~~~~~~~~~~~~~ Configure and Run Required Background Processes ~~~~~~~~~~~~~~\n"

		cat >> /etc/supervisord.conf <<____SUPERVISORDTEMPLATE
[program:oro_web_socket]
command=php ./app/console clank:server --env=prod
numprocs=1
autostart=true
autorestart=true
directory=/usr/share/nginx/html/oroapp
user=nginx
redirect_stderr=true

[program:oro_message_consumer]
command=php ./app/console oro:message-queue:consume --env=prod
process_name=%(program_name)s_%(process_num)02d
numprocs=5
autostart=true
autorestart=true
directory=/usr/share/nginx/html/oroapp
user=nginx
redirect_stderr=true
____SUPERVISORDTEMPLATE
		
		systemctl restart supervisord

		echo "\n**********************************************************************************************************************"
		echo "************** Congratulations! You’ve Successfully Installed OroCRM Application ***************************************"
		echo "**********************************************************************************************************************\n"
		echo "\n************** You should now be able to open the homepage http://$APP_HOST:$FORWARDED_PORT/ and use the application. **************\n"
   SHELL
end

