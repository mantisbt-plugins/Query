<?PHP
require_once( 'core.php' );
//require_once( '../../../core.php' );
$schedule	= $_REQUEST['schedule_desc'];
$query_id	= $_REQUEST['query_id'];
$filter		= $_REQUEST['schedule_filter'];
$target		= $_REQUEST['schedule_target'];
$frequency	= $_REQUEST['schedule_type'];
if (empty($schedule)) {
	plugin_error( QueryPlugin::ERROR_SCHEDULE_EMPTY_TITLE );
}
if (empty($target)) {
	plugin_error( QueryPlugin::ERROR_SCHEDULE_EMPTY_TARGET );
}
if ($query_id<1) {
	plugin_error( QueryPlugin::ERROR_SCHEDULE_EMPTY_QUERY );
}

$sql = "INSERT INTO {plugin_Query_schedule} ( schedule_desc,query_id, schedule_filter, target, frequency ) 	VALUES (  '$schedule','$query_id', '$filter','$target','$frequency')";
db_query($sql);

print_header_redirect( 'plugin.php?page=Query/manage_schedule' );
