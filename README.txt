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

cp config.ini.default config.ini


Development
-----------
Instructions for Developers to install are located in DEV-INSTALL.txt

License
-------
Ciniki is free software, and is released under the terms of the MIT License. See LICENSE.txt.
