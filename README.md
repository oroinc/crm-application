# OroCRM Application

Welcome to OroCRM an Open Source Customer Relationship Management (CRM) tool.

## Requirements

OroCRM is Symfony 2 based application with following requirements:

* PHP 5.4.4 and above
* PHP 5.4.4 and above command line interface
* PHP Extensions
    * GD
    * Mcrypt
    * JSON
    * ctype
    * Tokenizer
    * SimpleXML
    * PCRE
    * ICU
* MySQL 5.1 and above

## Installation instructions

OroCRM uses [Composer][1] to manage package dependencies, this is the a recommended way to install OroCRM.

- If you don't have Composer yet, download it following the instructions on http://getcomposer.org/
or just run the following command:

```bash
curl -s https://getcomposer.org/installer | php
```

- Clone https://github.com/orocrm/crm-application.git OroCRM project with:

```bash
git clone http://github.com/orocrm/crm-application.git
```


- Make sure that you have installed Java

- Go to app/config folder and create parameters.yml using parameters.yml.dist as example. Update database name and credentials.
  Alternatively parameters.yml can be created automatically on the next step when run composer install command,
  you will be able to customize all the values interactively.
- Install OroCRM dependencies with composer. If installation process seems too slow you can use "--prefer-dist" option.
  Go to crm-application folder and run composer installation:

```bash
php composer.phar install --prefer-dist
```

- Create the database (default name is "oro_crm").

- Initialize application with Installation Wizard by opening install.php in the browser or from CLI:

```bash  
app/console oro:install
```

After installation you can login as application administrator using user name "admin" and password "admin".

## Installation notes

Installed PHP Accelerators must be compatible with Symfony and Doctrine (support DOCBLOCKs)

Using MySQL 5.6 with HDD is potentially risky because of performance issues

Recommended configuration for this case:

    innodb_file_per_table = 0

And ensure that timeout has default value

    wait_timeout = 28800

See [Optimizing InnoDB Disk I/O][3] for more

Instant messaging between the browser and the web server
--------------------------------------------------------
To use this feature you need to configure parameters.yml websocket parameters and run server with console command

```bash
app/console clank:server --env prod
```

## Reporting

To use this feature you need to run report data collector with console command

```bash
app/console oro:report:update --env prod
```

## Demo Data uploading

To upload this feature you need to run console command

```bash
php app/console doctrine:fixture:load --verbose --append --no-interaction --env=prod --fixtures=vendor/oro/crm/src/OroCRM/Bundle/DemoDataBundle/DataFixtures
```
## Instant messaging between the browser and the web server

To use this feature you need to configure parameters.yml websocket parameters and run server with console command

 ```bash
app/console clank:server
```
Configure crontab or scheduled tasks execution to run command below every minute:

 ```bash
    php app/console oro:cron
 ```

[1]:  http://symfony.com/doc/2.3/book/installation.html
[2]:  http://getcomposer.org/
[3]:  http://dev.mysql.com/doc/refman/5.6/en/optimizing-innodb-diskio.html
