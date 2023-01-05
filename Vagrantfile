# -*- mode: ruby -*-
# vi: set ft=ruby :

# Use this Vagrant configuration file for local installation of the Oro application.
# Please, refer to the Oro Applications installation guides for the detailed instructions:
# https://doc.oroinc.com/backend/setup/dev-environment/vagrant/

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
  config.vm.box = "generic/oracle8"

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
  config.vm.network "private_network", ip: "192.168.56.10"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # config.vm.synced_folder "../data", "/home/vagrant/www"
  config.vm.synced_folder "./", "/vagrant"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  #
   config.vm.provider "virtualbox" do |vb|
     # Display the VirtualBox GUI when booting the machine
  #   vb.gui = true

     # Customize the amount of memory on the VM:
     vb.memory = 4096
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
    ORO_PHP_MODULE_VERSION=8.1
    ORO_NODEJS_MODULE_VERSION=18

    # --- Database settings ---

    DB_USER="dbuser"
    DB_PASSWORD="DBP@ssword123"
    DB_NAME="oro"

    echo "$DB_USER" > /tmp/oro_install_DB_USER
    echo "$DB_PASSWORD" > /tmp/oro_install_DB_PASSWORD
    echo "$DB_NAME" > /tmp/oro_install_DB_NAME

    # --- Oro application settings ---

    APP_HOST="localhost"
    APP_USER="admin"
    APP_PASSWORD="adminpass"
    APP_LOAD_DEMO_DATA="y"		# y | n

    echo "$APP_HOST" > /tmp/oro_install_APP_HOST
    echo "$APP_USER" > /tmp/oro_install_APP_USER
    echo "$APP_PASSWORD" > /tmp/oro_install_APP_PASSWORD
    echo "$APP_LOAD_DEMO_DATA" > /tmp/oro_install_APP_LOAD_DEMO_DATA

    echo "\n*******************************************************"
    echo "************** Step 1: Environment Setup **************"
    echo "*******************************************************\n"

    dnf -y install dnf-plugin-config-manager https://dl.fedoraproject.org/pub/epel/epel-release-latest-8.noarch.rpm https://rpms.remirepo.net/enterprise/remi-release-8.rpm
    dnf -y module enable mysql:8.0 nodejs:${ORO_NODEJS_MODULE_VERSION} php:remi-${ORO_PHP_MODULE_VERSION}
    dnf -y upgrade

    echo "\n~~~~~~~~~~~~~~ Enable Required Package Repositories ~~~~~~~~~~~~~~\n"

    if [[ ! -e /etc/yum.repos.d/oropublic.repo ]]; then
	    TMP_FILE=$(mktemp)
	    cat >"$TMP_FILE" <<__EOF__
[oropublic]
name=OroPublic
baseurl=https://nexus.oro.cloud/repository/oropublic/8/x86_64/
enabled=1
gpgcheck=0
module_hotfixes=1
__EOF__
    	mv -f "$TMP_FILE" "/etc/yum.repos.d/oropublic.repo"
    fi

    echo "\n~~~~~~~~~~~~~~ Install Nginx, Install PHP, NodeJS, Wget ~~~~~~~~~~~~~~\n"
    dnf -y --setopt=install_weak_deps=False --best install pngquant jpegoptim findutils rsync glibc-langpack-en psmisc wget bzip2 unzip p7zip p7zip-plugins parallel \
    patch nodejs npm git-core jq bc mysql-server \
    php-common php-cli php-fpm php-opcache php-mbstring php-mysqlnd php-pgsql php-pdo php-json php-process php-ldap php-gd php-intl php-bcmath php-xml php-soap php-sodium php-tidy php-imap php-pecl-zip php-pecl-mongodb
    dnf -y --setopt=install_weak_deps=False --best --nogpgcheck install oro-nginx oro-nginx-mod-http-cache_purge oro-nginx-mod-http-cookie_flag oro-nginx-mod-http-geoip oro-nginx-mod-http-gridfs oro-nginx-mod-http-headers_more oro-nginx-mod-http-naxsi oro-nginx-mod-http-njs oro-nginx-mod-http-pagespeed oro-nginx-mod-http-sorted_querystring oro-nginx-mod-http-testcookie_access oro-nginx-mod-http-xslt-filter

    echo "\n~~~~~~~~~~~~~~ Install Composer ~~~~~~~~~~~~~~\n"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/bin/composer

    echo "********************************************************************************"
    echo "************** Step 2: Pre-installation Environment Configuration **************"
    echo "********************************************************************************"

    echo "\n~~~~~~~~~~~~~~ Perform Security Configuration ~~~~~~~~~~~~~~\n"

    sed -i 's/SELINUX=enforcing/SELINUX=permissive/g' /etc/selinux/config
    setenforce permissive
    systemctl disable --now firewalld

    echo "\n~~~~~~~~~~~~~~ Prepare MySQL Database ~~~~~~~~~~~~~~\n"

    # --- Change the MySQL Server Configuration ---

    echo "[client]" >> /etc/my.cnf
    echo "default-character-set = utf8mb4" >> /etc/my.cnf
    echo "" >> /etc/my.cnf
    echo "[mysql]" >> /etc/my.cnf
    echo "default-character-set = utf8mb4" >> /etc/my.cnf
    echo "" >> /etc/my.cnf
    echo "[mysqld]" >> /etc/my.cnf
    echo "default-authentication-plugin=mysql_native_password" >> /etc/my.cnf
    echo "innodb_file_per_table = 0" >> /etc/my.cnf
    echo "wait_timeout = 28800" >> /etc/my.cnf
    echo "character-set-server = utf8mb4" >> /etc/my.cnf
    echo "collation-server = utf8mb4_unicode_ci" >> /etc/my.cnf

    systemctl restart mysqld

    # --- Change the Default MySQL Password for Root User ---

    mysqladmin --user=root password "$DB_PASSWORD"

    # --- Create a Database for OroPlatform Community Edition Application and a Dedicated Database User ---

    mysql -uroot -p$DB_PASSWORD -e "CREATE DATABASE $DB_NAME"
    mysql -uroot -p$DB_PASSWORD -e "CREATE USER '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD'"
    mysql -uroot -p$DB_PASSWORD -e "GRANT ALL PRIVILEGES ON $DB_NAME.* to '$DB_USER'@'localhost' WITH GRANT OPTION"
    mysql -uroot -p$DB_PASSWORD -e "FLUSH PRIVILEGES"

    echo "\n~~~~~~~~~~~~~~ Configure Web Server ~~~~~~~~~~~~~~\n"

    cat > /etc/nginx/conf.d/default.conf <<____NGINXCONFIGTEMPLATE
server {
  server_name $APP_HOST www.$APP_HOST;
  root  /var/www/oroapp/public;

  index index.php;

  gzip on;
  gzip_proxied any;
  gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;
  gzip_vary on;

  location / {
    # try to serve file directly, fallback to index.php
    try_files \\$uri /index.php\\$is_args\\$args;
  }

  location ~ ^/(index|index_dev|config|install)\\.php(/|$) {
    fastcgi_pass php-fpm;
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

    echo "\n~~~~~~~~~~~~~~ Enable Installed Services ~~~~~~~~~~~~~~\n"

    systemctl enable mysqld php-fpm nginx
    systemctl restart php-fpm nginx

    echo "********************************************************************************************"
    echo "************************** Step 3: Oro Application Installation ****************************"
    echo "********************************************************************************************"
    echo "\n~~~~~~~~~~~~~~ Get Application Source Code ~~~~~~~~~~~~~~\n"

    # --- Copy application source code from the current host folder to the nginx web folder ---

    mkdir -p /var/www/oroapp
    cd /var/www/oroapp
    cp -r /vagrant/* .
    chown -R vagrant:vagrant .

    echo "\n~~~~~~~~~~~~~~ Install Application Dependencies ~~~~~~~~~~~~~~\n"

    # --- Install Dependencies ---

    su - vagrant -c 'composer install --no-progress --prefer-dist --no-dev --optimize-autoloader -n --working-dir=/var/www/oroapp'

    # --- Configure config/parameters.yml (to prevent composer interactive dialog) ---

    sed -i "/database_user/s/:[[:space:]].*$/: $DB_USER/g" ./config/parameters.yml
    sed -i "/database_password/s/:[[:space:]].*$/: '$DB_PASSWORD'/g" ./config/parameters.yml
    sed -i "/database_name/s/:[[:space:]].*$/: $DB_NAME/g" ./config/parameters.yml
    chown vagrant:vagrant /var/www/oroapp/config/parameters.yml

    echo "\n~~~~~~~~~~~~~~ Install Oro Application ~~~~~~~~~~~~~~\n"

    su - vagrant -c 'APP_HOST=$(cat /tmp/oro_install_APP_HOST) && APP_USER=$(cat /tmp/oro_install_APP_USER) && APP_PASSWORD=$(cat /tmp/oro_install_APP_PASSWORD) && APP_LOAD_DEMO_DATA=$(cat /tmp/oro_install_APP_LOAD_DEMO_DATA) && php /var/www/oroapp/bin/console oro:install --env=prod --timeout=1500 --no-debug --application-url="http://$APP_HOST/" --organization-name="Oro Inc" --user-name="$APP_USER" --user-email="admin@example.com" --user-firstname="Bob" --user-lastname="Dylan" --user-password="$APP_PASSWORD" --sample-data=$APP_LOAD_DEMO_DATA --language=en --formatting-code=en_US'

    echo "\n~~~~~~~~~~~~~~ Add Required Permissions for the nginx User ~~~~~~~~~~~~~~\n"

    setfacl -b -R ./
    cd /var/www/oroapp
    find . -type f -exec chmod 0644 {} \\;
    find . -type d -exec chmod 0755 {} \\;
    chown -R nginx:nginx ./var/{sessions,data,cache,logs}
    chown -R nginx:nginx ./public/{media,js}

    echo "\n*********************************************************************************"
    echo "************** Step 4: Post-installation Environment Configuration **************"
    echo "*********************************************************************************\n"

    echo "\n~~~~~~~~~~~~~~ Schedule Periodical Command Execution ~~~~~~~~~~~~~~\n"

    echo "*/1 * * * * php /var/www/oroapp/bin/console oro:cron --env=prod > /dev/null" > /var/spool/cron/nginx

    echo "\n~~~~~~~~~~~~~~ Configure and Run Required Background Processes ~~~~~~~~~~~~~~\n"

    cat >> /etc/systemd/system/oro-message-queue.service <<__EOF__
[Unit]
Description=ORO consumer for /var/www/oroapp
After="mysqld"

[Service]
Type=simple
User=nginx
WorkingDirectory=/var/www/oroapp
ExecStart=/usr/bin/php /var/www/oroapp/bin/console oro:message-queue:consume --env=prod --memory-limit=500
Restart=always
RestartSec=3s
TimeoutStopSec=3s
KillMode=control-group
PrivateTmp=true

[Install]
WantedBy=multi-user.target
__EOF__

    cat >> /etc/systemd/system/oro-clank-server.service <<__EOF__
[Unit]
Description=ORO clank server for /var/www/oroapp
After="mysqld"

[Service]
Type=simple
User=nginx
WorkingDirectory=/var/www/oroapp
ExecStart=/usr/bin/php /var/www/oroapp/bin/console gos:websocket:server --env=prod
Restart=always
RestartSec=3s
KillMode=control-group
PrivateTmp=true

[Install]
WantedBy=multi-user.target
__EOF__

    systemctl enable --now oro-message-queue.service oro-clank-server.service

    echo "\n**********************************************************************************************************************"
    echo "************** Congratulations! You’ve Successfully Installed OroCommerce Application **********************************"
    echo "***********************************************************************************************************************\n"
    echo "\n************** You should now be able to open the homepage http://$APP_HOST:$FORWARDED_PORT/ and use the application. ************\n"
   SHELL
end

