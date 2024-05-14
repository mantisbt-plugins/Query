<?PHP
$update_id			=  $_REQUEST['update_id'];
layout_page_header( lang_get( 'plugin_format_title' ) );
layout_page_begin();
print_manage_menu();
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
$sql = "select * from {plugin_Query_definitions} where query_id=$update_id";
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
<form name="editquery" method="post" action="plugin.php?page=Query/edit_query2.php">
<input type="hidden" name="update_id" value="<?php echo $update_id;  ?>">
<strong><?php echo lang_get( 'query_update_comments' ) ?>: </strong>
<?php echo $row['query_name'];?>
<br>
<?php echo $row['query_desc'];?>
<br>
<?PHP
if ($type<>"Q"){
	?>
	<tr>
	<td width="50%">
	<br>
	<?php echo lang_get( 'query_tip_7' ) ?>
	</td>
	<td><div ><b><?php echo lang_get( 'query_script' ) ?></b><br>
	<textarea name="query_script" rows="20" cols="75"><?php echo $row['query_script'];  ?></textarea>
	</div>
	</td>
	</tr>
	<?PHP
} else{
	?>
	<tr>
	<td width="50%">
	<br>
	<?php echo lang_get( 'query_tip_1' ) ?>
	</td>
	<td><div ><b><?php echo lang_get( 'query_tables' ) ?><b><br>
	<textarea name="query_tables" rows="3" cols="50"><?php echo $row['query_tables'];  ?></textarea>
	</div></td></tr>
	<tr>
	<td width="50%">
	<br>
	<?php echo lang_get( 'query_tip_2' ) ?>
	</td>
	<td><div ><b><?php echo lang_get( 'query_joins' ) ?></b><br>
	<textarea name="query_joins" rows="3" cols="50"><?php echo $row['query_joins'];  ?></textarea>
	</div></td></tr>
	<tr>
	<td width="50%">
	<br>
	<?php echo lang_get( 'query_tip_3' ) ?>
	</td>
	<td><div ><b><?php echo lang_get( 'query_fields' ) ?></b><br>
	<textarea name="query_fields" rows="3" cols="50"><?php echo $row['query_fields'];  ?></textarea>
	</div></td></tr>
	<tr>
	<td width="50%">
	<br>
	<?php echo lang_get( 'query_tip_4' ) ?>
	</td>
	<td><div ><b><?php echo lang_get( 'query_filters' ) ?></b><br>
	<textarea name="query_filters" rows="3" cols="50"><?php echo $row['query_filter'];  ?></textarea>
	</div></td></tr>
	<tr>
	<br>
	<td width="50%">
	<br>
	<?php echo lang_get( 'query_tip_5' ) ?>
	</td>
	<td><div ><b><?php echo lang_get( 'query_order' ) ?></b><br>
	<textarea name="query_order" rows="3" cols="50"><?php echo $row['query_order'];  ?></textarea>
	</div></td></tr>
	<tr>
	<td width="50%">
	<?php echo lang_get( 'query_tip_6' ) ?>
	</td>
	<td><div ><b><?php echo lang_get( 'query_group' ) ?></b><br>
	<textarea name="query_group" rows="3" cols="50"><?php echo $row['query_group'];  ?></textarea>
	</div></td></tr>
	<?PHP
}
?>
<tr>
<td><input name="Update" type="submit" value="Update"></td>
<td align="right"><a href="plugin.php?page=Query/manage_query">Cancel<a/></td>
</tr>
</table></form>
</div>
</div>
</div>
</div>
</div>
</div>
<?php
layout_page_end();