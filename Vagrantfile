# -*- mode: ruby -*-
# vi: set ft=ruby :

# Use this Vagrant configuration file for local installation of the Oro application.
# Please, refer to the Oro Applications installation guides for the detailed instructions:
# https://doc.oroinc.com/backend/setup/dev-environment/vagrant/

Vagrant.require_version ">= 2.3"
oro_baseline_version = ( ENV['ORO_BASELINE_VERSION'] || '5.1-latest').to_s
memory = ( ENV['MEMORY'] || 8192 ).to_i
cpus = ( ENV['CPUS'] || 4 ).to_i

licence = (ENV['licence']).to_s
gittoken = (ENV['gittoken']).to_s

if ARGV[0] == "up" || ARGV[0] == "provision"
    if !licence || licence.empty?
        puts "You can provide your Enterprise Licence Key to be able to install Oro Enterprise Edition application before 'vagrant up' command (licence=YourEnterpsiseLicenceKey gittoken=yourgithubtoken vagrant up --provision)"
    end
    if !gittoken || gittoken.empty?
        puts "You can provide your GitHub Token to be able to install Oro Enterprise Edition application before 'vagrant up' command (licence=YourEnterpsiseLicenceKey gittoken=yourgithubtoken vagrant up --provision)"
    end
end

$scriptInstallBaseSystem = <<-'SCRIPT'
    set -xe
    sed -i 's/SELINUX=enforcing/SELINUX=permissive/g' /etc/selinux/config
    setenforce permissive
    systemctl disable --now firewalld

    dnf config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
    dnf config-manager --save --setopt="docker-ce-*.priority=1"
    dnf -y upgrade
    dnf -y install git-core bzip2 unzip bc psmisc wget jq docker-ce docker-compose-plugin docker-buildx-plugin
    usermod -aG docker vagrant
    systemctl enable --now "docker.service"
SCRIPT

$scriptBuild = <<-'SCRIPT'
    set -x
    export COMPOSER_PROCESS_TIMEOUT=600
    if [[ "X$GITHUB_TOKEN" != 'X' ]]; then
        COMPOSER_AUTH="{\"github-oauth\": {\"github.com\": \"$GITHUB_TOKEN\"}}"
        export COMPOSER_AUTH
    fi
    # Prepare repositories
    rm -rf /srv/docker-build ||:
    git clone https://github.com/oroinc/docker-build /srv/docker-build
    rm -rf /srv/vagrant ||:
    cp -rf /vagrant /srv/ ||:
    if [[ "X$LICENCE_KEY" != 'X' ]]; then
        echo "ORO_ENTERPRISE_LICENCE=$LICENCE_KEY" >> "/srv/docker-build/docker-compose/.env"
    fi
    echo 'ORO_WEBSOCKET_FRONTEND_DSN=//*:8000/ws' >> "/srv/docker-build/docker-compose/.env"
    export ORO_PROJECT=''
    set -o allexport
    source "/vagrant/.env-build"
    set +o allexport
    export ORO_IMAGE_TAG=local
    set -e
    /srv/docker-build/scripts/composer.sh -b ${ORO_BASELINE_VERSION} -s /srv/vagrant -- '--no-dev install'
    docker buildx build --pull --load --rm --build-arg ORO_BASELINE_VERSION -t ${ORO_IMAGE,,}:$ORO_IMAGE_TAG -f "/srv/docker-build/docker/Dockerfile" /srv/vagrant
    docker image prune -f
    rm -rf /srv/vagrant ||:
SCRIPT

$scriptInstall = <<-'SCRIPT'
    set -x
    export ORO_PROJECT=''
    set -o allexport
    source "/vagrant/.env-build"
    set +o allexport
    export ORO_IMAGE_TAG=local
    set -e
    docker compose --project-directory "/srv/docker-build/docker-compose" -f "/srv/docker-build/docker-compose/compose-orocommerce-application.yaml" down -v
    docker compose --project-directory "/srv/docker-build/docker-compose" -f "/srv/docker-build/docker-compose/compose-orocommerce-application.yaml" pull --ignore-pull-failures --quiet --include-deps install
    docker compose --project-directory "/srv/docker-build/docker-compose" -f "/srv/docker-build/docker-compose/compose-orocommerce-application.yaml" up --pull never --exit-code-from install --attach install install
SCRIPT

$scriptDeploy = <<-'SCRIPT'
    export ORO_PROJECT=''
    set -o allexport
    source "/vagrant/.env-build"
    set +o allexport
    export ORO_IMAGE_TAG=local
    set -xe
    docker compose --project-directory "/srv/docker-build/docker-compose" -f "/srv/docker-build/docker-compose/compose-orocommerce-application.yaml" up  --attach application application
    set +x
    echo "***********************************************************************************************************************"
    echo "************* Congratulations! You’ve Successfully Installed ORO Application ******************************************"
    echo "***********************************************************************************************************************"
    echo "************* You should now be able to open the homepage http://localhost.dev.oroinc.com:8000 and use the application."

SCRIPT


Vagrant.configure(2) do |config|
    config.vagrant.plugins = ["vagrant-env"]
    config.env.enable # Enable vagrant-env(.env)
    config.vm.hostname = "orodev"
    # config.vm.synced_folder "./", "/vagrant"
    config.vm.network "forwarded_port", guest: 80, host: 8000
    config.vm.provider :virtualbox do |vb, override|
        override.vm.box_url = "https://oracle.github.io/vagrant-projects/boxes/oraclelinux/8-btrfs.json"
        override.vm.box = "oraclelinux/8-btrfs"
        vb.gui = false
        vb.memory = memory
        vb.cpus = cpus
    end

    config.vm.provision "InstallBaseSystem", type: "shell" do |s|
        s.inline= $scriptInstallBaseSystem
        s.env = {LICENCE_KEY:licence, GITHUB_TOKEN:gittoken, ORO_BASELINE_VERSION:oro_baseline_version}
        s.sensitive = true
    end
    config.vm.provision "Build", type: "shell" do |s|
        s.inline = $scriptBuild
        s.env = {LICENCE_KEY:licence, GITHUB_TOKEN:gittoken, ORO_BASELINE_VERSION:oro_baseline_version}
        s.sensitive = true
    end
    config.vm.provision "Install", type: "shell", inline: $scriptInstall
    config.vm.provision "Deploy", type: "shell", inline: $scriptDeploy
end

