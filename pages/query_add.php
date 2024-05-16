<?PHP
$query	= $_REQUEST['query_name'];
$type	= $_REQUEST['query_type'];
$desc	= $_REQUEST['query_desc'];
if (empty($query)) {
	plugin_error( QueryPlugin::ERROR_QUERY_EMPTY_TITLE );
}
if (empty($desc)) {
	plugin_error( QueryPlugin::ERROR_QUERY_EMPTY_DESC );
}
// check if query name already exists
$sql = "select * from {plugin_Query_definitions} where upper(query_name) = upper('$query')";
$result = db_query( $sql );
$count = db_num_rows( $result );
if ( $count >  0 ) {
	plugin_error( QueryPlugin::ERROR_QUERY_NAME_NOT_UNIQUE );
}
// insert new query
$sql = "INSERT INTO {plugin_Query_definitions} ( query_name,query_type,query_desc ) 	VALUES (  '$query','$type', '$desc')";
$result = db_query($sql) ;

print_header_redirect( 'plugin.php?page=Query/manage_query' );
exit;