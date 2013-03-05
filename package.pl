#!/usr/bin/perl

use POSIX qw(strftime);
use File::Basename;
use FindBin qw($Bin);

$zipcmd = '/usr/bin/zip';

open(my $vini, ">", "site/_versions.ini");
open(my $cini, ">", "site/ciniki-code/_versions.ini");

$package = `git show --format='%cn:::%ct:::%H' |grep :::`;
if( $package =~ /(.*):::(.*):::(.*)/ ) {
	($name, $path, $suffix) = fileparse($Bin);
	$package_name = $name;

	open(my $ini, ">", "_version.ini");
	print $ini "name = $package_name\n";
	print $ini "version = " . strftime("%Y%m%d.%H%M", localtime($2)) . "\n";
	print $ini "author = $1\n";
	print $ini "hash = $3\n\n";
	close($ini);

	# Update master version file
	print $vini "[package]\n";
	print $vini "name = $package_name\n";
	print $vini "version = " . strftime("%Y%m%d.%H%M", localtime($2)) . "\n";
	print $vini "author = $1\n";
	print $vini "hash = $3\n\n";
	# Don't add master package to code versions.ini
}

$mods = `git submodule foreach 'git show --format='%cn:::%ct:::%H' |grep :::'`;

$mods =~ s/Entering \'site\/(ciniki)-(api|manage|manage-themes)\/([a-z]+)\'\n(.*):::(.*):::(.*)/$1:::$2:::$3:::$4:::$5:::$6/g;

@modules = split("\n", $mods);
foreach $mod (@modules) {
	if( $mod =~ /(.*):::(.*):::(.*):::(.*):::(.*):::(.*)/ ) {
		open(my $ini, ">", "site/$1-$2/$3/_version.ini");
		print $ini "mod_name = $1.$3\n";
		print $ini "version = " . strftime("%Y%m%d.%H%M", localtime($5)) . "\n";
		print $ini "author = $4\n";
		print $ini "hash = $6\n\n";
		close($ini);
		unlink("site/ciniki-code/$1.$2.$3.zip");
		chdir("site/$1-$2/$3");
		`$zipcmd -x .git -r ../../ciniki-code/$1.$2.$3.zip *`;
		chdir("../../..");

		# Update master version file
		print $vini "[$1.$2.$3]\n";
		print $vini "version = " . strftime("%Y%m%d.%H%M", localtime($5)) . "\n";
		print $vini "author = $4\n";
		print $vini "hash = $6\n\n";
		print $cini "[$1.$2.$3]\n";
		print $cini "version = " . strftime("%Y%m%d.%H%M", localtime($5)) . "\n";
		print $cini "author = $4\n";
		print $cini "hash = $6\n\n";
	}
}

# 
# Check ciniki-lib files for versions
#
opendir(my $dir, "site/ciniki-lib");
while(readdir $dir) {
	if( $_ eq '..' || $_ eq '.' ) {
		next;
	}
	$lib = $_;
	if( ! -d "site/ciniki-lib/$lib" ) {
		next;
	}
	unlink("site/ciniki-code/ciniki.lib.$lib.zip");
	chdir("site/ciniki-lib/$lib");
	`$zipcmd -x .git -r ../../ciniki-code/ciniki.lib.$lib.zip *`;
	chdir("../../..");

	print $vini "[ciniki.lib.$lib]\n";
	open(my $in, "site/ciniki-lib/$lib/_version.ini");
	$contents = join("", <$in>);
	print $vini $contents;
	close($in);
	print $vini "\n";
}
closedir($dir);

close($vini);
close($cini);

`rm site/_version.ini`;
`rm site/ciniki-api/*/_version.ini`;
`rm site/ciniki-manage/*/_version.ini`;
`rm site/ciniki-manage-themes/*/_version.ini`;
