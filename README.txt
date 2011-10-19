Ciniki - Small Business Management Platform

FIXME: Need description


Install
-------
Ciniki is released as a set of modules which can be reused individually or as a package.
To pull down all the code, run the following command:

git clone git://github.com/ciniki/ciniki.git
cd ciniki
git submodule update --init

Link files to php scripts

cd site
ln -s ciniki-api/core/scripts/rest.php ciniki-rest.php
ln -s ciniki-api/core/scripts/json.php ciniki-json.php

Setup config file

cp ciniki.ini.default ciniki.ini

Setup Robot
-----------
The Ciniki Robot will take care of cron jobs, and emails alerts to approriate people.
The reason to include Robot in the name, is so users understand easily this is coming
from an automated system and not a human.

1. Make sure the email address you're going to use is available and forwarded to 
   somebody who will read it.  Any bounced messages etc will be sent this address.

2. Setup the address in the system.email and system.email.name config variables in ciniki.ini

3. Ensure mail services are running for outbound mail.

Development
-----------
Instructions for Developers to install are located in DEV-INSTALL.txt

License
-------
Ciniki is free software, and is released under the terms of the MIT License. See LICENSE.txt.
