<?PHP
$schedule	= $_REQUEST['schedule_desc'];
$query_id	= $_REQUEST['query_id'];
$filter		= $_REQUEST['schedule_filter'];
$target		= $_REQUEST['schedule_target'];
$frequency	= $_REQUEST['schedule_type'];
// check if schedule name already exists
$sql = "select * from {plugin_Query_schedule} where upper(schedule_desc) = upper('$schedule')";
$result = db_query( $sql );
$count = db_num_rows( $result );
if ( $count >  0 ) {
	plugin_error( QueryPlugin::ERROR_SCHEDULE_NAME_NOT_UNIQUE );
}
// check if additional filter ius allowed
$query="select * from {plugin_Query_definitions} where query_id=$query_id" ;
$result = db_query($query);
$row = db_fetch_array( $result );
$type = $row['query_type'];
if ( OFF == plugin_config_get( 'build_sql' )  and ( $type == 'Q' ) and ( trim( $filter ) <> '' ) ) { 
	plugin_error( QueryPlugin::ERROR_FILTER_NOT_POSSIBLE );
}
// check if required fields hold a value
if (empty($schedule)) {
	plugin_error( QueryPlugin::ERROR_SCHEDULE_EMPTY_TITLE );
}
if (empty($target)) {
	plugin_error( QueryPlugin::ERROR_SCHEDULE_EMPTY_TARGET );
}
if ($query_id<1) {
	plugin_error( QueryPlugin::ERROR_SCHEDULE_EMPTY_QUERY );
}
// save it
$sql = "INSERT INTO {plugin_Query_schedule} ( schedule_desc,query_id, schedule_filter, target, frequency ) 	VALUES (  '$schedule','$query_id', '$filter','$target','$frequency')";
db_query($sql);
// back home
print_header_redirect( 'plugin.php?page=Query/manage_schedule' );