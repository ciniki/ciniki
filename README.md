Ciniki - Small Business Management Platform

FIXME: Need description


Install
=======
Ciniki is released as a set of modules which can be reused individually or as a package.
To pull down all the code, run the following command:

git clone git://github.com/ciniki/ciniki.git
cd ciniki
git submodule update --init

If this is running locally, then the ssl and logs directories should be created, and
the site directory needs to be writable by www-data to enable the install script.
mkdir logs
mkdir ssl
sudo chown www-data site


Setup Web Server
================
Setup your web server with a new site, which has the root of the site folder inside ciniki.

Ubuntu with Apache2
-------------------
cd /etc/apache2/sites-available

Copy the default site conf from site/ciniki-api/core/docs/apache2.virtualhost.ssl

Edit the file for the settings on your server.

No link the file into sites-enabled
cd /etc/apache2/sites-enabled
sudo ln -s ../sites-available/instance.mydomain.com

Setup the rewrite module
cd /etc/apache2/mods-enabled
sudo ln -s ../mods-available/rewrite.load

Setup SSL
---------
FIXME: Add instructions for SSL cert generation

Restart Apache
--------------
Before restarting apache, makes sure to run configtest and validate the site config.
sudo apache2ctl configtest
sudo apache2ctl restart

Setup database
==============
Create the database for Ciniki.  It is recommended to create a username and password
specific to this database.  

Here is a sample grant statement for the required privileges in MySQL.

GRANT alter, create, create temporary tables, delete, index, insert, lock tables, select, update ON <instancename>.\* to 'ciniki'@'localhost' IDENTIFIED BY '<min32randomcharacterpassword>';



Setup Email Robot
=================
The Ciniki Robot will take care of cron jobs, and emails alerts to approriate people.
The reason to include Robot in the name, is so users understand easily this is coming
from an automated system and not a human.

1. Make sure the email address you're going to use is available and forwarded to 
   somebody who will read it.  Any bounced messages etc will be sent this address.

2. Ensure mail services are running for outbound mail.


Run the installer
=================
Open your browser and go to the domain you installed ciniki to, and run the install script.

http://<hostname>/ciniki-install.php



Manual Configuration
====================
cd site
ln -s ciniki-api/core/scripts/rest.php ciniki-rest.php
ln -s ciniki-api/core/scripts/json.php ciniki-json.php
ln -s ciniki-manage/core/scripts/manage.php ciniki-manage.php
ln -s ciniki-manage/core/scripts/login.php ciniki-login.php

Setup config files
cp ciniki-api/core/docs/ciniki.ini.default ciniki-api.ini
cp ciniki-manage/core/docs/ciniki.ini.default ciniki-manage.ini

Don't forget to setup the address in the system.email and system.email.name config variables in ciniki-api.ini



MAC OS X
========
When running this on mac with the native PHP5, the follow macports need to be installed:

1. php5-imagick
	sudo port install php5-imagick

2. Setup /etc/php.ini file to include the line:
	extension=/opt/local/lib/php/extensions/no-debug-non-zts-20090626/imagick.so


Development
===========
Instructions for Developers to install are located in DEV-INSTALL.txt



License
=======
Ciniki is free software, and is released under the terms of the MIT License. See LICENSE.txt.
