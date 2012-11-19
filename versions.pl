#!/usr/bin/perl

use POSIX qw(strftime);
use File::Basename;
use FindBin qw($Bin);

$output_type = 'txt';
if( $ARGV[0] eq '-i' ) {
	$output_type = 'ini';
}

$package = `git show --format='%cn:::%ct:::%H' |grep :::`;
if( $package =~ /(.*):::(.*):::(.*)/ ) {
	($name, $path, $suffix) = fileparse($Bin);
	$package_name = $name;

	if( $output_type eq 'ini' ) {
		print "[package]\n";
		print "name = $package_name\n";
		print "version = " . strftime("%Y%m%d.%H%M", localtime($2)) . "\n";
		print "author = $1\n";
		print "hash = $3\n\n";
	} else {
		print "package - $package_name - " . strftime("%Y%m%d.%H%M", localtime($2)) . ", $1 - $3\n";
	}
}

$mods = `git submodule foreach 'git show --format='%cn:::%ct:::%H' |grep :::'`;

$mods =~ s/Entering \'site\/(ciniki)-(api|manage|manage-themes)\/([a-z]+)\'\n(.*):::(.*):::(.*)/$1:::$2:::$3:::$4:::$5:::$6/g;

@modules = split("\n", $mods);
foreach $mod (@modules) {
	if( $mod =~ /(.*):::(.*):::(.*):::(.*):::(.*):::(.*)/ ) {
		if( $output_type eq 'ini' ) {
			print "[$1.$2.$3]\n";
			print "version = " . strftime("%Y%m%d.%H%M", localtime($5)) . "\n";
			print "author = $4\n";
			print "hash = $6\n\n";
		} else {
			print "module - $1.$2.$3 - " . strftime("%Y%m%d.%H%M", localtime($2)) . " - $4 - $6\n";
		}
	}
}
