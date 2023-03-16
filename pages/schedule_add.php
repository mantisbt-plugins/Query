<?PHP
require_once( 'core.php' );
//require_once( '../../../core.php' );
$schedule	= $_REQUEST['schedule_desc'];
$query_id	= $_REQUEST['query_id'];
$filter		= db_prepare_string($_REQUEST['schedule_filter']);
$target		= db_prepare_string($_REQUEST['schedule_target']);
$frequency	= db_prepare_string($_REQUEST['schedule_type']);
if (empty($schedule)) {
	trigger_error( ERROR_SCHEDULE_EMPTY_TITLE, ERROR );
}
if (empty($target)) {
	trigger_error( ERROR_SCHEDULE_EMPTY_TARGET, ERROR );
}
if ($query_id<1) {
	trigger_error( ERROR_SCHEDULE_EMPTY_QUERY, ERROR );
}

$q3_table		= plugin_table('schedule','Query');
$sql = "INSERT INTO $q3_table ( schedule_desc,query_id, schedule_filter, target, frequency ) 	VALUES (  '$schedule','$query_id', '$filter','$target','$frequency')";

if(!db_query($sql)){ 
	trigger_error( ERROR_DB_QUERY_FAILED, ERROR );
}
print_header_redirect( 'plugin.php?page=Query/manage_schedule' );