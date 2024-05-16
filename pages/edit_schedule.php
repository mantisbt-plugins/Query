<?PHP
$reqVar		= '_' . $_SERVER['REQUEST_METHOD'];
layout_page_header( lang_get( 'plugin_format_title' ) );
layout_page_begin();
print_manage_menu();
auth_reauthenticate();
$form_vars	= $$reqVar;
$update_id	= $form_vars['update_id'] ;
$sql = "select b.*,query_name, query_type from {plugin_Query_definitions} as a,{plugin_Query_schedule} as b  where  a.query_id=b.query_id and schedule_id=$update_id "; 
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
<form name="editschedule" method="post" action="edit_schedule2.php">
<center>
<strong><?php echo lang_get( 'schedule_update_comments' ) ?>: </strong>
<?php echo $row['schedule_desc'];?>
<br>
<?php echo $row['query_name'];?>
<br><br><br>
</center>
<input type="hidden" name="update_id" value="<?php echo $update_id;  ?>">
<tr>
<td width = "50%">
<b><?php echo lang_get( 'schedule_filter' ) ?></b><br>	
<?php echo lang_get( 'query_tip_8' ) ?></td>
<td>
<?php
if ( OFF == plugin_config_get( 'build_sql' )  and ( $type == 'Q' ) ) { 
	echo lang_get( 'no_schedule_filter' );
} else { 
?>
	<textarea name="schedule_filter" rows="3" cols="50"><?php echo $row['schedule_filter'];  ?></textarea>
<?php
 }	 
 ?>
</div>
</td>
</tr>
<tr>
<td width = "50%">
<b><?php echo lang_get( 'schedule_target' ) ?></b><br>
<?php echo lang_get( 'query_tip_9' ) ?></td>
<td><div >
<textarea name="schedule_target" rows="3" cols="50"><?php echo $row['target'];  ?></textarea>
</div>
</td>
</tr>
<tr>
<td><input name="Update" type="submit" class="btn btn-primary btn-white btn-round" value="Update"></td>
<td align="right">
<?PHP	
print_link_button( "plugin.php?page=Query/manage_schedule", 'Cancel' ) ;
?>
</td>
</tr>
</form>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<?php
layout_page_end();