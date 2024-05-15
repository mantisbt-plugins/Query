<?PHP
$update_id	= gpc_get_int( 'update_id' );
$script		=  @$_REQUEST['query_script'] ;
$tables		=  @$_REQUEST['query_tables'];
$joins		=  @$_REQUEST['query_joins'];
$fields     =  @$_REQUEST['query_fields'];
$filters	=  @$_REQUEST['query_filters'];
$order		=  @$_REQUEST['query_order'];
$group		=  @$_REQUEST['query_group'];
$name		=  @$_REQUEST['query_name'];
$desc		=  @$_REQUEST['query_desc'];
$lvl		=  @$_REQUEST['query_lvl'];
$query_sql	=  @$_REQUEST['query_sql'];
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
	$query = 'update {plugin_Query_definitions} set query_name= ' . db_param() . ', query_desc= ' . db_param() . ', query_lvl= ' . db_param() . ',query_sql =  ' . db_param() . ', query_script = '  . db_param() . ' where query_id= ' . db_param() . '';
	$result =db_query( $query, array($name,$desc,$lvl,$sql, $script, $update_id) );
} else {
	if ( ON == plugin_config_get('build_sql' ) ) {
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
	$query = 'update {plugin_Query_definitions} set query_name= ' . db_param() . ', query_desc= ' . db_param() . ', query_lvl= ' . db_param() . ',query_sql =  ' . db_param() . ', query_script = '  . db_param() . ' , query_tables = '   . db_param() . ' , query_joins = '  . db_param() . ' , query_fields = '  . db_param() . ' , query_filter =  ' . db_param() . ' , query_order = '  . db_param() . ' , query_group =  ' . db_param() . ' where query_id = '. db_param() . ' ';
	$result =db_query( $query, array($name,$desc,$lvl,$sql, $script, $tables, $joins, $fields, $filters, $order, $group, $update_id) );
	} else {
		$sql = htmlentities($query_sql);
		$script = ' ';
		$query = 'update {plugin_Query_definitions} set query_name= ' . db_param() . ', query_desc= ' . db_param() . ', query_lvl= ' . db_param() . ',query_sql =  ' . db_param() . ', query_script = '  . db_param() . ' where query_id= ' . db_param() . '';
		$result =db_query( $query, array($name,$desc,$lvl,$sql, $script, $update_id) );
	}
}

print_header_redirect( 'plugin.php?page=Query/manage_query' );
