<?PHP
$reqVar		= '_' . $_SERVER['REQUEST_METHOD'];
$form_vars	= $$reqVar;
$update_id	= $form_vars['update_id'] ;
//require_once( '../../../core.php' );
$basepad=config_get('path');

$sql = "select b.*,query_name from {plugin_Query_definitions} as a,{plugin_Query_schedule} as b  where  a.query_id=b.query_id and schedule_id=$update_id "; 
$result = db_query($sql);
$row = db_fetch_array($result);
?>
<form name="editschedule" method="post" action="edit_schedule2.php">

<center>
<strong><?php echo lang_get( 'schedule_update_comments' ) ?>: </strong>
<?php echo $row['schedule_desc'];?>
<br>
<?php echo $row['query_name'];?>
<br><br><br>
</center>
<input type="hidden" name="update_id" value="<?php echo $update_id;  ?>">
<td><div align="center"><?php echo lang_get( 'schedule_filter' ) ?><br>
<textarea name="schedule_filter" rows="3" cols="50"><?php echo $row['schedule_filter'];  ?></textarea>
</div>
</td>
<td><div align="center"><?php echo lang_get( 'schedule_target' ) ?><br>
<textarea name="schedule_target" rows="3" cols="50"><?php echo $row['target'];  ?></textarea>
</div>
</td>
	
<center>
<td><input name="Update" type="submit" value="Update"></td>
<td><a href="plugin.php?page=Query/manage_schedule">Cancel<a/></td>
</tr>
</form>
