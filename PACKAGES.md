Packages are a way to extend Ciniki platform with your own proprietary modules.


Setup
=====
In order to create your own package, you'll need a git server.  You can use github,
or create one on your own server.  The following instructions are for your own server,
but can be adapted to github.

1. Create the directory to store your git modules, main package and initialize.
	- mkdir /<name>
	- mkdir /<name>/package
	- cd /<name>/package
	- git init --bare

2. Create a directory and initialize for each of your own custom modules.
	- mkdir /<name>/api-<modulename>
	- cd /<name>/api-<modulename>
	- git init --bare
	- cd /
	- mkdir /<name>/manage-<modulename>
	- cd /<name>/manage-<modulename>
	- git init --bare

3. Create a development environment.
	You'll need a development server or environment outside of the git repository.

	- git clone username@server:/<name>/package
	- Create a README.md file
	- git commit -am 'First commit'
	- git push -u origin master
   
4. Clone the Ciniki modules.

	- cd /<name>
	- git submodule add git://github.com/ciniki/api-<modulename>.git site/ciniki-api/<modulename>

	If you have access rights to the github repository, you can add the rights to push back.
	- cd site/ciniki-api/<modulename>
	- git remote add push git@github.com:ciniki/api-<modulename>.git

	This process should be repeated for all the api and manage modules you require for your project.

5. Clone your package modules

    - cd /<name>
	- git submodule add username@server:/<name>/api-<modulename> site/<name>-api/<modulename>
	- cd site/<name>-api/<modulename>
	- git remote add push username@server:/<name>/api-<modulename>


Ciniki is released as a set of modules which can be reused individually or as a package.
To pull down all the code, run the following command:

git clone git://github.com/ciniki/ciniki.git
cd ciniki
git submodule update --init

If this is running locally, then the ssl and log directories should be created, and
the site directory needs to be writable by www-data to enable the install script.
- mkdir logs
- mkdir ssl
- sudo chown www-data site

