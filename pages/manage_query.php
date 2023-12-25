<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( lang_get( 'plugin_format_title' ) );
layout_page_begin();
print_manage_menu();
$link=plugin_page('config');
$link2=plugin_page('manage_schedule');
?>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 

<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo  lang_get( 'plugin_query_name' ).': ' . lang_get( 'plugin_query_manage' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<table class="table table-bordered table-condensed table-striped"> 


<form action="<?php echo plugin_page( 'query_add' ) ?>" method="post">


<tr >
<td class="category" colspan="5">
</td>
</tr>
<br>
<tr>
<td class="form-title" colspan="4" >
<a href="<?php echo $link ?>"><?php echo lang_get( 'plugin_query_config' ) ?></a>
<==>
<a href="<?php echo $link2 ?>"><?php echo lang_get( 'manage_schedule' ) ?></a>
</td>
</tr>

<tr class="row-category">
<td><div align="center"><?php echo lang_get( 'query_title' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'query_type' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'query_lvl' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'query_desc' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'query_act' ); ?></div></td>

<tr >
<td class="center">
<input type="text" name="query_name" size="40" maxlength="100"  >
</td>

<td class="center">
<select <?php echo helper_get_tab_index() ?> name="query_type">
<option value="Q" >Query</option> 
<option value="S" >Script</option> 
<option value="X" >eXtended</option> 
</select>
</td>
<td class="center">
<select <?php echo helper_get_tab_index() ?> name="query_lvl">
<option value="U" >User</option> 
<option value="A" >Admin only</option> 
</select>
</td>

<td class="center">
<textarea name="query_desc" rows="3" cols="40"></textarea>
</td>
<td class="center" colspan="3">
<input type="submit" class="button" value="<?php echo lang_get( 'add_query' ) ?>" />
</td>
</tr>

</form>

<?php
$sql = "select query_id,query_name,query_desc,query_type,query_lvl from {plugin_Query_definitions} order by query_name";
$result = db_query($sql);
while ($row = db_fetch_array($result)) {
	?>
	<tr>
	<td><div align="center"><?php  echo html_entity_decode($row["query_name"]); ?>	</div></td>
	<td><div align="center"><?php echo $row["query_type"]; ?></div></td>
	<td><div align="center"><?php echo $row["query_lvl"]; ?></div></td>
	<td><div align="left"><?PHP	echo html_entity_decode($row["query_desc"]);?>	</div></td>
	<td><div>
	<a href="plugins/Query/pages/edit_query.php?update_id=<?php echo $row["query_id"]; ?>"><?php echo lang_get( 'query_edit' ) ?></a>
	<a href="plugins/Query/pages/query_delete.php?delete_id=<?php echo $row["query_id"]; ?>"><?php echo lang_get( 'query_delete' ) ?></a>
	<a href="plugins/Query/pages/exec_query.php?id=<?php echo $row["query_id"]; ?>"><?php echo lang_get( 'query_execute' ) ?></a>



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
	layout_page_end();