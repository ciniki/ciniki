<?php
//
// This file is the install script which will configure and setup the database
// and configuration files on disk.  The script will only run if it can't find
// a ciniki-api.ini file
//


//
// Figure out where the root directory is.  This file may be symlinked
//
$ciniki_root = dirname(__FILE__);
$modules_dir = $ciniki_root . '/ciniki-api';

//
// Verify no ciniki-api.ini file
//
if( file_exists($ciniki_root . '/ciniki-api.ini') ) {
	print_page('no', 'ciniki.installer.15', 'Already installed.</p><p><a href="/manage/">Login</a>');
	exit();
}

//
// Verify no .htaccess file exists.
//
if( file_exists($ciniki_root . '/.htaccess') ) {
	print_page('no', 'ciniki.installer.14', 'Already installed.</p><p><a href="/manage/">Login</a>');
	exit();
}

//
// If they didn't post anything, display the form, otherwise run an install
//
if( !isset($_POST['database_host']) ) {
	print_page('yes', '', '');
} else {
	install($ciniki_root, $modules_dir);
}

exit();





function print_page($display_form, $err_code, $err_msg) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Ciniki Installer</title>
<link rel='stylesheet' type='text/css' href='ciniki-manage-themes/default/style.css' />
<link rel='stylesheet' type='text/css' href='ciniki-manage-themes/default/e-webkit.css' />
<link rel='stylesheet' type='text/css' href='ciniki-manage-themes/default/s-normal.css' />
<link rel='stylesheet' type='text/css' href='ciniki-manage-themes/default/d-generic.css' />
<link rel='stylesheet' type='text/css' href='ciniki-manage-themes/default/s-normal-webkit.css' />
<link rel='stylesheet' type='text/css' href='ciniki-manage-themes/default/colors.css' />

</head>
<body id="m_body">
<div id='m_container' class="s-normal">
	<table id="mc_header" class="headerbar" cellpadding="0" cellspacing="0">
		<tr>
		<td id="mc_home_button" style="display:none;"><img src="ciniki-manage-themes/default/img/home_button.png"/></td>
		<td id="mc_title" class="title">Ciniki Installer</td>
		<td id="mc_help_button" style="display:none;"><img src="ciniki-manage-themes/default/img/help_button.png"/></td>
		</tr>
	</table>
	<div id="mc_content">
	<div id="mc_content_scroller" class="scrollable">
	<div id="mc_apps">
		<div id="mapp_installer" class="mapp">
			<div id="mapp_installer_content" class="panel">
				<div class="medium">
				<?php
					if( $err_code == 'installed' ) {
						print "<h2 class=''>Installed</h2><div class='bordered error'><p>Ciniki installed and configured, you can now login and finished installing the database.  </p><p><a href='/manage'>Login</a></p></div>";

					}
					elseif( $err_code != '' ) {
						print "<h2 class='error'>Error</h2><div class='bordered error'><p>Error $err_code - $err_msg</p></div>";
					}
				?>
				<?php if( $display_form == 'yes' ) { ?>
					<form id="mapp_installer_form" method="POST" name="mapp_installer_form">
						<h2>Database</h2>
						<table class="list noheader form outline" cellspacing='0' cellpadding='0'>
							<tbody>
							<tr class="textfield"><td class="label"><label for="database_host">Host</label></td>
								<td class="input"><input id="database_host" name="database_host" type="text"/></td></tr>
							<tr class="textfield"><td class="label"><label for="database_username">User</label></td>
								<td class="input"><input type="text" id="database_username" name="database_username" /></td></tr>
							<tr class="textfield"><td class="label"><label for="database_password">Password</label></td>
								<td class="input"><input type="password" id="database_password" name="database_password" /></td></tr>
							<tr class="textfield"><td class="label"><label for="database_name">Name</label></td>
								<td class="input"><input type="text" id="database_name" name="database_name" /></td></tr>
							</tbody>
						</table>
						<h2>System Adminstrator</h2>
						<table class="list noheader form outline" cellspacing='0' cellpadding='0'>
							<tbody>
							<tr class="textfield"><td class="label"><label for="admin_email">Email</label></td>
								<td class="input"><input type="email" id="admin_email" name="admin_email" /></td></tr>
							<tr class="textfield"><td class="label"><label for="admin_username">Username</label></td>
								<td class="input"><input type="text" id="admin_username" name="admin_username" /></td></tr>
							<tr class="textfield"><td class="label"><label for="admin_password">Password</label></td>
								<td class="input"><input type="password" id="admin_password" name="admin_password" /></td></tr>
							<tr class="textfield"><td class="label"><label for="admin_firstname">First</label></td>
								<td class="input"><input type="text" id="admin_firstname" name="admin_firstname" /></td></tr>
							<tr class="textfield"><td class="label"><label for="admin_lastname">Last</label></td>
								<td class="input"><input type="text" id="admin_lastname" name="admin_lastname" /></td></tr>
							<tr class="textfield"><td class="label"><label for="admin_display_name">Display</label></td>
								<td class="input"><input type="text" id="admin_display_name" name="admin_display_name" placeholder=""/></td></tr>
							</tbody>
						</table>
						<h2>Master Business</h2>
						<div class="section">
						<table class="list noheader form outline" cellspacing='0' cellpadding='0'>
							<tbody>
							<tr class="textfield"><td class="label"><label for="master_name">Name</label></td>
								<td class="input"><input type="text" id="master_name" name="master_name" /></td></tr>
							<tr class="textfield"><td class="label"><label for="system_email" >System Email</label></td>
								<td class="input"><input type="text" id="system_email" name="system_email" placeholder="For sending alerts and notifications"/></td></tr>
							<tr class="textfield"><td class="label"><label for="system_email_name">System Name</label></td>
								<td class="input"><input type="text" id="system_email_name" name="system_email_name" value="Ciniki Robot"/></td></tr>
							</tbody>
						</table>
						</div>
						<div style="text-align:center;">
							<input type="submit" value=" Install " class="button">
						</div>
					</form>
				<?php } ?>
			</div>
			</div>
		</div>
	</div>
	</div>
	</div>
</div>
</body>
</html>
<?php
}


//
// Install Procedure
//

function install($ciniki_root, $modules_dir) {

	$database_host = $_POST['database_host'];
	$database_username = $_POST['database_username'];
	$database_password = $_POST['database_password'];
	$database_name = $_POST['database_name'];
	$admin_email = $_POST['admin_email'];
	$admin_username = $_POST['admin_username'];
	$admin_password = $_POST['admin_password'];
	$admin_firstname = $_POST['admin_firstname'];
	$admin_lastname = $_POST['admin_lastname'];
	$admin_display_name = $_POST['admin_display_name'];
	$master_name = $_POST['master_name'];
	$system_email = $_POST['system_email'];
	$system_email_name = $_POST['system_email_name'];

	$manage_api_key = md5(date('Y-m-d-H-i-s') . rand());

	//
	// Build the config file
	//
	$config = array('core'=>array(), 'users'=>array());
	$config['core']['root_dir'] = $ciniki_root;
	$config['core']['modules_dir'] = $ciniki_root . '/ciniki-api';
	$config['core']['lib_dir'] = $ciniki_root . '/ciniki-lib';

	// Default session timeout to 30 minutes
	$config['core']['session_timeout'] = 1800;

	// Database information
	$config['core']['database'] = $database_name;
	$config['core']['database.names'] = $database_name;
	$config['core']["database.$database_name.hostname"] = $database_host;
	$config['core']["database.$database_name.username"] = $database_username;
	$config['core']["database.$database_name.password"] = $database_password;
	$config['core']["database.$database_name.database"] = $database_name;

	// The master business ID will be set later on, once information is in database
	$config['core']['master_business_id'] = 0;

	$config['core']['alerts.notify'] = $admin_email;
	$config['core']['system.email'] = $system_email;
	$config['core']['system.email.name'] = $system_email_name;

	// Configure packages and modules 
	$config['core']['packages'] = 'ciniki';

	$config['core']['sync.name'] = $master_name;
	$config['core']['sync.url'] = "https://" . $_SERVER['SERVER_NAME'] . "/" . preg_replace('/^\//', '', dirname($_SERVER['REQUEST_URI']) . "ciniki-sync.php");

	// Configure users module settings for password recovery
	$config['users']['password.forgot.notify'] = $admin_email;
	$config['users']['password.forgot.url'] = "https://" . $_SERVER['SERVER_NAME'] . "/" . preg_replace('/^\/$/', '', dirname($_SERVER['REQUEST_URI']));


	//
	// Setup ciniki variable, just like ciniki-api/core/private/init.php script, but we
	// can't load that script as the config file isn't on disk, and the user is not 
	// in the database
	//
	$ciniki = array('config'=>$config);
	$ciniki['request'] = array('api_key'=>$manage_api_key, 'auth_token'=>'', 'method'=>'', 'args'=>array());

	//
	// Initialize the database connection
	//
	require_once($modules_dir . '/core/private/loadMethod.php');
	ciniki_core_loadMethod($ciniki, 'ciniki', 'core', 'private', 'dbInit');
	$rc = ciniki_core_dbInit($ciniki);
	if( $rc['stat'] != 'ok' ) {
		print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to connect to the database '$database_name', please check your database connection settings and try again.<br/><br/>" . $rc['err']['msg']);
		exit();
	}

	//
	// Run the upgrade script, which will upgrade any existing tables,
	// so we don't have to check first if they exist.
	// 
	ciniki_core_loadMethod($ciniki, 'ciniki', 'core', 'private', 'dbUpgradeTables');
	$rc = ciniki_core_dbUpgradeTables($ciniki);
	if( $rc['stat'] != 'ok' ) {
		print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to connect to the database '$database_name', please check your database connection settings and try again.<br/><br/>" . $rc['err']['msg']);
		exit();
	}

	// FIXME: Add code to upgrade other packages databases


	//
	// Check if any data exists in the database
	//
	$strsql = "SELECT 'num_rows', COUNT(*) FROM ciniki_core_api_keys, ciniki_users";
	ciniki_core_loadMethod($ciniki, 'ciniki', 'core', 'private', 'dbCount');
	$rc = ciniki_core_dbCount($ciniki, $strsql, 'core', 'count');
	if( $rc['stat'] != 'ok' ) {
		print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to check for existing data<br/><br/>" . $rc['err']['msg']);
		exit();
	}
	if( $rc['count']['num_rows'] != 0 ) {
		print_page('yes', 'ciniki.installer.96', "Failed to check for existing data.");
		exit();
	}
	$db_exists = 'no';

	//
	// FIXME: Check if api_key already exists for ciniki-manage, and add if doesn't
	//



	//
	// FIXME: Add the user, if they don't already exist
	//

	//
	// Start a new database transaction
	//
	ciniki_core_loadMethod($ciniki, 'ciniki', 'core', 'private', 'dbTransactionStart');
	ciniki_core_loadMethod($ciniki, 'ciniki', 'core', 'private', 'dbTransactionRollback');
	ciniki_core_loadMethod($ciniki, 'ciniki', 'core', 'private', 'dbTransactionCommit');
	ciniki_core_loadMethod($ciniki, 'ciniki', 'core', 'private', 'dbInsert');
	$rc = ciniki_core_dbTransactionStart($ciniki, 'core');
	if( $rc['stat'] != 'ok' ) {
		print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
		exit();
	}

	if( $db_exists == 'no' ) {
		//
		// Add the user
		//
		$strsql = "INSERT INTO ciniki_users (id, email, username, password, avatar_id, perms, status, timeout, "
			. "firstname, lastname, display_name, date_added, last_updated) VALUES ( "
			. "'1', '$admin_email', '$admin_username', SHA1('$admin_password'), 0, 1, 1, 0, "
			. "'$admin_firstname', '$admin_lastname', '$admin_display_name', UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$rc = ciniki_core_dbInsert($ciniki, $strsql, 'users');
		if( $rc['stat'] != 'ok' ) {
			ciniki_core_dbTransactionRollback($ciniki, 'core');
			print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
			exit();
		}

		//
		// Add the master business, if it doesn't already exist
		//
		$strsql = "INSERT INTO ciniki_businesses (id, uuid, modules, name, tagline, description, status, date_added, last_updated) VALUES ("
			. "'1', UUID(), 0, '$master_name', '', '', 1, UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$rc = ciniki_core_dbInsert($ciniki, $strsql, 'businesses');
		if( $rc['stat'] != 'ok' ) {
			ciniki_core_dbTransactionRollback($ciniki, 'core');
			print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
			exit();
		}
		$config['core']['master_business_id'] = 1;
		$config['web']['master.domain'] = $_SERVER['HTTP_HOST'];

		//
		// Add sysadmin as the owner of the master business
		//
		$strsql = "INSERT INTO ciniki_business_users (business_id, user_id, package, permission_group, status, date_added, last_updated) VALUES ("
			. "'1', '1', 'ciniki', 'owners', '1', UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$rc = ciniki_core_dbInsert($ciniki, $strsql, 'businesses');
		if( $rc['stat'] != 'ok' ) {
			ciniki_core_dbTransactionRollback($ciniki, 'core');
			print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
			exit();
		}

		//
		// Enable modules: bugs, features, questions for master business
		//
		$strsql = "INSERT INTO ciniki_business_modules (business_id, package, module, status, ruleset, date_added, last_updated) "
			. "VALUES ('1', 'ciniki', 'bugs', 1, 'all_customers', UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$rc = ciniki_core_dbInsert($ciniki, $strsql, 'businesses');
		if( $rc['stat'] != 'ok' ) {
			ciniki_core_dbTransactionRollback($ciniki, 'core');
			print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
			exit();
		}

		$strsql = "INSERT INTO ciniki_business_modules (business_id, package, module, status, ruleset, date_added, last_updated) "
			. "VALUES ('1', 'ciniki', 'features', 1, 'all_customers', UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$rc = ciniki_core_dbInsert($ciniki, $strsql, 'businesses');
		if( $rc['stat'] != 'ok' ) {
			ciniki_core_dbTransactionRollback($ciniki, 'core');
			print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
			exit();
		}

		$strsql = "INSERT INTO ciniki_business_modules (business_id, package, module, status, ruleset, date_added, last_updated) "
			. "VALUES ('1', 'ciniki', 'questions', 1, 'all_customers', UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$rc = ciniki_core_dbInsert($ciniki, $strsql, 'businesses');
		if( $rc['stat'] != 'ok' ) {
			ciniki_core_dbTransactionRollback($ciniki, 'core');
			print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
			exit();
		}

		//
		// Setup notification settings
		//
		$strsql = "INSERT INTO ciniki_bug_settings (business_id, detail_key, detail_value, date_added, last_updated) "
			. "VALUES ('1', 'add.notify.owners', 'yes', UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$rc = ciniki_core_dbInsert($ciniki, $strsql, 'bugs');
		if( $rc['stat'] != 'ok' ) {
			ciniki_core_dbTransactionRollback($ciniki, 'core');
			print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
			exit();
		}

		$strsql = "INSERT INTO ciniki_feature_settings (business_id, detail_key, detail_value, date_added, last_updated) "
			. "VALUES ('1', 'add.notify.owners', 'yes', UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$rc = ciniki_core_dbInsert($ciniki, $strsql, 'features');
		if( $rc['stat'] != 'ok' ) {
			ciniki_core_dbTransactionRollback($ciniki, 'core');
			print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
			exit();
		}

		$strsql = "INSERT INTO ciniki_question_settings (business_id, detail_key, detail_value, date_added, last_updated) "
			. "VALUES ('1', 'add.notify.owners', 'yes', UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$rc = ciniki_core_dbInsert($ciniki, $strsql, 'questions');
		if( $rc['stat'] != 'ok' ) {
			ciniki_core_dbTransactionRollback($ciniki, 'core');
			print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
			exit();
		}

		//
		// Add the api key for the UI
		//
		$strsql = "INSERT INTO ciniki_core_api_keys (api_key, status, perms, user_id, appname, notes, "
			. "last_access, expiry_date, date_added, last_updated) VALUES ("
			. "'$manage_api_key', 1, 0, 2, 'ciniki-manage', '', 0, 0, UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$rc = ciniki_core_dbInsert($ciniki, $strsql, 'core');
		if( $rc['stat'] != 'ok' ) {
			ciniki_core_dbTransactionRollback($ciniki, 'core');
			print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
			exit();
		}
	}

	// 
	// Save ciniki-api config file
	//
	$new_config = "";
	foreach($config as $module => $settings) {
		$new_config .= "[$module]\n";
		foreach($settings as $key => $value) {
			$new_config .= "	$key = $value\n";
		}
		$new_config .= "\n";
	}
	$num_bytes = file_put_contents($ciniki_root . '/ciniki-api.ini', $new_config);
	if( $num_bytes == false || $num_bytes < strlen($new_config)) {
		unlink($ciniki_root . '/ciniki-api.ini');
		ciniki_core_dbTransactionRollback($ciniki, 'core');
		print_page('yes', 'ciniki.installer.99', "Unable to write configuration, please check your website settings.");
		exit();
	}

	//
	// Save ciniki-manage config file
	//
	$manage_config = ""
		. "[core]\n"
		. "manage_root_url = /ciniki-manage\n"
		. "themes_root_url = " . preg_replace('/^\/$/', '', dirname($_SERVER['REQUEST_URI'])) . "/ciniki-manage-themes\n"
		. "json_url = " . preg_replace('/^\/$/', '', dirname($_SERVER['REQUEST_URI'])) . "/ciniki-json.php\n"
		. "api_key = $manage_api_key\n"
		. "site_title = '" . $master_name . "'\n"
		. "";

	$num_bytes = file_put_contents($ciniki_root . '/ciniki-manage.ini', $manage_config);
	if( $num_bytes == false || $num_bytes < strlen($manage_config)) {
		unlink($ciniki_root . '/ciniki-api.ini');
		unlink($ciniki_root . '/ciniki-manage.ini');
		ciniki_core_dbTransactionRollback($ciniki, 'core');
		print_page('yes', 'ciniki.installer.98', "Unable to write configuration, please check your website settings.");
		exit();
	}

	//
	// Save the .htaccess file
	//
	$htaccess = ""
		. "# Block evil spam bots\n"
		. "# List found on : http://perishablepress.com/press/2006/01/10/stupid-htaccess-tricks/#sec1\n"
		. "RewriteBase /\n"
		. "RewriteCond %{HTTP_USER_AGENT} ^Anarchie [OR]\n"
		. "RewriteCond %{HTTP_USER_AGENT} ^ASPSeek [OR]\n"
		. "RewriteCond %{HTTP_USER_AGENT} ^attach [OR]\n"
		. "RewriteCond %{HTTP_USER_AGENT} ^autoemailspider [OR]\n"
		. "RewriteCond %{HTTP_USER_AGENT} ^Xaldon\ WebSpider [OR]\n"
		. "RewriteCond %{HTTP_USER_AGENT} ^Xenu [OR]\n"
		. "RewriteCond %{HTTP_USER_AGENT} ^Zeus.*Webster [OR]\n"
		. "RewriteCond %{HTTP_USER_AGENT} ^Zeus\n"
		. "RewriteRule ^.* - [F,L]\n"
		. "\n"
		. "# Block access to internal code\n"
		. "\n"
		. "Options All -Indexes\n"
		. "RewriteEngine On\n"
		. '# Allow access to artweb themes and cache, everything is considered public\n'
		. 'RewriteRule ^ciniki-web-layouts/(.*\.)(css|js)$ ciniki-api/web/layouts/$1$2 [L]\n'
		. 'RewriteRule ^ciniki-web-themes/(.*\.)(css|js|html)$ ciniki-api/web/themes/$1$2 [L]\n'
		. 'RewriteRule ^ciniki-web-cache/(.*\.)(jpg)$ ciniki-api/web/cache/$1$2 [L]\n'
		. "RewriteBase /\n"
		. "\n"
		. "AddType text/cache-manifest .manifest\n"
		. "\n"
		. "RewriteCond %{REQUEST_FILENAME} -f [OR]\n"
		. "RewriteCond %{REQUEST_FILENAME} -d\n"
		. "RewriteRule ^(manage/)$ ciniki-manage.php [L]                                            # allow all ciniki-manage\n"
		. "RewriteRule ^(manage)$ ciniki-manage.php [L]                                             # allow all ciniki-manage\n"
		. "RewriteRule ^(ciniki-manage/.*)$ $1 [L]                                                  # Allow manage content\n"
		. "RewriteRule ^(ciniki-manage-themes/.*)$ $1 [L]                                           # Allow manage-theme content\n"
		. "RewriteRule ^(ciniki-login|ciniki-sync|ciniki-json|ciniki-rest|index|ciniki-manage).php$ $1.php [L]  # allow entrance php files\n"
		. "RewriteRule  ^([_0-9a-zA-Z-]+/)(.*\.php)$ index.php [L]                                  # Redirect all other php requests to index\n"
		. "RewriteRule . index.php [L]                                                              # Redirect all other requests to index\n"
		. "\n"
		. "php_value post_max_size 20M\n"
		. "php_value upload_max_filesize 20M\n"
		. "php_value magic_quotes 0\n"
		. "php_flag magic_quotes off\n"
		. "php_value magic_quotes_gpc 0\n"
		. "php_flag magic_quotes_gpc off\n"
		. "";

	$num_bytes = file_put_contents($ciniki_root . '/.htaccess', $htaccess);
	if( $num_bytes == false || $num_bytes < strlen($htaccess)) {
		unlink($ciniki_root . '/ciniki-api.ini');
		unlink($ciniki_root . '/ciniki-manage.ini');
		unlink($ciniki_root . '/.htaccess');
		ciniki_core_dbTransactionRollback($ciniki, 'core');
		print_page('yes', 'ciniki.installer.97', "Unable to write configuration, please check your website settings.");
		exit();
	}

	//
	// Create symlinks into scripts
	//
	symlink($ciniki_root . '/ciniki-api/core/scripts/sync.php', $ciniki_root . '/ciniki-sync.php');
	symlink($ciniki_root . '/ciniki-api/core/scripts/rest.php', $ciniki_root . '/ciniki-rest.php');
	symlink($ciniki_root . '/ciniki-api/core/scripts/json.php', $ciniki_root . '/ciniki-json.php');
	symlink($ciniki_root . '/ciniki-manage/core/scripts/manage.php', $ciniki_root . '/ciniki-manage.php');
	symlink($ciniki_root . '/ciniki-manage/core/scripts/login.php', $ciniki_root . '/ciniki-login.php');

	$rc = ciniki_core_dbTransactionCommit($ciniki, 'core');
	if( $rc['stat'] != 'ok' ) {
		ciniki_core_dbTransactionRollback($ciniki, 'core');
		unlink($ciniki_root . '/ciniki-api.ini');
		unlink($ciniki_root . '/ciniki-manage.ini');
		unlink($ciniki_root . '/.htaccess');
		unlink($ciniki_root . '/ciniki-rest.php');		
		unlink($ciniki_root . '/ciniki-json.php');		
		unlink($ciniki_root . '/ciniki-manage.php');		
		unlink($ciniki_root . '/ciniki-login.php');		
		print_page('yes', 'ciniki.' . $rc['err']['code'], "Failed to setup database<br/><br/>" . $rc['err']['msg']);
		exit();
	}

	print_page('no', 'installed', '');
}

?>
