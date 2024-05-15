<?PHP
//require_once( 'core.php' );
$query	= $_REQUEST['query_name'];
$type	= $_REQUEST['query_type'];
$lvl	= $_REQUEST['query_lvl'];
$desc	= $_REQUEST['query_desc'];
if (empty($query)) {
	trigger_error( ERROR_QUERY_EMPTY_TITLE, ERROR );
}
if (empty($desc)) {
	trigger_error( ERROR_QUERY_EMPTY_DESC, ERROR );
}
// check if query name already exists
$sql = "select * from {plugin_Query_definitions} where upper(query_name) = upper('$query')";
$result = db_query( $sql );
$count = db_num_rows( $result );
if ( $count >  0 ) {
		trigger_error( ERROR_QUERY_NAME_NOT_UNIQUE, ERROR );
}
// insert new query
$sql = "INSERT INTO {plugin_Query_definitions} ( query_name,query_type,query_lvl,query_desc ) 	VALUES (  '$query','$type', '$lvl','$desc')";
db_query($sql);

print_header_redirect( 'plugin.php?page=Query/manage_query' );
exit;