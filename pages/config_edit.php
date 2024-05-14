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
$f_build_sql			= gpc_get_int( 'build_sql', ON );
$f_log_loc				= gpc_get_string( 'log_loc', '/var/log/' );
// update results
plugin_config_set( 'manage_threshold', $f_manage_threshold );
plugin_config_set( 'execute_threshold', $f_execute_threshold );
plugin_config_set( 'download_location', $f_download_location );
plugin_config_set( 'delete_file', $f_delete_file );
plugin_config_set( 'from_address', $f_from_address );
plugin_config_set( 'separator', $f_separator );
plugin_config_set( 'build_sql', $f_build_sql );
plugin_config_set( 'log_loc', $f_log_loc );
// redirect 
print_header_redirect( plugin_page( 'config',TRUE ) );