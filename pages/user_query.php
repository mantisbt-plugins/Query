<?php
layout_page_header( lang_get( 'plugin_format_title' ) );
layout_page_begin( );
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
<tr >
<td class="category" colspan="4">
</td>
</tr>


<tr >
<td class="category" colspan="4">
</td>
</tr>
<tr>
<td class="form-title" colspan="4">
<?php echo lang_get( 'plugin_query_name' ) . ': ' . lang_get( 'plugin_query_execute' ) ?>
</td>
</tr>


<tr class="row-category">
<td><div align="center"><?php echo lang_get( 'query_title' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'query_type' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'query_desc' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'query_act' ); ?></div></td>


<?php
$sql = "select query_id,query_name,query_desc,query_type from {plugin_Query_definitions} where query_type='Q' and query_lvl='U' order by query_name ";
$result = db_query($sql);
while ($row = db_fetch_array($result)) {
	?>
	<tr>
	<td><div align="center"><?php  echo html_entity_decode($row["query_name"]); ?>	</div></td>
	<td><div align="center"><?php echo $row["query_type"]; ?></div></td>
	<td><div align="left"><?PHP	echo html_entity_decode($row["query_desc"]);?>	</div></td>
	<td><div>
	<a href="plugin.php?page=Query/exec_query.php&id=<?php echo $row["query_id"]; ?>"><?php echo lang_get( 'query_execute' ) ?></a>
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