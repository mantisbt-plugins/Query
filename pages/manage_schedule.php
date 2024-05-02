<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( lang_get( 'plugin_format_title' ) );
layout_page_begin();
print_manage_menu();
$link=plugin_page('config');
$link2=plugin_page('manage_query');
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
</tr>
<tr >
<td class="category" colspan="6">
</td>
</tr>

<tr>
<td class="form-title" colspan="6" >
<a href="<?php echo $link ?>"><?php echo lang_get( 'plugin_query_config' ) ?></a>
<==>
<a href="<?php echo $link2 ?>"><?php echo lang_get( 'plugin_query_manage' ) ?></a>
</td>
</tr>

<tr class="row-category">
<td><div align="center"><?php echo lang_get( 'schedule_desc' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'query_title2' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'schedule_filter' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'schedule_target' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'schedule_frequency' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'query_act' ); ?></div></td>

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
<input type="submit" class="button" value="<?php echo lang_get( 'add_query' ) ?>" />
</td>
</tr>

<form>

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
	<td><div>
	<a href="plugins/Query/pages/edit_schedule.php?update_id=<?php echo $row["schedule_id"]; ?>"><?php echo lang_get( 'query_edit' ) ?></a>
	<a href="plugins/Query/pages/schedule_delete.php?delete_id=<?php echo $row["schedule_id"]; ?>"><?php echo lang_get( 'query_delete' ) ?></a>
	</div></td>
	</tr>
	<?PHP
}
?>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<?php
layout_page_end(  );
