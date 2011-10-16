#!/bin/bash

git submodule foreach 'git commit -am "$2" || :'
git commit -am "$1"
