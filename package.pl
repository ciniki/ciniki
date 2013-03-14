#!/usr/bin/perl

use POSIX qw(strftime);
use File::Basename;
use FindBin qw($Bin);

$zipcmd = '/usr/bin/zip';


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
	$vini .= "[package]\n";
	$vini .= "name = $package_name\n";
	$vini .= "version = " . strftime("%Y%m%d.%H%M", localtime($2)) . "\n";
	$vini .= "author = $1\n";
	$vini .= "hash = $3\n\n";
	# Don't add master package to code versions.ini
}

$mods = `git submodule foreach 'git show --format='%cn:::%ct:::%H' |grep :::'`;

$mods =~ s/Entering \'site\/(ciniki)-(api|manage|manage-themes)\/([a-z]+)\'\n(.*):::(.*):::(.*)/$1:::$2:::$3:::$4:::$5:::$6/g;

@modules = split("\n", $mods);
foreach $mod (@modules) {
	print "packaging $mod\n";
	if( $mod =~ /(.*):::(.*):::(.*):::(.*):::(.*):::(.*)/ ) {
		open(my $ini, ">", "site/$1-$2/$3/_version.ini");
		print $ini "mod_name = $1.$3\n";
		print $ini "version = " . strftime("%Y%m%d.%H%M", localtime($5)) . "\n";
		print $ini "author = $4\n";
		print $ini "hash = $6\n\n";
		close($ini);
		unlink("site/ciniki-code/$1.$2.$3.zip");
		chdir("site/$1-$2/$3");
		`$zipcmd -x cache/\\*/\\* -x uploads/\\*/\\* -r ../../ciniki-code/$1.$2.$3.zip *`;
		chdir("../../..");

		# Update master version file
		$vini .= "[$1.$2.$3]\n";
		$vini .= "version = " . strftime("%Y%m%d.%H%M", localtime($5)) . "\n";
		$vini .= "author = $4\n";
		$vini .= "hash = $6\n\n";
		$cini .= "[$1.$2.$3]\n";
		$cini .= "version = " . strftime("%Y%m%d.%H%M", localtime($5)) . "\n";
		$cini .= "author = $4\n";
		$cini .= "hash = $6\n\n";
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

	$vini .= "[ciniki.lib.$lib]\n";
	open(my $in, "site/ciniki-lib/$lib/_version.ini");
	$contents = join("", <$in>);
	$vini .= $contents;
	close($in);
	$vini .= "\n";
}
closedir($dir);

open(my $outfile, ">", "site/_versions.ini");
print $outfile $vini;
close($outfile);
open(my $outfile, ">", "site/ciniki-code/_versions.ini");
print $outfile $cini;
close($outfile);

`rm site/ciniki-api/*/_version.ini`;
`rm site/ciniki-manage/*/_version.ini`;
`rm site/ciniki-manage-themes/*/_version.ini`;
