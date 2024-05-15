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
		<?php echo  lang_get( 'plugin_query_name' ).': ' . lang_get( 'plugin_query_manage' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<table class="table table-bordered table-condensed table-striped"> 
<form action="<?php echo plugin_page( 'query_add' ) ?>" method="post">
<tr>
<td class="form-title" colspan="4" >
<?php print_link_button( $link1, lang_get( 'plugin_query_config' ) );?>
<?php print_link_button( $link2, lang_get( 'manage_schedule' ) );?>
<?php print_link_button( $link3, lang_get( 'plugin_query_manage' ) );?>
</td>
</tr>
<tr class="row-category">

<td><div align="center"><b><?php echo lang_get( 'query_title' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'query_type' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'query_lvl' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'query_desc' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'query_act' ); ?></b></div></td>

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
<input type="submit" class="btn btn-primary btn-white btn-round"  value="<?php echo lang_get( 'add_query' ) ?>" />
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
	<?php
	$link6 = "plugin.php?page=Query/edit_query.php&update_id=";
	$link6 .= $row["query_id"]  ;
	$link7 = "plugin.php?page=Query/query_delete.php&delete_id=";
	$link7 .= $row["query_id"]  ;
		$link8 = "plugin.php?page=Query/exec_query.php&id=";
	$link8 .= $row["query_id"]  ;
	print_link_button( $link6, lang_get( 'query_edit' ) );
	print_link_button( $link7, lang_get( 'query_delete' ));
	print_link_button( $link8, lang_get( 'query_execute' ) );
	?>
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