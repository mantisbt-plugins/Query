<?PHP
$reqVar = '_' . $_SERVER['REQUEST_METHOD'];
$form_vars = $$reqVar;
$delete_id = $form_vars['delete_id'] ;
require_once( '../../../core.php' );
$q3_table		= plugin_table('schedule','Query');
$q1_table		= plugin_table('definitions','Query');
// first check if this entry is already in use
$query= "select * from $q3_table where query_id= $delete_id";
$result = db_query($query);
$res2=db_num_rows($result);
if ($res2 >0){
	trigger_error( ERROR_DELETE_QUERY, ERROR );
} else {
	# Deleting category
	$query = "DELETE FROM $q1_table WHERE query_id = $delete_id";        
	db_query($query);
}
print_header_redirect( '../../../plugin.php?page=Query/manage_query' );