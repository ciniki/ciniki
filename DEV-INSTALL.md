Development Install
===================
These instructions are only for those allowed commit privileges to checkin changes to the core code.

Create SSH Key 
--------------
In order to do development work, you must download the code as a registered github user to the ciniki project. 

Follow the instructions at http://help.github.com/mac-set-up-git/ to setup you ssh key and github token.  These
will allow you to download and contribute back to the code.

Setup Ciniki
------------
Follow the instruction in README.md to setup Ciniki, with webserver and mail server.

SSL for dev/test
----------------
The following command can be used to generate a SSL key for apache, but is not
signed and should not be used in production.
```
	cd /ciniki/<instance>/
	mkdir ssl
	cd ssl/
	sudo openssl genrsa -out server.key 4096
	sudo chmod 400 server.key
	sudo openssl req -new -key server.key -out server.csr
		-- Common name MUST be <instance>.ciniki.ca
		-- Blank for password and company name
	sudo openssl x509 -req -days 365 -in server.csr -signkey server.key -out server.crt
```

Clone dev-tools helper scripts
------------------------------
Checkout the dev-tools module from the github to aid in managing the development.

```
git clone git@github.com:ciniki/dev-tools.git
```

If you want to update the tool set:
```
cd dev-tools
git remote add push git@github.com:ciniki/dev-tools.git 
```

Run the init script which will checkout all masters and setup the remote git repo for checkin.
```
./dev-tools/dev-init.sh
```


Commit, Push, Pull
==================
To commit all changes in all modules with one commit msg:
./dev-tools/commit.sh 'msg'

To push all changes up to github:
./dev-tools/push.sh

To pull the recent code for all modules:
./dev-tools/pull.sh


Workflow
========
The following workflow is recommended while working on the code

Update the code to the latest from the repo.
./dev-tools/pull.sh

Make changes to code, then commit and push. It's a good idea to pull
after the push to update all the submodules.
./dev-tools/commit.sh 'commit msg'
./dev-tools/push.sh
./dev-tools/pull.sh


Adding a new submodule
======================
First, create the repo on github under ciniki.

Then, add submodule to the local ciniki project directory.  The module should be
added using the public URL so the master ciniki git repo can pull it without permissions.

```
cd ciniki/
git submodule add git://github.com/ciniki/<modulename>.git site/ciniki-mods/<modulename>
cd site/ciniki-mods/<modulename>
git remote add push git@github.com:ciniki/<modulename>.git 
```

Once the module has been added, it should be added to initialization script for the project.
Edit dev-tools/dev-init.sh and add the project.

Documentation
=============
The documentation is all stored inline in the code, or in the docs directory for each module.

To access the documentation, it is available from the API in XML format currently.  In the
future it will be generated to a website for easy viewing online.


