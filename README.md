# OroCRM Application

This is an example of a fully functional [OroCRM][1] application which can be used as is or customized to meet
your business needs.

## System Requirements

Before starting the installation process, please prepare infrastructure environment based on the [system requirements][2]. 

## Installation

- Clone OroCRM application repository:

```bash
    git clone -b x.y.z https://github.com/orocrm/crm-application.git
```

where x.y.z is the latest [release tag](https://github.com/orocrm/crm-application/releases) or use the latest master:

```bash
    git clone https://github.com/orocrm/crm-application.git
```


- Install [Composer][3] globally following the  official Composer [installation documentation][4]
and install [fxpio/composer-asset-plugin][5] plugin for it:

```bash
    composer global require "fxp/composer-asset-plugin:~1.2"
```

- Install [Node.js][6].

- Install application dependencies running the following command from the application folder:

```bash
    composer install --prefer-dist --no-dev
```

- Create the database with the name specified in the previous step (default name is "oro_crm").

- Install the application and the admin user with the Installation Wizard by opening install.php in the browser or from CLI:

```bash  
php app/console oro:install --env=prod
```

- Configure the Web Socket server process and the Message Queue consumer process in [Supervisor][7]:

```ini

[program:orocrm_web_socket]
command=/path/to/app/console clank:server --env=prod
numprocs=1
autostart=true
startsecs=0
user=www-data
redirect_stderr=true

[program:orocrm_message_consumer]
command=/path/to/app/console oro:message-queue:consume --env=prod
process_name=%(program_name)s_%(process_num)02d
numprocs=5
autostart=true
autorestart=true
startsecs=0
user=www-data
redirect_stderr=true
```

or run them manually:

```bash
php /path/to/app/console clank:server --env=prod
php /path/to/app/console oro:message-queue:consume --env=prod
```

**Note:** the port used by Web Socket must be open in the firewall for outgoing/incoming connections.

- Configure crontab:

```bash
*/1 * * * * /path/to/app/console oro:cron --env=prod
```

or scheduled tasks execution to run the command below every minute:

```bash
php /path/to/app/console oro:cron --env=prod
```
 
**Note:** ``/path/to/app/console`` is a full path to `app/console` script in your application.

##Using Redis for application caching

To use Redis for application caching, follow the corresponding [configuration instructions][9]

[1]:    https://github.com/orocrm/crm
[2]:    https://www.orocrm.com/documentation/index/current/system-requirements
[3]:    https://getcomposer.org/
[4]:    https://getcomposer.org/download/
[5]:    https://github.com/fxpio/composer-asset-plugin/blob/master/Resources/doc/index.md
[6]:    https://nodejs.org/en/download/package-manager/
[7]:    http://supervisord.org/
[8]:    https://github.com/orocrm/redis-config#configuration
