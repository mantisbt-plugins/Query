<?PHP
$reqVar = '_' . $_SERVER['REQUEST_METHOD'];
$form_vars = $$reqVar;
$delete_id = $form_vars['delete_id'] ;
// require_once( '../../../core.php' );
# Deleting category
$query = "DELETE FROM {plugin_Query_schedule} WHERE schedule_id = $delete_id";        
db_query($query);
print_header_redirect( 'plugin.php?page=Query/manage_schedule' );