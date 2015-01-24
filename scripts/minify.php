#!/usr/bin/php
<?php
//
// This script will combine and minify the js and css for ciniki-manage, after which
// a cache.manifest will be created for each device, which will ensure faster 
// loading times
//

$ciniki_root = dirname(dirname(__FILE__));

// All browsers
$ciniki_js = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/ciniki.js");
$version = strftime("%y%m%d.%H%M", time());
$ciniki_js = preg_replace("/'version':'[0-9]{6}\.[0-9]{4}',/", "'version':'$version',", $ciniki_js);
$ciniki_panels_js = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/ciniki_panels.js");
$cinikiAPI_js = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/cinikiAPI.js");
$colorPicker_js = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/colorPicker.js");

$all_browser_js = $ciniki_js . "\n" . $ciniki_panels_js . "\n" . $cinikiAPI_js . "\n" . $colorPicker_js;

$e_webkit_js = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/e-webkit.js");
$e_gecko_js = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/e-gecko.js");
$e_presto_js = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/e-presto.js");

$s_compact_js = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/s-compact.js");
$s_normal_js = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/s-normal.js");


$style_css = load_minify_css("$ciniki_root/site/ciniki-mods/core/ui/themes/default/style.css");
$d_ipad_css = load_minify_css("$ciniki_root/site/ciniki-mods/core/ui/themes/default/d-ipad.css");
$d_generic_css = load_minify_css("$ciniki_root/site/ciniki-mods/core/ui/themes/default/d-generic.css");

$e_webkit_css = load_minify_css("$ciniki_root/site/ciniki-mods/core/ui/themes/default/e-webkit.css");
$e_gecko_css = load_minify_css("$ciniki_root/site/ciniki-mods/core/ui/themes/default/e-gecko.css");
$e_presto_css = load_minify_css("$ciniki_root/site/ciniki-mods/core/ui/themes/default/e-presto.css");
$e_trident_css = load_minify_css("$ciniki_root/site/ciniki-mods/core/ui/themes/default/e-trident.css");

$s_compact_css = load_minify_css("$ciniki_root/site/ciniki-mods/core/ui/themes/default/s-compact.css");
$s_normal_css = load_minify_css("$ciniki_root/site/ciniki-mods/core/ui/themes/default/s-normal.css");
$s_normal_webkit_css = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/themes/default/s-normal-webkit.css");
$s_normal_gecko_css = load_minify_js("$ciniki_root/site/ciniki-mods/core/ui/themes/default/s-normal-gecko.css");
$s_normal_presto_css = '';
$s_normal_trident_css = '';


//
// **** Smart Phones ******
//
file_put_min_cssjs('android', 'webkit', 'compact'); 				// Device: Android, Engine: Webkit
file_put_min_cssjs('iphone', 'webkit', 'compact'); 					// Device: iphone, Engine: webkit
file_put_min_cssjs('blackberry', 'webkit', 'compact'); 				// Device: Blackberry, Engine: webkit

//
// ******* Tablets **********
//
file_put_min_cssjs('ipad', 'webkit', 'normal'); 					// Device: iPad, Engine: webkit

// 
// ****** Generic Devices ******
//
file_put_min_cssjs('generic', 'webkit', 'normal');					// Device: generic, Engine: webkit (Chrome, Safari)
file_put_min_cssjs('generic', 'gecko', 'normal'); 					// Device: generic, Engine: gecko (Firefox)
file_put_min_cssjs('generic', 'presto', 'normal'); 					// Device: generic, Engine: presto (Opera)
file_put_min_cssjs('generic', 'trident', 'normal'); 				// Device: generic, Engine: trident (IE) 


function load_minify_js($filename) {
	$file_contents = file_get_contents($filename);
	$pre_count = strlen($file_contents);
	$file_contents = preg_replace(
		array('/^\s*\/\/.*$/m',														//  1 - Remove comment lines
			'/^\s*$/m',																//  2 - Remove blank lines
			'/\s*$/m',																//  3 - Remove blanks from end of line.
			'/^\s*/m',																//  4 - Remove blanks from start of line.
			'/^\s*var ([a-zA-Z0-9_]+) = /m',										//  5 - Remove blanks from around equal signs
			'/^\s*([a-zA-Z0-9_\.]+) = /m',											//  6 - Remove blanks from variable assignments
			'/\s*=\s+([^\s]+\;)\s*$/m',												//  7 - Remove blanks from variable assignments
			'/\s*\/\/.*$/m',														//  8 - Remove comments from end of lines
			'/\s+\+\s+/',															// 10 - Remove spaces around + characters
			'/^\s*}\s*else\s+if\( (.*) \) {\s*$/m',										// 20 - Remove blanks from else if statement
			'/^\s*if\( ([a-zA-Z0-9_]+) (\<|\>|==|!=) ([a-zA-Z0-9_\-]+) \) {\s*$/m',		// 21 - Remove blanks from if statement
			'/^\s*if\( (.*) \)\s*{\s*$/m',												// 22 - Remove blanks from if statement
			'/^\s*}\s*else\s*{\s*$/m',													// 23 - Remove blanks from else statement
			'/^\s*([a-zA-Z0-9_\.]+)\s*(\+|\-)=\s*/m',									// 30 - Remove blanks from around += and -= 
			'/^\s*(.*)=\s*function\(\s*\)\s*{\s*$/m',								// 39 - remove spaces between first and second arguments in function declarations
			'/^\s*(.*)=\s*function\(\s*([a-zA-Z_,]+)\s*\)\s*{\s*$/m',					// 40 - remove spaces between first and second arguments in function declarations
			'/^\s*(.*)=\s*function\(([a-zA-Z,]+),\s*([a-zA-Z, ]+)\s*\)\s*{\s*$/m',		// 41 - remove spaces between first and second arguments in function declarations
			'/^\s*(.*)=\s*function\(([a-zA-Z,]+),\s*([a-zA-Z, ]+)\s*\)\s*{\s*$/m',		// 42 - again to get between second and third argument
			'/^\s*(.*)=\s*function\(([a-zA-Z,]+),\s*([a-zA-Z, ]+)\s*\)\s*{\s*$/m',		// 43 - again 
			'/^\s*(.*)=\s*function\(([a-zA-Z,]+),\s*([a-zA-Z, ]+)\s*\)\s*{\s*$/m',		// 44 - again 
			'/^\s*(.*)=\s*function\(([a-zA-Z,]+),\s*([a-zA-Z, ]+)\s*\)\s*{\s*$/m',		// 45 - again 
			'/if\(\s*([^\s]+)\s*(===|==|\!=)\s*([^\s]+)\s*\)/',														// 50 - remove == from if statements
			'/if\(\s*([^\s]+)\s*(===|==|\!=|\>|\<)\s*([^\s]+)\s*(\&\&|\|\|)\s*([^\s]+)\s*(===|==|\!=|\>|\<)\s*([^\s]+)\s*\)/',	// 51 - remove if with 2 comparisions
			'/if\(\s*([^\s]+)\s*(===|==|\!=|\>|\<)\s*([^\s]+)\s*(\&\&|\|\|)\s*([^\s]+)\s*(===|==|\!=|\>|\<)\s*([^\s]+)\s*\)/',	// 52 - remove if with 2 comparisions
			'/while\(\s([^\s]+)\s*(\>|\<)\s*([^\s ]+)\s*\)\s*{/',		// 60 - Remove spaces from while statements
			'/,\s*\n/m',									// 79 - Any lines ending in , can be joined with next line
			'/{\s*\n/m',									// 80 - Any lines ending in ; can be joined with next line
			'/}\s*\n\s*}/m',									// 81 - Any lines end with } and next starts with } can be joined.
			'/}\s*\n\s*}/m',									// 82 - Any lines end with } and next starts with } can be joined.
			'/}\s*\n\s*\]/m',									// 83 - Any lines end with } and next starts with ] can be joined.
			'/\[\s*\n\s*{/m',									// 85 - Any lines end with } and next starts with ] can be joined.
	//		'/}\s*\n/m',									// 84 - Any lines ending in ; can be joined with next line
			'/}\s*\n\s*var\s+/m',								// 86 - Any lines end with } and next starts with ] can be joined.
			'/\s*\n\s*\+\s*/m',									// 87 - Any lines that start with a + can be joined.
			'/}\s*\n\s*if\(/m',									// 88 - Any lines end with } and next starts with ] can be joined.
			'/}\s*\n\s*return\s+/m',							// 89 - Any lines end with } and next starts with not a function declaration.
			'/}\s*\n\s*([a-zA-Z\.]+\()/m',						// 90 - Any lines end with } and next starts with not a function declaration.
			'/}\s*\n\s*([a-zA-Z\.]+=\')/m',						// 91 - Any lines end with } and next starts with variable assignment.
			'/;\s*\n/m',										// 95 - Any lines ending in ; can be joined with next line
			),
		array('',										//  1
			'',											//  2
			'',											//  3
			'',											//  4
			'var $1=',									//  5
			'$1=',										//  6
			'=$1',										//  7
			'',											//  8
			'+',										// 10
			'}else if($1){',							// 20
			'if($1$2$3){',								// 21
			'if($1){',									// 22 
			'}else{',									// 23
			'$1$2=',									// 30 
			'$1=function(){',							// 39
			'$1=function($2){',							// 40
			'$1=function($2,$3){',						// 41
			'$1=function($2,$3){',						// 42
			'$1=function($2,$3){',						// 43
			'$1=function($2,$3){',						// 44
			'$1=function($2,$3){',						// 45
			'if($1$2$3)',								// 50
			'if($1$2$3$4$5$6$7)',						// 51
			'if($1$2$3$4$5$6$7)',						// 52
			'while($1$2$3){',							// 60
			',',										// 79
			'{',										// 80
			'}}',										// 81
			'}}',										// 82
			'}]',										// 83
			'[{',										// 85
	//		'};',										// 84
			'}var ',									// 86
			'+',										// 87
			'}if(',										// 88
			'}return ',									// 89
			'}$1',										// 90
			'}$1',										// 91
			';',										// 95
			),
		$file_contents);
	$post_count = strlen($file_contents);
	printf("%6d %6d (%2.2f%%) - $filename\n", $pre_count, $post_count, (($pre_count-$post_count)/$pre_count)*100);
	return $file_contents;
}

function load_minify_css($filename) {
	$file_contents = file_get_contents($filename);
	$pre_count = strlen($file_contents);
	$file_contents = preg_replace(
		array('/^\s*\/\*.*\*\/$/m',				// Remove comments
			'/^\s*$/m',							// Remove blank lines
			'/^\s*/m',							// Remove space at start of lines
//			'/\n/',								// Remove all line breaks;
			),
		array('',
			'',
			'',
//			'',
			),
		$file_contents);
	$post_count = strlen($file_contents);
	printf("%6d %6d (%2.2f%%) - $filename\n", $pre_count, $post_count, (($pre_count-$post_count)/$pre_count)*100);
	return $file_contents;	
}


function file_put_manifest($filename, $cache, $network) {
	global $version;
	file_put_contents($filename,
		  "CACHE MANIFEST\n"
		. "# Version: $version\n"
		. "CACHE:\n"
		. $cache
		. "index.php\n"
		. "ciniki-mods/core/ui/themes/default/img/arrow.png\n"
		. "ciniki-mods/core/ui/themes/default/img/expand.png\n"
		. "ciniki-mods/core/ui/themes/default/img/history.png\n"
		. "ciniki-mods/core/ui/themes/default/img/help_button.png\n"
		. "ciniki-mods/core/ui/themes/default/img/home_button.png\n"
		. "\n"
		. "NETWORK:\n"
		. "*\n"
		. "");
}

function file_put_min_cssjs($device, $engine, $size) {
	global ${"s_${size}_js"};
	global ${"e_${engine}_js"};
	global ${"d_${device}_js"};
	global ${"s_${size}_css"};
	global ${"e_${engine}_css"};
	global ${"d_${device}_css"};
	global ${"s_${size}_${engine}_css"};
	global $ciniki_root;
	global $all_browser_js;
	global $style_css;

	if( $engine != 'trident' ) {
		file_put_contents("$ciniki_root/site/ciniki-mods/core/ui/$device-$engine.min.js",
			$all_browser_js . "\n" . ${"s_${size}_js"} . "\n" . ${"e_${engine}_js"});
	}

//	if( $engine == 'trident' ) {
//		file_put_contents("$ciniki_root/site/cinikii/themes/default/$device-$engine.min.css",
//			${"e_${engine}_css"} . ${"d_${device}_css"} . ${"s_${size}_css"} . $style_css);
//	} else {
		file_put_contents("$ciniki_root/site/ciniki-mods/core/ui/themes/default/$device-$engine.min.css",
			$style_css . ${"e_${engine}_css"} . ${"s_${size}_css"} . ${"d_${device}_css"} . ${"s_${size}_${engine}_css"});
//	}
}


?>
