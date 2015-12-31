[![Onboard Rocks - A live demonstration of Onboard Informatics APIs](Onboard/_install/onboard-logo.png)](http://www.onboardinformatics.com)

# Onboard Rocks

## What is Onboard Rocks ?

An open-source PHP application using the Onboard Informatics Property API.  It is based on 150 million US property records.  View
a live demo here http://onboard.rocks/, Sign up for developer access to the API here, [[https://developer.onboard-apis.com/](https://developer.onboard-apis.com/)].

It was built using the [[Mini 2 application](https://github.com/panique/mini2)].
An extremely simple PHP barebone / skeleton application built on top of the wonderful Slim router / micro framework
[[1](http://www.slimframework.com/)] [[2](https://github.com/codeguy/Slim)] [[docs](http://docs.slimframework.com)].

## Features

- Search for properties based on address and radius
- Pagination through result set
- Return sales history for property based on property ID
- Return county tax assessment for property based on property ID
- Return AVM for property based on property ID

By default MINI allows user access to /public folder. The rest of the application (including .git files, swap files,
etc) is not accessible.

## Requirements

- PHP 5.3+
- cURL
- mod_rewrite activated, document root routed to /public (tutorial below)

Maybe useful: Simple tutorials on setting up a LAMP stack on 
[Ubuntu 14.04 LTS](http://www.dev-metal.com/installsetup-basic-lamp-stack-linux-apache-mysql-php-ubuntu-14-04-lts/)
and [12.04 LTS](http://www.dev-metal.com/setup-basic-lamp-stack-linux-apache-mysql-php-ubuntu-12-04/).

## Screenshot

[![US Property Records Search Application](Onboard/_install/onboard-rocks-screenshot.png)](http://onboard.rocks/)

## Installation

##### 1. Activate mod_rewrite and ...

Tutorials for [Ubuntu 14.04 LTS](http://www.dev-metal.com/enable-mod_rewrite-ubuntu-14-04-lts/) and 
[Ubuntu 12.04 LTS](http://www.dev-metal.com/enable-mod_rewrite-ubuntu-12-04-lts/).
 
##### 2. ... route all requests to /public folder of the script
 
Change the VirtualHost file from `DocumentRoot /var/www/html` to `DocumentRoot /var/www/html/public` and from
`<Directory "/var/www/html">` to `<Directory "/var/www/html/public">`. Don't forget to restart. By the way this is also 
mentioned in the official Slim documentation, but 
[hidden quite much](http://docs.slimframework.com/#Route-URL-Rewriting).

##### 3. Get dependencies via Composer
 
Do a `composer install` in the project's root folder to fetch the dependencies (and to create the autoloader).

##### 3. Insert Property API Key

Sign up for developer access to Onboard's Property API here, https://developer.onboard-apis.com/  Once your account is created
log in and go to Account >> Applications.  Create a new application.  Copy the API Key and paste into "THE CONFIGS" section of /public/index.php

#### Scripts used

MINI 2
https://github.com/panique/mini2

Slim
http://www.slimframework.com/

Twig
http://twig.sensiolabs.org/

SASS Compiler
https://github.com/panique/php-sass

CSS / JS Minifier
http://www.mullie.eu/dont-build-your-own-minifier/

## Change log

12/31/2015 - Initial Commit


## Support

imonko@onboardinformatics.com

