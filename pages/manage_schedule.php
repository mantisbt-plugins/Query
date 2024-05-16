<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( lang_get( 'plugin_format_title' ) );
layout_page_begin();
print_manage_menu();
$link1	= plugin_page('config');
$link2	= plugin_page('manage_schedule');
$link3	= plugin_page('manage_query');
?>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 
<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo  lang_get( 'plugin_query_name' ).': ' . lang_get( 'manage_schedule' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<table class="table table-bordered table-condensed table-striped"> 
<form action="<?php echo plugin_page( 'schedule_add' ) ?>" method="post">
<tr>
<td class="form-title" colspan="6" >
<?php print_link_button( $link1, lang_get( 'plugin_query_config' ) );?>
&nbsp;
<?php print_link_button( $link3, lang_get( 'plugin_query_manage' ) );?>
</td>
</tr>

<tr class="row-category">
<td><div align="center"><b><?php echo lang_get( 'schedule_desc' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'query_title2' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'schedule_filter' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'schedule_target' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'schedule_frequency' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'query_act' ); ?></b></div></td>



<?php
$sql = "select b.*,query_name from {plugin_Query_definitions} as a,{plugin_Query_schedule} as b  where  a.query_id=b.query_id order by schedule_desc";
$result = db_query($sql);
while ($row = db_fetch_array($result)) {
	?>
	<tr>
	<td><div align="center"><?php  echo html_entity_decode($row["schedule_desc"]); ?>	</div></td>
	<td><div align="center">	<?php  echo html_entity_decode($row["query_name"]); ?>	</div></td>
	<td><div align="center"><?php echo $row["schedule_filter"]; ?></div></td>
	<td><div align="center"><?PHP	echo html_entity_decode($row["target"]);?>	</div></td>
	<td><div align="center"><?php echo $row["frequency"]; ?></div></td>
	<td class="center" colspan="3"><div>
	
	<?php
	$link6 = "plugin.php?page=Query/edit_schedule.php&update_id=";
	$link6 .= $row["schedule_id"]  ;
	$link7 = "plugin.php?page=Query/schedule_delete.php&delete_id=";
	$link7 .= $row["schedule_id"]  ;
	print_link_button( $link6, lang_get( 'query_edit' ), 'btn-xs' );
	print_link_button( $link7, lang_get( 'query_delete' ), 'btn-xs');
	?>

	</div></td>
	</tr>
	<?PHP
}
?>
<tr >
<td class="center">
<input type="text" name="schedule_desc" size="30" maxlength="100"  >
</td>

<td class="center">
<select <?php echo helper_get_tab_index() ?> name="query_id">
<?PHP 
$sql1 = "select * from {plugin_Query_definitions} order by query_name";
$result1 = db_query($sql1);
while ($row1 = db_fetch_array($result1)) {
	echo '<option value=';
	echo $row1['query_id'];
	echo '>';
	echo $row1['query_name'];
	echo '</option>';
}
?>
</select> 
</td>
<td class="center">
<textarea name="schedule_filter" rows="3" cols="30"></textarea>
</td>
<td class="center">
<textarea name="schedule_target" rows="3" cols="30"></textarea>
</td>

<td class="center">
<select <?php echo helper_get_tab_index() ?> name="schedule_type">
<option value="E" >Every Day</option> 
<option value="D" >Weekdays</option> 
<option value="W" >Weekly</option> 
<option value="M" >Monthly</option> 
</select>
</td> 

<td class="center" colspan="3">
<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo lang_get( 'add_query' ) ?>" />
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
layout_page_end(  );
