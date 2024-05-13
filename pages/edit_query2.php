<?PHP
//require_once( '../../../core.php' );
$update_id	= gpc_get_int( 'update_id' );
$script		=  @$_REQUEST['query_script'] ;
$tables		=  @$_REQUEST['query_tables'];
$joins		=  @$_REQUEST['query_joins'];
$fields     =  @$_REQUEST['query_fields'];
$filters	=  @$_REQUEST['query_filters'];
$order		=  @$_REQUEST['query_order'];
$group		=  @$_REQUEST['query_group'];

# Updating query
// get current values
$query = "select query_type from {plugin_Query_definitions} where query_id=$update_id";
$result = db_query($query);
$row = db_fetch_array($result);
$type = $row['query_type'];
// perform update
if ($type <>'Q'){
	$sql='';
	$script = htmlentities($script);
	$query = "update {plugin_Query_definitions} set query_sql='$sql',query_script='$script' where query_id=$update_id ";
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
	$query = "update {plugin_Query_definitions} set query_sql='$sql',query_script='$script',query_tables='$tables',query_joins='$joins',query_fields='$fields',query_filter='$filters',query_order='$order',query_group='$group' where query_id=$update_id ";
}
if(!db_query($query)){ 
	trigger_error( ERROR_DB_QUERY_FAILED, ERROR );
}
print_header_redirect( 'plugin.php?page=Query/manage_query' );
