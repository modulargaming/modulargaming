# [Modular Gaming](http://www.modulargaming.com)

A modular [persistent browser based game](http://www.pbbg.org) framework using [Kohana 3.3](https://github.com/kohana/core) with [ORM](https://github.com/kohana/orm) and [KOstache](https://github.com/zombor/KOstache).

### Kohana

[Kohana](http://kohanaframework.org) is an elegant, open source, and object oriented [HMVC](http://en.wikipedia.org/wiki/Hierarchical_model%E2%80%93view%E2%80%93controller) framework built using [PHP5](http://www.php.net), by a team of volunteers.
It aims to be swift, secure, and small.

### ORM

ORM is a a powerful Object Relational Mapping (ORM) module that uses the active record pattern and database introspection to determine a model's column information. 

### Mustache

[Mustache](https://github.com/mustache) is a template specification we use for our templates, we use [Kostache](https://github.com/zombor/KOstache)'s implementation.

### License

Released under a [BSD license](http://www.modulargaming.com/license), Modular Gaming can be used legally for any open source,
 commercial, or personal project.

### Requirements

* PHP 5.3.3+
* MySQL

## Installation

### Step 1: Download

Download Modular Gaming from Github.

	$ git clone git@github.com:hinton/mg.git --recursive

### Step 2: Configuration of Database

Edit `application/config/database.php` with the correct information.

### Step 3: Import database

* On UNIX run the command ./minion migrations:run
* On Windows run the command php minion migrations:run

### Step 4: Configuration of modulargaming

Open `application/bootstrap.php` and make the following changes: 

* Set the default directory for your application if it does not automatically work

* Set the default cookie salt

* Set the default [timezone](http://php.net/timezones) for your application

Open `application/config/auth.php` and make the following changes:

* Set the default hash key

Open `.htaccess` and make the following changes:

* Set the correct RewriteBase

* Set the correct environment, either development or production.


Open `application/config/email.php` and make the following changes:

* Set the default from address

### Step 5: Permissions

	$ chmod 0755 application/{cache,logs}
	$ chmod 0755 assets

### Step 6: Cron jobs

	$ php ./minion Pet
	$ php ./minion Restock

## Testing

We use BDD, with 2 different tools, phpspec and behat.

We use composer to install them, http://getcomposer.org/ or you could install them manually using pear.

	$ curl -s https://getcomposer.org/installer | php
	$ ./composer.phar install --dev

## Documentation

The userguide module included in all Modular Gaming releases also allows you to view the documentation locally. It is accessible from your site via `/guide`

## Reporting bugs

If you've stumbled across a bug, please help us out by [reporting the bug](https://github.com/hinton/mg/issues?state=open) you have found. Simply log in or register and submit a new issue, leaving as much information about the bug as possible, e.g.

* Steps to reproduce
* Expected result
* Actual result

This will help us to fix the bug as quickly as possible, and if you'd like to fix it yourself feel free to fork us on GitHub and submit a pull request!

## Contributing

Contributions are encouraged and welcome; however, please review the Developer Certificate of Origin in the LICENSE.md file included in the repository. All commits must be signed off using the -s switch.
