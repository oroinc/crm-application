OroCRM Application
========================

Welcome to OroCRM an Open Source Client Relationship Management (CRM) tool.

This document contains information on how to download, install, and start
using OroCRM. For a more detailed explanation, see the [Installation]
chapter.

Important Note: this application is not production ready and is intended for evaluation and development only!

Requirements
------------

OroCRM requires Symfony 2, Doctrine 2 and PHP 5.3.8 or above.

Installation instructions:
-------------------------


### Using Composer

[As both Symfony 2 and OroCRM use [Composer][2] to manage their dependencies, this is the recommended way to install OroCRM.]

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s https://getcomposer.org/installer | php

- Clone https://github.com/orocrm/crm-application.git OroCRM project with

    git clone http://github.com/orocrm/crm-application.git

- Make sure that you have installed Java

- Go to app/config folder and create parameters.yml using parameters.yml.dist as example. Update database name and credentials.
  Alternatively parameters.yml can be created automatically on the next step when run composer install command,
  you will be able to customize all the values interactively.
- Install OroCRM dependencies with composer. If installation process seems too slow you can use "--prefer-dist" option.

    php composer.phar install

- Create the database (default name is "oro_crm")

- Open the OroCRM URL and initialize application with Install Wizard
  Alternatively with script (for Linux and Mac OS install.sh, for Windows install.bat)
  After installation you can login as application administrator using user name "admin" and password "admin".

Instant messaging between the browser and the web server
--------------------------------------------------------
To use this feature you need to configure parameters.yml websocket parameters and run server with console command

 ```bash
app/console clank:server --env prod

Reporting
---------
To use this feature you need to run report data collector with console command

 ```bash
app/console oro:report:update --env prod

Demo Data uploading
---------
To upload this feature you need to run console command

 ```bash
php app/console doctrine:fixture:load --verbose --append --no-interaction --env=prod --fixtures=vendor/oro/crm/src/OroCRM/Bundle/DemoDataBundle/DataFixtures

Checking your System Configuration
-------------------------------------

Before starting to code, make sure that your local system is properly
configured for a Symfony application.

Execute the `check.php` script from the command line:

    php app/check.php

Access the `config.php` script from a browser:

    http://your_domain/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.


[1]:  http://symfony.com/doc/2.3/book/installation.html
[2]:  http://getcomposer.org/
