# OroCRM Application

Welcome to OroCRM the Open Source Customer Relationship Management (CRM) application.

## Requirements

OroCRM is a Symfony 2 based application with the following requirements:

* PHP 5.5.9 or above with command line interface
* PHP Extensions:
    * GD
    * Mcrypt
    * JSON
    * ctype
    * Tokenizer
    * SimpleXML
    * PCRE
    * ICU
* MySQL 5.1 or above
* PostgreSQL 9.1 or above

## Installation instructions

OroCRM uses [Composer][1] to manage package dependencies, this is the a recommended way to install OroCRM.

- If you don't have Composer yet, download it and follow the instructions on http://getcomposer.org/
or just run the following command:

```bash
curl -s https://getcomposer.org/installer | php
```

- Clone https://github.com/orocrm/crm-application.git OroCRM project with:

```bash
git clone https://github.com/orocrm/crm-application.git
```


- Make sure that you have [NodeJS][4] installed

- Install OroCRM dependencies with composer. If installation process seems too slow you can use "--prefer-dist" option.
  Go to crm-application folder and run composer installation:

```bash
php composer.phar install --prefer-dist --no-dev
```

- Create the database with the name specified on previous step (default name is "oro_crm").

- Install application and admin user with Installation Wizard by opening install.php in the browser or from CLI:

```bash  
php app/console oro:install --env prod
```
**Note:** If the installation process times out, add the `--timeout=0` argument to the command.

- Enable WebSockets messaging

```bash
php app/console clank:server --env prod
```

- Configure crontab or scheduled tasks execution to run command below every minute:

```bash
php app/console oro:cron --env prod
```
 
**Note:** ``app/console`` is a path from project root folder. Please make sure you are using full path for crontab configuration or if you running console command from other location.

## Installation notes

Installed PHP Accelerators must be compatible with Symfony and Doctrine (support DOCBLOCKs).

Note that the port used in Websocket must be open in firewall for outgoing/incoming connections

Using MySQL 5.6 on HDD is potentially risky because of performance issues.

Recommended configuration for this case:

    innodb_file_per_table = 0

And ensure that timeout has default value

    wait_timeout = 28800

See [Optimizing InnoDB Disk I/O][3] for more

The default MySQL character set utf8 uses a maximum of three bytes per character and contains only BMP characters. The [utf8mb4][6] character set uses a maximum of four bytes per character and supports supplemental characters (e.g. emojis). It is [recommended][7] to use utf8mb4 character set in your app/config.yml:

```
...
doctrine:
    dbal:
        connections:
            default:
                driver:       "%database_driver%"
                host:         "%database_host%"
                port:         "%database_port%"
                dbname:       "%database_name%"
                user:         "%database_user%"
                password:     "%database_password%"
                charset:      utf8mb4
                default_table_options:
                    charset: utf8mb4
                    collate: utf8mb4_unicode_ci
                    row_format: dynamic
...
```

Using utf8mb4 might have side effects. MySQL indexes have a default limit of 767 bytes, so any indexed fields with varchar(255) will fail when inserted, because utf8mb4 can have 4 bytes per character (255 * 4 = 1020 bytes), thus the longest data can be 191 (191 * 4 = 764 < 767). To be able to use any 4 byte charset all indexed varchars should be at most varchar(191). To overcome the index size issue the server can be configured to have large index size by enabling [sysvar_innodb_large_prefix][8]. However, innodb_large_prefix requires some additional settings to work:

- innodb_default_row_format=DYNAMIC (you may also enable it per connection as in the config above)
- innodb_file_format=Barracuda
- innodb_file_per_table=1 (see above performance issues with this setting)

More details about this issue can be read [here][9]

## PostgreSQL installation notes

You need to load `uuid-ossp` extension for proper doctrine's `guid` type handling.
Log into database and run sql query:

```
CREATE EXTENSION "uuid-ossp";
```

## Loading Demo Data using command line

To load sample data you need to run console command

```bash
php app/console oro:migration:data:load --fixtures-type=demo --env=prod
```

## Web Server Configuration

OroCRM application is based on the Symfony standard application so web server configuration recommendations are the [same][5].

[1]:  http://symfony.com/doc/2.3/book/installation.html
[2]:  http://getcomposer.org/
[3]:  http://dev.mysql.com/doc/refman/5.6/en/optimizing-innodb-diskio.html
[4]:  https://github.com/joyent/node/wiki/Installing-Node.js-via-package-manager
[5]:  http://symfony.com/doc/2.3/cookbook/configuration/web_server_configuration.html
[6]:  https://dev.mysql.com/doc/refman/5.6/en/charset-unicode-utf8mb4.html
[7]:  http://symfony.com/doc/current/doctrine.html#configuring-the-database
[8]:  http://dev.mysql.com/doc/refman/5.6/en/innodb-parameters.html#sysvar_innodb_large_prefix
[9]:  https://mathiasbynens.be/notes/mysql-utf8mb4#utf8-to-utf8mb4
