<?PHP
require_once( '../../../core.php' );
$update_id			=  $_REQUEST['update_id'];
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
//layout_page_header( lang_get( 'plugin_format_title' ) );
//layout_page_begin();
//print_manage_menu();


$basepad=config_get('path');
$q1_table =  plugin_table('definitions','Query');
$sql = "select * from $q1_table where query_id=$update_id";
$result = db_query($sql);
$row = db_fetch_array($result);
$type = $row['query_type'];

?>
<center>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 

<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo  lang_get( 'plugin_query_name' ).': ' . lang_get( 'query_edit' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<table class="table table-bordered table-condensed table-striped"> 
<form name="editquery" method="post" action="edit_query2.php">
<input type="hidden" name="update_id" value="<?php echo $update_id;  ?>">

<strong><?php echo lang_get( 'query_update_comments' ) ?>: </strong>
<?php echo $row['query_name'];?>
<br>
<?php echo $row['query_desc'];?>
<br><br><br>
<?PHP
if ($type<>"Q"){
	?>
	<tr>
	<td><div align="center"><?php echo lang_get( 'query_script' ) ?><br>
	<textarea name="query_script" rows="20" cols="75"><?php echo $row['query_script'];  ?></textarea>
	</div>
	</td>
	</tr>
	<?PHP
} else{
	?>
	<tr>
	<td><div align="center"><?php echo lang_get( 'query_tables' ) ?><br>
	<textarea name="query_tables" rows="3" cols="50"><?php echo $row['query_tables'];  ?></textarea>
	</div></td></tr>
	<tr><td><div align="center"><?php echo lang_get( 'query_joins' ) ?><br>
	<textarea name="query_joins" rows="3" cols="50"><?php echo $row['query_joins'];  ?></textarea>
	</div></td></tr>
	<tr><td><div align="center"><?php echo lang_get( 'query_fields' ) ?><br>
	<textarea name="query_fields" rows="3" cols="50"><?php echo $row['query_fields'];  ?></textarea>
	</div></td></tr>
	<tr><td><div align="center"><?php echo lang_get( 'query_filters' ) ?><br>
	<textarea name="query_filters" rows="3" cols="50"><?php echo $row['query_filter'];  ?></textarea>
	</div></td></tr>
	<tr><td><div align="center"><?php echo lang_get( 'query_order' ) ?><br>
	<textarea name="query_order" rows="3" cols="50"><?php echo $row['query_order'];  ?></textarea>
	</div></td></tr>
	<tr><td><div align="center"><?php echo lang_get( 'query_group' ) ?><br>
	<textarea name="query_group" rows="3" cols="50"><?php echo $row['query_group'];  ?></textarea>
	</div></td></tr>
	<?PHP
}
?>
<tr>
<td><input name="Update" type="submit" value="Update"></td>
<td><a href="../../../plugin.php?page=Query/manage_query">Cancel<a/></td>
</tr></center>
</table></form>
</div>
</div>
</div>
</div>
</div>
<?php
//layout_page_end();