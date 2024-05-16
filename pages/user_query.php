<?php
layout_page_header( lang_get( 'plugin_format_title' ) );
layout_page_begin();
?>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 
<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo  lang_get( 'plugin_query_name' ).': ' . lang_get( 'plugin_user_query' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<table class="table table-bordered table-condensed table-striped"> 
<tr ><td>
<?PHP
if ( access_has_project_level( plugin_config_get( 'manage_threshold' ) )  ){
	$link1	= plugin_page('config');
	$link2	= plugin_page('manage_schedule');
	$link3	= plugin_page('manage_query');
	print_link_button( $link1, lang_get( 'plugin_query_config' ) );
	print_link_button( $link2, lang_get( 'manage_schedule' ) );
	print_link_button( $link3, lang_get( 'plugin_query_manage' ) );
}
?>
</td></tr>
<tr>
<td class="form-title" colspan="4">
<?php echo lang_get( 'plugin_query_name' ) . ': ' . lang_get( 'plugin_query_execute' ) ?>
</td>
</tr>

<tr class="row-category">
<td><div align="center"><b><?php echo lang_get( 'query_title' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'query_type' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'query_desc' ); ?></b></div></td>
<td><div align="center"><b><?php echo lang_get( 'query_act' ); ?></b></div></td>

<?php
$sql = "select query_id,query_name,query_desc,query_type from {plugin_Query_definitions} where query_type='Q' order by query_name ";
$result = db_query($sql);
while ($row = db_fetch_array($result)) {
	?>
	<tr>
	<td><div align="center"><?php  echo html_entity_decode($row["query_name"]); ?>	</div></td>
	<td><div align="center"><?php echo $row["query_type"]; ?></div></td>
	<td><div align="left"><?PHP	echo html_entity_decode($row["query_desc"]);?>	</div></td>
	<td><div>
	<?PHP
	$link7 = "plugin.php?page=Query/exec_query.php&id=";
	$link7 .= $row["query_id"]  ;
	print_link_button( $link7, lang_get( 'query_execute' ) );
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