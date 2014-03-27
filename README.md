# OroCRM Application

Welcome to OroCRM an Open Source Customer Relationship Management (CRM) tool.

## Requirements

OroCRM is Symfony 2 based application with following requirements:

* PHP 5.4.4 and above
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

If you don't have Composer yet, download it following the instructions on http://getcomposer.org/
or just run the following command:

    curl -s https://getcomposer.org/installer | php

Clone https://github.com/orocrm/crm-application.git OroCRM project with:

    git clone http://github.com/orocrm/crm-application.git

Go to crm-application folder and run composer installation:

    php composer.phar install

Create the database (default name is "oro_crm").

Initialize application with Installation Wizard by opening install.php in the browser or from CLI:

    php app/console oro:install

Start web socket server:

    php app/console clank:server

Configure crontab or scheduled tasks execution to run command below every minute:

    php app/console oro:cron

[1]:  http://getcomposer.org/
