# Modular Gaming

A modular browser based game framework using Kohana 3.3 with ORM and KOstache.

> It is unstable and still developing.

## Requirements

* PHP 5.3+
* MySQL 5.1+

## Installation

Step 1: Download

Download Modular Gaming from www.modulargaming.com

Step 2: Configuration of Database

Edit `application/config/database.php` with the correct information.

Step 3: Import SQL

Import modulargaming.sql using your tool of choice (MySQL client, PHPMyAdmin etc)

Step 4: Configuration of modulargaming

Open `application/bootstrap.php` and make the following changes: 

* Set the default [timezone](http://php.net/timezones) for your application

* Set the default directory for your application

* Update the default cookie salt

Step 5:

* Update .htaccess to refer to the correct location

* chmod 0777 application/{cache,logs}

