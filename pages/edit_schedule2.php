<?PHP
require_once( '../../../core.php' );
$update_id			= gpc_get_int( 'update_id' );

$filter	= db_prepare_string(htmlentities($_REQUEST['schedule_filter'],ENT_COMPAT,'UTF-8'));
$target	= db_prepare_string(htmlentities($_REQUEST['schedule_target'],ENT_COMPAT,'UTF-8'));

# Updating schedule
$q3_table =  plugin_table('schedule','Query');
$query = "update $q3_table set schedule_filter='$filter',target='$target' where schedule_id=$update_id ";
if(!db_query($query)){ 
	trigger_error( ERROR_DB_QUERY_FAILED, ERROR );
}		
print_header_redirect( '../../../plugin.php?page=Query/manage_schedule' );
