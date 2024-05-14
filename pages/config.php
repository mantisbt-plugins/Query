<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( lang_get( 'plugin_format_title' ) );
layout_page_begin( 'config_page.php' );
print_manage_menu();
$link=plugin_page('manage_query');
$link2=plugin_page('manage_schedule');
?>

<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 
<div class="widget-box widget-color-blue2">

<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo  lang_get( 'plugin_query_name' ).': ' . lang_get( 'plugin_query_config' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<table class="table table-bordered table-condensed table-striped"> 
<form action="<?php echo plugin_page( 'config_edit' ) ?>" method="post">

<tr >
<td class="category" colspan="4">
</td>
</tr>
<br>
<tr>
<td class="form_title" colspan="4" >
<a href="<?php echo $link ?>"><?php echo lang_get( 'plugin_query_manage' ) ?></a>
<==>
<a href="<?php echo $link2 ?>"><?php echo lang_get( 'manage_schedule' ) ?></a>
<br><br>
</td>
</tr>

<tr >
<td class="category" colspan="4">
</td>
</tr>

<tr >
<td class="category">
<?php echo lang_get( 'query_manage_threshold' ) ?>
</td>
<td class="category">
<select name="manage_threshold">
<?php print_enum_string_option_list( 'access_levels', plugin_config_get( 'manage_threshold'  ) ) ?>;
</select> 
</td>
</tr>

<tr >
<td class="category">
<?php echo lang_get( 'query_execute_threshold' ) ?>
</td>
<td class="category">
<select name="execute_threshold">
<?php print_enum_string_option_list( 'access_levels', plugin_config_get( 'execute_threshold'  ) ) ?>;
</select> 
</td>
</tr>

<tr ?>
<td class="category">
<?php echo lang_get( 'query_download_location' ) ?>
</td>
<td class="category">
<input type="text" name="download_location" size="50" maxlength="50" value="<?php echo plugin_config_get( 'download_location' )?>" >
</td>
</tr>

<tr >
<td class="category">
<?php echo lang_get( 'query_separator' ) ?>
</td>
<td class="category">
<input type="text" name="separator" size="1" maxlength="1" value="<?php echo plugin_config_get( 'separator' )?>" >
</td>
</tr>

<tr >
<td class="category">
<?php echo lang_get( 'query_from_address' ) ?>
</td>
<td class="category">
<input type="text" name="from_address" size="50" maxlength="50" value="<?php echo plugin_config_get( 'from_address' )?>" >
</td>
</tr>

<tr >
<td class="category" width="60%">
<?php echo lang_get( 'query_delete_file' )?>
</td>
<td class="category" width="20%">
<label><input type="radio" name='delete_file' value="1" <?php echo( ON == plugin_config_get( 'delete_file' ) ) ? 'checked="checked" ' : ''?>/>
<?php echo lang_get( 'query_enabled' )?></label>

<label><input type="radio" name='delete_file' value="0" <?php echo( OFF == plugin_config_get( 'delete_file' ) )? 'checked="checked" ' : ''?>/>
<?php echo lang_get( 'query_disabled' )?></label>
</td>
</tr> 

<tr>
<td class="center" colspan="3">
<input type="submit" class="button" value="<?php echo lang_get( 'change_configuration' ) ?>" />
</td>
</tr>
</table>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
<?php
layout_page_end(  );