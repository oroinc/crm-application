OroCRM Application
========================

Welcome to OroCRM an Open Source Client Relationship Management (CRM) tool.

This document contains information on how to download, install, and start
using OroCRM. For a more detailed explanation, see the [Installation]
chapter.

Important Note: this application is not production ready and is intendant for evaluation and development only!

Requirements
------------

OroCRM requires Symfony 2, Doctrine 2 and PHP 5.3.3 or above.

Installation instructions:
-------------------------


### Using Composer

[As both Symfony 2 and OroCRM use [Composer][2] to manage their dependencies, this is the recommended way to install OroCRM.]

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s https://getcomposer.org/installer | php

- Clone https://github.com/laboro/crm-dev.git OroCRM project with

    git clone http://github.com/laboro/crm-dev.git

- Go to app/config folder and create parameters.yml using parameters.dist.yml as example. Update database name and credentials
- Install OroCRM dependencies with composer. If installation process seems too slow you can use "--prefer-dist" option.

    php composer.phar install

- Initialize application with install script (for Linux and Mac OS install.sh, for Windows install.bat)

After installation you can login as application administrator using user name "admin" and password "admin".

Checking your System Configuration
-------------------------------------

Before starting to code, make sure that your local system is properly
configured for a Symfony application.

Execute the `check.php` script from the command line:

    php app/check.php

Access the `config.php` script from a browser:

    http://your_domain/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.


[1]:  http://symfony.com/doc/2.1/book/installation.html
[2]:  http://getcomposer.org/