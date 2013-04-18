Oro CRM
========================

Welcome to the Oro CRM.

This document contains information on how to download, install, and start
using CRM. For a more detailed explanation, see the [Installation]
chapter.

1) Installation
----------------------------------

When it comes to installing the Oro CRM, you have the
following options.

### Use Composer (*recommended*)

As Symfony and Oro CRM uses [Composer][2] to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s https://getcomposer.org/installer | php

- Clone https://github.com/laboro/crm Oro CRM project with

    git clone https://github.com/laboro/crm.git

- Go to app/config folder and create parameters.yml using parameters.dist.yml as example.
- Install Oro CRM and dependencies with composer

    php composer.phar install

*Note! This method not workable before oro/crm published on packagist.org*
Then, use the `create-project` command to generate a new Oro CRM application:

    php composer.phar create-project oro/crm path/to/install 1.0.x-dev

For an exact version, replace 1.0.x-dev with the latest  version.

Composer will install Symfony, Oro CRM and all their dependencies under the
`path/to/install` directory.

2) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Symfony application.

Execute the `check.php` script from the command line:

    php app/check.php

Access the `config.php` script from a browser:

    http://localhost/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.

What's inside?
---------------
TODO: Update this chapter

Enjoy!

[1]:  http://symfony.com/doc/2.1/book/installation.html
[2]:  http://getcomposer.org/
