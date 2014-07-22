#!/usr/bin/perl

use POSIX qw(strftime);
use File::Basename;
use FindBin qw($Bin);

`rm site/_version.ini`;
`rm site/ciniki-mods/*/_version.ini`;


close($vini);
