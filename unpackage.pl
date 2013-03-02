#!/usr/bin/perl

use POSIX qw(strftime);
use File::Basename;
use FindBin qw($Bin);

`rm site/_version.ini`;
`rm site/ciniki-api/*/_version.ini`;
`rm site/ciniki-manage/*/_version.ini`;
`rm site/ciniki-manage-themes/*/_version.ini`;


close($vini);
