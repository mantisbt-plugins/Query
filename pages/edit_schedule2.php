<?PHP
$update_id			= gpc_get_int( 'update_id' );
$filter	= htmlentities($_REQUEST['schedule_filter'],ENT_COMPAT,'UTF-8');
$target	= htmlentities($_REQUEST['schedule_target'],ENT_COMPAT,'UTF-8'));
# Updating schedule
$query = "update {plugin_Query_schedule} set schedule_filter='$filter',target='$target' where schedule_id=$update_id ";
db_query($query);
print_header_redirect( 'plugin.php?page=Query/manage_schedule' );