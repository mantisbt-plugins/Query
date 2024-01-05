<?php
// authenticate
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
// Read results
$f_manage_threshold		= gpc_get_int( 'manage_threshold', [ADMINISTRATOR] );
$f_execute_threshold	= gpc_get_int( 'execute_threshold', [DEVELOPER] );
$f_download_location	= gpc_get_string( 'download_location', 'c:/temp/' );
$f_delete_file			= gpc_get_int( 'delete_file', ON );
$f_from_address			= gpc_get_string( 'from_address', 'me@mydomain.com/' );
$f_separator			= gpc_get_string( 'separator', ',' );
// update results
plugin_config_set( 'manage_threshold', $f_manage_threshold );
plugin_config_set( 'execute_threshold', $f_execute_threshold );
plugin_config_set( 'download_location', $f_download_location );
plugin_config_set( 'delete_file', $f_delete_file );
plugin_config_set( 'from_address', $f_from_address );
plugin_config_set( 'separator', $f_separator );
// redirect 
print_header_redirect( plugin_page( 'config',TRUE ) );