# [Modular Gaming](http://www.modulargaming.com)

Modular Gaming is a open source [persistent browser based game](http://www.pbbg.org) framework built upon [Kohana 3.3](https://github.com/kohana/core) with modules such as [ORM](https://github.com/kohana/orm) and [KOstache](https://github.com/zombor/KOstache).

Released under a [BSD license](http://www.modulargaming.com/license), Modular Gaming can be used legally for any open source, commercial, or personal project.

## Requirements

* PHP 5.3.3+
* MySQL
* [Composer](http://getcomposer.org) (Dependency Manager)

## Installation

### Step 1: Download

Download Modular Gaming from Github and install composer dependencies.

        $ git clone https://github.com/modulargaming/modulargaming.git
        $ cd modulargaming

        $ curl -s https://getcomposer.org/installer | php
        $ php ./composer.phar install --dev

Alternatively you can also use composer create-project to download the project and the dependencies.

        $ composer create-project modulargaming/modulargaming modulargaming dev-master

If you cannot use Composer you can download a packaged copy of Modular Gaming from http://www.sourceforge.net/projects/modulargaming/files/



### Step 2: File Permissions

	$ chmod 0777 application/{cache,logs}
	$ chmod 0777 assets
	$ chmod 0777 media

### Step 3: Run the installer

Open `.htaccess` and make the following changes:

* Set the correct RewriteBase

Start the installer and follow the instructions by browsing to the Modular Gaming install with your web browser.

* Once you have completed install, delete the install.php file.

### Step 4: Admin

Register your admin account at /user/register.
Promote your newly created account to admin by using the minion task:

	$ php minion User:Promote --username=admin

You should now verify that you have admin access by accessing the administration panel at /admin/.

### Step 5: Cron jobs

	$ php ./minion Pet:Decrease
	$ php ./minion Item:Restock


### Step 6: Configuration of modulargaming

Open `.htaccess` and make the following changes:

* Set the correct environment, either development or production.

Open `application/bootstrap.php` and make the following changes: 

* Set the default directory for your application if it does not automatically work

* Set the default cookie salt

* Set the default [timezone](http://php.net/timezones) for your application

Open `application/config/auth.php` and make the following changes:

* Set the default hash key

Open `application/config/email.php` and make the following changes:

* Set the default from address

## Testing

We use BDD, with 2 different tools, phpspec and behat.

	$ curl -s https://getcomposer.org/installer | php
	$ ./composer.phar install --dev

## Documentation

The userguide module included in all Modular Gaming releases also allows you to view the documentation locally. It is accessible from your site via `/guide`

## Reporting bugs

If you've stumbled across a bug, please help us out by [reporting the bug](https://github.com/modulargaming/modulargaming/issues?state=open) you have found. Simply log in or register and submit a new issue, leaving as much information about the bug as possible, e.g.

* Steps to reproduce
* Expected result
* Actual result

This will help us to fix the bug as quickly as possible, and if you'd like to fix it yourself feel free to fork us on GitHub and submit a pull request!

## Contributing

Contributions are encouraged and welcome; however, please review the Developer Certificate of Origin in the LICENSE.md file included in the repository. All commits must be signed off using the -s switch.
