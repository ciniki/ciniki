Ciniki - Small Business Management Platform

Submodules
----------
Ciniki is released as a set of modules which can be reused individually or as a package.
To pull down all the code, run the following command:

git clone git@github.com:ciniki/ciniki.git
cd ciniki
git submodule update --init

Development
-----------
If you are doing development run the ./script/dev-init.sh script so the modules can be
checked out at master and available to push change up.

Commit, Push, Pull
+---------
To commit all changes in all modules with one commit msg:
	./scripts/commit.sh 'msg'

To push all changes up to github:
	./scripts/push.sh

To pull the recent code for all modules:
	./scripts/pull.sh

License
-------
Ciniki is free software, and is released under the terms of the MIT License. See LICENSE.txt.
