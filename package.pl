#!/usr/bin/perl

use POSIX qw(strftime);
use File::Basename;
use FindBin qw($Bin);

$zipcmd = '/usr/bin/zip';
$zipinfo = '/usr/bin/zipinfo';


$package = `git show --format='%cn:::%ct:::%H' |grep :::`;
if( $package =~ /(.*):::(.*):::(.*)/ ) {
	($name, $path, $suffix) = fileparse($Bin);
	$package_name = $name;

	open(my $ini, ">", "_version.ini");
	print $ini "name = $package_name\n";
	print $ini "version = " . strftime("%Y%m%d.%H%M", gmtime($2)) . "\n";
	print $ini "author = $1\n";
	print $ini "hash = $3\n\n";
	close($ini);

	# Update master version file
	$vini .= "[package]\n";
	$vini .= "name = $package_name\n";
	$vini .= "version = " . strftime("%Y%m%d.%H%M", gmtime($2)) . "\n";
	$vini .= "author = $1\n";
	$vini .= "hash = $3\n\n";
	# Don't add master package to code versions.ini
}

$mods = `git submodule foreach 'git show --format='%cn:::%ct:::%H' |grep :::'`;

$mods =~ s/Entering \'site\/(ciniki)-(mods|lib|manage-themes)\/([A-Za-z]+)\'\n(.*):::(.*):::(.*)/$1:::$2:::$3:::$4:::$5:::$6/g;

@modules = split("\n", $mods);
$updates = "";
foreach $mod (@modules) {
	if( $mod =~ /(.*):::(.*):::(.*):::(.*):::(.*):::(.*)/ ) {
		$pkg = $1;
		$sec = $2;
		$md = $3;
		$author = $4;
		$modtime = $5;
		$modhash = $6;
		printf("%-50s", "packaging $pkg.$sec.$md...");
		open(my $ini, ">", "site/$1-$2/$3/_version.ini");
		print $ini "mod_name = $1.$3\n";
		print $ini "version = " . strftime("%Y%m%d.%H%M", gmtime($5)) . "\n";
		print $ini "author = $4\n";
		print $ini "hash = $6\n\n";
		close($ini);
		chdir("site/$1-$2/$3");
		`$zipcmd -x .git -x cache/\\*/\\* -x uploads/\\* -x uploads/\\*/\\* -r ../../ciniki-code/$1.$2.$3.zip.tmp *`;
		chdir("../../..");
	
		# Check if the file has changed at all
		if( ! -e "site/ciniki-code/$1.$2.$3.zip" ) {
			rename("site/ciniki-code/$1.$2.$3.zip.tmp", "site/ciniki-code/$1.$2.$3.zip");
			print "added\n";
			$updates .= "$pkg.$sec.$md\n";
		} else {
			$old = `$zipinfo site/ciniki-code/$1.$2.$3.zip`;
			$new = `$zipinfo site/ciniki-code/$1.$2.$3.zip.tmp`;
			$old =~ s/^.*\n//m;
			$old =~ s/^.*_version.ini\n//m;
			$new =~ s/^.*\n//m;
			$new =~ s/^.*_version.ini\n//m;
			if( $old ne $new ) {
				unlink("site/ciniki-code/$pkg.$sec.$md.zip");
				rename("site/ciniki-code/$pkg.$sec.$md.zip.tmp", "site/ciniki-code/$pkg.$sec.$md.zip");
				print "*UPDATED\n";
				$updates .= "$pkg.$sec.$md\n";
			} else {
				print "skipped\n";
				unlink("site/ciniki-code/$pkg.$sec.$md.zip.tmp");
			}
		}

		# Update master version file
		$vini .= "[$pkg.$sec.$md]\n";
		$vini .= "version = " . strftime("%Y%m%d.%H%M", gmtime($modtime)) . "\n";
		$vini .= "author = $author\n";
		$vini .= "hash = $modhash\n\n";
		$cini .= "[$pkg.$sec.$md]\n";
		$cini .= "version = " . strftime("%Y%m%d.%H%M", gmtime($modtime)) . "\n";
		$cini .= "author = $author\n";
		$cini .= "hash = $modhash\n\n";
	}
}

#if( $updates ne '' ) {
#	print "Updated packages:\n";
#	print $updates;
#}

# 
# Check ciniki-lib files for versions
#
opendir(my $dir, "site/ciniki-lib");
while(readdir $dir) {
	if( $_ eq '..' || $_ eq '.' ) {
		next;
	}
	$lib = $_;
	if( -e "site/ciniki-lib/$lib/.git" ) {
		# skip any lib's that are pulled through git
		next;
	}
	if( ! -d "site/ciniki-lib/$lib" ) {
		next;
	}
	$pkg = 'ciniki';
	$sec = 'lib';
	$md = $lib;
	printf("%-50s", "packaging $pkg.$sec.$md...");
	chdir("site/ciniki-lib/$lib");
	`$zipcmd -x .git -r ../../ciniki-code/ciniki.lib.$lib.zip.tmp *`;
	chdir("../../..");

#	unlink("site/ciniki-code/ciniki.lib.$lib.zip");
	# Check if the file has changed at all
	if( ! -e "site/ciniki-code/$pkg.$sec.$md.zip" ) {
		rename("site/ciniki-code/$pkg.$sec.$md.zip.tmp", "site/ciniki-code/$pkg.$sec.$md.zip");
		print "added\n";
		$updates .= "$pkg.$sec.$md\n";
	} else {
		$old = `$zipinfo site/ciniki-code/$pkg.$sec.$md.zip`;
		$new = `$zipinfo site/ciniki-code/$pkg.$sec.$md.zip.tmp`;
		$old =~ s/^.*\n//m;
		$old =~ s/^.*_version.ini\n//m;
		$new =~ s/^.*\n//m;
		$new =~ s/^.*_version.ini\n//m;
		if( $old ne $new ) {
			unlink("site/ciniki-code/$pkg.$sec.$md.zip");
			rename("site/ciniki-code/$pkg.$sec.$md.zip.tmp", "site/ciniki-code/$pkg.$sec.$md.zip");
			print "*UPDATED\n";
			$updates .= "$pkg.$sec.$md\n";
		} else {
			print "skipped\n";
			unlink("site/ciniki-code/$pkg.$sec.$md.zip.tmp");
		}
	}

	$vini .= "[ciniki.lib.$lib]\n";
	$cini .= "[ciniki.lib.$lib]\n";
	open(my $in, "site/ciniki-lib/$lib/_version.ini");
	$contents = join("", <$in>);
	$vini .= $contents;
	$cini .= $contents;
	close($in);
	$vini .= "\n";
	$cini .= "\n";
}
closedir($dir);

unlink("site/ciniki-lib/PHPMailer/_version.ini");

open(my $outfile, ">", "site/_versions.ini");
print $outfile $vini;
close($outfile);
open(my $outfile, ">", "site/ciniki-code/_versions.ini");
print $outfile $cini;
close($outfile);

`rm site/ciniki-mods/*/_version.ini`;
`rm site/ciniki-manage-themes/*/_version.ini`;
