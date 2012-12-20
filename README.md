# Modular Gaming

A modular persistent browser based game framework using [Kohana 3.3](https://github.com/kohana/core) with [ORM](https://github.com/kohana/orm) and [KOstache](https://github.com/zombor/KOstache).

### Kohana

Kohana is an elegant, open source, and object oriented HMVC framework built using PHP5, by a team of volunteers.
It aims to be swift, secure, and small.

### KOstache

Kostache is a Kohana 3 module for using Mustache templates in your application.

### License

Released under a BSD license, Modular Gaming can be used legally for any open source,
 commercial, or personal project.

### Requirements

* PHP 5.3+
* MySQL 5.1+

## Installation

### Step 1: Download

Download Modular Gaming from Github.

	$ git clone git@github.com:hinton/mg.git
	$ git submodule update --init
	$ cd modules/kostache
	$ git submodule update --init

### Step 2: Configuration of Database

Edit `application/config/database.php` with the correct information.

### Step 3: Import SQL

Import modulargaming.sql using your tool of choice (MySQL client, PHPMyAdmin etc)

### Step 4: Configuration of modulargaming

Open `application/bootstrap.php` and make the following changes: 

* Set the default directory for your application

* Set the default cookie salt

* Set the default [timezone](http://php.net/timezones) for your application

Open `application/config/auth.php` and make the following changes:

* Set the default hash key

Open `.htaccess` and make the following changes:

* Set the correct RewriteBase

### Step 5: Permissions

	$ chmod 0777 application/{cache,logs}

## Testing

We use BDD, with 2 different tools, phpspec and behat.

We use composer to install them, http://getcomposer.org/ or you could install them manually using pear.

	$ curl -s https://getcomposer.org/installer | php
	$ ./composer.phar install --dev
