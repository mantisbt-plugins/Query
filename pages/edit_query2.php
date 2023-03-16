<?PHP
require_once( '../../../core.php' );
$update_id			= gpc_get_int( 'update_id' );

$script		=  db_prepare_string(htmlentities($_REQUEST['query_script'],ENT_COMPAT,'UTF-8'));
$tables		=  db_prepare_string(htmlentities($_REQUEST['query_tables'],ENT_COMPAT,'UTF-8'));
$joins		=  db_prepare_string(htmlentities($_REQUEST['query_joins'],ENT_COMPAT,'UTF-8'));
$fields     =  db_prepare_string(htmlentities($_REQUEST['query_fields'],ENT_COMPAT,'UTF-8'));
$filters	=  db_prepare_string(htmlentities($_REQUEST['query_filters'],ENT_COMPAT,'UTF-8'));
$order		=  db_prepare_string(htmlentities($_REQUEST['query_order'],ENT_COMPAT,'UTF-8'));
$group		=  db_prepare_string(htmlentities($_REQUEST['query_group'],ENT_COMPAT,'UTF-8'));

# Updating query
// get current values
$q1_table =  plugin_table('definitions','Query');
$query = "select query_type from $q1_table where query_id=$update_id";
$result = db_query($query);
$row = db_fetch_array($result);
$type = $row['query_type'];
// perform update
if ($type <>'Q'){
	$sql='';
	$query = "update $q1_table set query_sql='$sql',query_script='$script' where query_id=$update_id ";
} else {
	$script='';
	# now compose the final sql statement
	$sql = 'select ';
	$sql .= $fields;
	$sql .= ' from ';
	$sql .= $tables ;
	$where ='';
	if (!empty($joins)){
		$where .= ' where ';
		$where .= $joins;
	}	
	if (!empty($filters)){
		if (empty($where)){
			$where .= ' where ';
		} else {
			$where .= ' and ';
		}
		$where .= $filters;
	}	
	$sql .= $where;
	if (!empty($group)){
		$sql .= ' group by ';
		$sql .= $group;
	}
	if (!empty($order)){
		$sql .= ' order by ';
		$sql .= $order;
	}
	$query = "update $q1_table set query_sql='$sql',query_script='$script',query_tables='$tables',query_joins='$joins',query_fields='$fields',query_filter='$filters',query_order='$order',query_group='$group' where query_id=$update_id ";
}
if(!db_query($query)){ 
	trigger_error( ERROR_DB_QUERY_FAILED, ERROR );
}
print_header_redirect( '../../../plugin.php?page=Query/manage_query' );
