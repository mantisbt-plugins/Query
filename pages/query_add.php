<?PHP
require_once( 'core.php' );
//require_once( '../../../core.php' );
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
$q1_table		= plugin_table('definitions','Query');
$sql = "INSERT INTO $q1_table ( query_name,query_type,query_lvl,query_desc ) 	VALUES (  '$query','$type', '$lvl','$desc')";

if(!db_query($sql)){ 
	trigger_error( ERROR_DB_QUERY_FAILED, ERROR );
}
print_header_redirect( 'plugin.php?page=Query/manage_query' );
