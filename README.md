# Modular Gaming

A modular browser based game framework based on Kohana 3.3 using KOstache and ORM.

> It is unstable and still developing.

## Requirements

* PHP 5.3+
* Mysql 5.1+

## Installation

Step 1: Download Modular Gaming!

Using your console, to get it from git execute the following command in the root of your development environment:

	$ git clone git://github.com/modulargaming/modulargaming.git

And watch the git magic...

Of course you can always download the code from the http://www.modulargaming.com

Step 2: Initial Structure

Next the submodules must be initialized:

	cd modulargaming (or whatever you named it)

	$ git submodule init
	
Now that the submodules are added, update them:

	$ git submodule update

Now do the same for KOstache:

	$ cd modules/kostache
	$ git submodule init
	$ git submodule update

That's all there is to it.

Step 3: Configuration of Database

Edit `application/config/database.php` with the correct information.

Step 4: Import SQL

Import modulargaming.sql using your tool of choice (MySQL client, PHPMyAdmin etc)

Step 5: Configuration of modulargaming

Open `application/bootstrap.php` and make the following changes: 

* Set the default [timezone](http://php.net/timezones) for your application

* Set the default directory for your application

* Update the default cookie salt

> You will need to update .htaccess to refer to the correct location of the folder containing Modular Gaming. 

Make sure the `application/cache` and `application/logs` directories are world writable with:

 `chmod 0777 application/{cache,logs}`


Now browse to `yourdomain.com` and you should see the **Home Page**.

