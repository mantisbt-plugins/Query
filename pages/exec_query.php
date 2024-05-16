<?PHP
error_reporting(-1); //turn on errorreporting

# which query will we execute ?
$query_id	= $_REQUEST['id'];

# fetch query definition
$query="select * from {plugin_Query_definitions} where query_id=$query_id" ;
$result = db_query($query);
$row = db_fetch_array( $result );

# action depends on type of script
$action = $row['query_type'] ;

# do we have a vailid query\
if ( $action == 'Q' ) {
	if ( !trim( $row['query_sql'] ) <> '' ) {
		trigger_error( ERROR_QUERY_NOT_VALID, ERROR );
	}
} else {
	if ( !trim( $row['query_script'] ) <> '' ) {
		trigger_error( ERROR_QUERY_NOT_VALID, ERROR );
	}
}

# now create a unique filename
$filename = uniqid(mt_rand(), true) . '.csv';

# which field separator
$separator = config_get( 'plugin_Query_separator','Query'  );

switch($action){
	case "Q":
		# 'Q' means execute query, copy results into $content
		$query2		= html_entity_decode( $row['query_sql']);
		$result2	= db_query($query2);

		# fieldnames
		$query4 = "create temporary table temptable (";
		$query4 .= $query2;
		$query4 .= ")";
 		$result4 = db_query($query4);
		$query3 = "Show columns from temptable";
		$result3 = db_query($query3);
		$fieldlist ="";
		while ($row3 = db_fetch_array($result3)) {
			$fieldlist .= "$separator" ;
			$fieldlist .= $row3['field'];
		}
		$length = strlen($fieldlist);
		$fieldlist = substr($fieldlist, 1, ($length-1));
		$fieldar = explode(",",$fieldlist);
		$fieldcount = count($fieldar);	

		# prepare full dataset
 		$fields=0;
		$content	= '';
		while ($row2 = db_fetch_array($result2)) {
			# first count the number of fields we have (only once) 
			if ($fields == 0) {
				$fields = $fieldcount ;
				# now create the headerline
				for ( $i = 0; $i < $fields; $i++ ) {
					if ($i>0){
						$content .= "$separator" ;
					}
					$content .=  $fieldar[$i];
				}
				$content .= "\r\n";
			}
			// data output
			for ($i=0; $i < $fields;$i++){
				if ($i>0){
					$content .= "$separator" ;
				}
				$name = $fieldar[$i];
				$content .= $row2[strtolower( trim( $name ) )] ;
			}
			$content .= "\r\n";
		}

		break;
	case "S":
		# 'S' means include script which returns results into $content
		$code = html_entity_decode($row['query_script']);
		$content = eval($code);
		break;
	case "X":
		# 'X' means include script which handles whatever needs to be done
		$code =  html_entity_decode($row['query_script']);
		$content = eval($code);
		break;
}
if ($action<>'X'){
	# Dowload results as CSV
	header('Content-type: text/enriched');
	header("Content-Disposition: attachment; filename=$filename");
	echo $content;
	exit;
}
return;