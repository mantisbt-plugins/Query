<?PHP
//require_once( 'core.php' );
$query	= $_REQUEST['query_name'];
$type	= $_REQUEST['query_type'];
$lvl	= $_REQUEST['query_lvl'];
$desc	= db_prepare_string($_REQUEST['query_desc']);
if (empty($query)) {
	trigger_error( ERROR_QUERY_EMPTY_TITLE, ERROR );
}
if (empty($desc)) {
	trigger_error( ERROR_QUERY_EMPTY_DESC, ERROR );
}
$sql = "INSERT INTO {plugin_Query_definitions} ( query_name,query_type,query_lvl,query_desc ) 	VALUES (  '$query','$type', '$lvl','$desc')";

if(!db_query($sql)){ 
	trigger_error( ERROR_DB_QUERY_FAILED, ERROR );
}
print_header_redirect( 'plugin.php?page=Query/manage_query' );
exit;