<?php
require_once( '../../../core.php' );
#
# Includes for PHP-Office, Excel or Word
# Need to be taken care of in the scripts itself 
# 

#logfile
$logfilename = PLUGINPATH.'/logs/';
$logfilename .= date('Ymd');
$logfilename .= '_scm_schedule.log';

# what is the table for queries/scripts
$q1_table		= plugin_table('definitions','Query');
$q3_table		= plugin_table('schedule','Query');

# which field separator
$separator = config_get( 'plugin_Query_separator','Query'  );

# load all defined schedules
$query1 = "select * from $q3_table order by schedule_id";
$result1 = db_query($query1);
while ($row1 = db_fetch_array($result1)) {
    # fetch query definition
    $schedule_id	= $row1['schedule_id'];
    $query_id		= $row1['query_id'];
    $target			= $row1['target'];
    $freq			= $row1['frequency'];
    $schedule_name  = $row1['schedule_desc'];

    $starttime = date('Y-m-d H:i:s');

    # should we run this schedule today ?
    $weekday	= true;
    $month		= true;
    $week		= true;
    if (date('d') <> '01'){
        $month = false;
    }
    $bookdate = mktime(0, 0, 0, date('m'),date('d'),date('Y'));
    $date_info  = getdate( $bookdate );
    if (($date_info["wday"] == 0) or ($date_info["wday"] == 6) ) {
        $weekday = false;
    }
    if ($date_info["wday"] <> 1)  {
        $week = false;
    }
    if (($freq == 'D') and (!$weekday)){
        continue;
    }
    if (($freq == 'W') and (!$week)){
        continue;
    }
    if (($freq == 'M') and (!$month)){
        continue;
    }

    # let's go
    $query2			= "select * from $q1_table where query_id=$query_id" ;
    $result2		= db_query($query2);
    $row2			= db_fetch_array($result2);

    # if we have an additional filter, rebuild query string (only for type Query)
    if ($row2['query_type'] == 'Q'){

        if (!empty($row1['schedule_filter'])){
            $sql = 'select ';
            $sql .= $row2['query_fields'];
            $sql .= ' from ';
            $sql .= $row2['query_tables'] ;
            $where ='';
            if (!empty($row2['query_joins'])){
                $where .= ' where ';
                $where .= $row2['query_joins'];
            }
            if (!empty($row2['query_filters'])){
                if (empty($where)){
                    $where .= ' where ';
                } else {
                    $where .= ' and ';
                }
                $where .= html_entity_decode($row2['query_filter']);
            }
            if (!empty($row1['schedule_filter'])){
                if (empty($where)){
                    $where .= ' where ';
                } else {
                    $where .= ' and ';
                }
                $where .= html_entity_decode($row1['schedule_filter']);
            }
            $sql .= $where;
            if (!empty($row2['query_order'])){
                $sql .= ' order by ';
                $sql .= $row2['query_order'];
            }
            if (!empty($row2['query_group'])){
                $sql .= ' group by ';
                $sql .= $row2['query_group'];
            }
        } else {
            $sql = html_entity_decode($row2['query_sql']);
        }

    }

    # action depends on type of script
    $action = $row2['query_type'] ;

    switch($action){
    case "Q":
        # 'Q' means execute query, copy results into $content

        $query3		= $sql;
        $result3	= db_query($query3) ;
        if (!$result3) {
			break;
            //die('Invalid query: ' . mysql_error());
        }

  		# fieldnames
		$query4 = "create temporary table temptable (";
		$query4 .= $query3;
		$query4 .= ")";
 		$result4 = db_query($query4);
		$query5 = "Show columns from temptable";
		$result5 = db_query($query5);
		$fieldlist ="";
		while ($row5= db_fetch_array($result5)) {
			$fieldlist .= "$separator" ;
			$fieldlist .= $row5['field'];
		}
		$length = strlen($fieldlist);
		$fieldlist = substr($fieldlist, 1, ($length-1));
		$fieldar = explode(",",$fieldlist);
		$fieldcount = count($fieldar);	
		
        $fields		= 0 ;
        $content	= '';		


		while ($row3 = db_fetch_array($result3)) {
            # first count the number of fields we have (only once)
            if ($fields == 0) {
                $fields = $fieldcount ;
                # now create the headerline
                for ( $i = 0; $i < $fields; $i++ ) {
                    if ($i>0){
                        $content .= "$separator" ;
                    }
                    $content .=  $fieldar[$i];
                }
                $content .= "\r\n";
            }
            // data output
            for ($i=0; $i < $fields;$i++){
                if ($i>0){
                    $content .= "$separator" ;
                }
  				$name = $fieldar[$i];
				$content .= $row3[trim($name)] ;
            }
            $content .= "\r\n";
        }
        break;
    case "S":
        # 'S' means include script which returns results into $content
        $fp = fopen($logfilename, 'a');
        fwrite($fp, "---- executing S $schedule_name \r\n");
        fclose($fp);

        if (!empty($row1['schedule_filter'])){
            $code = html_entity_decode($row1['schedule_filter']);
            $code .= $row2['query_script'];
        } else {
            $code = $row2['query_script'];
        }
        $starttime = date('Y-m-d H:i:s');
        $fp = fopen($logfilename, 'a');
        fwrite($fp, "---- starting S @  $starttime  $schedule_name \r\n");
        fclose($fp);

        $content = eval(html_entity_decode($code));

        $endtime = date('Y-m-d H:i:s');
        $fp = fopen($logfilename, 'a');
        fwrite($fp, "---- ending S @  $endtime  $schedule_name \r\n");
        fclose($fp);
        break;
    case "X":
        # 'X' means include script which handles whatever needs to be done
                if (!empty($row1['schedule_filter'])){
            $code = html_entity_decode($row1['schedule_filter']);
            $code .= $row2['query_script'];
        } else {
            $code = $row2['query_script'];
        }
        //$code = $row2['query_script'];
        $starttime = date('Y-m-d H:i:s');
        $fp = fopen($logfilename, 'a');
        fwrite($fp, "---- starting X @  $starttime  $schedule_name \r\n");
        fclose($fp);

        $content = eval(html_entity_decode($code));

        $endtime = date('Y-m-d H:i:s');
        $fp = fopen($logfilename, 'a');
        fwrite($fp, "---- ending X @  $endtime  $schedule_name \r\n");
        fclose($fp);

        break;
    }

    if ($action<>'X'){

        $fp = fopen($logfilename, 'a');
        fwrite($fp, "---- executing X $schedule_name \r\n");
        fclose($fp);

        # create a unique filename
        $filename = uniqid(mt_rand(), true) . '.csv';

        # save the file to disk
        $fullfile = config_get( 'plugin_Query_download_location','Query'  );
        $fullfile .= $filename;
        $fh = fopen($fullfile, 'w') or die("can't open file");
        fwrite($fh, $content);
        # start sending the file to the recipients
		$ok=mail_it($query_id,$filename, $target,$schedule_name);
        if ($ok) {
            if ( ON == config_get( 'plugin_Query_delete_file' ) ) {
                $fullfile = config_get( 'plugin_Query_download_location','Query'  );
                $fullfile .= $filename;
				unlink($fullfile) ;
            }
        }

    }
        $fp = fopen($logfilename, 'a');
        $endtime = date('Y-m-d H:i:s');
        fwrite($fp, " -- End @ $endtime --> $schedule_name \r\n");
        fclose($fp);

}

function mail_it($query_id,$filename, $target,$schedule_name){
	
	# recipients
    $recipients =  explode(",", $target);
    #now retrieve email addresses and fill the TO variable
    $fields = count($recipients) ;
    $to = '';
    # now create the to address
    for ( $i = 0; $i < $fields; $i++ ) {
        if ($i>0){
            $to .= "," ;
        }
        $username 	= $recipients[$i];
        $domain = strstr($username, '@');
        if (empty($domain)) {
            $userid		= user_get_id_by_name($username);
            $t_email	= user_get_email( $userid );
        } else {
            $t_email = $username ;
        }
        if ($t_email <> ''){
            $to .= $t_email;
        }
    }
    if ($to==''){
        return false;
    }

 
// Sender 
	    $from = config_get( 'plugin_Query_from_address','Query'  );
$fromName = 'MantisQuery'; 
 
// Email subject 
   $subject = "SCM-Report :".$schedule_name;
 
// Attachment file 
   $file = config_get( 'plugin_Query_download_location','Query'  );
    $file .= $filename;
 
// Email body content 
$htmlContent = ' 
    <h3>Query results</h3> 
    <p>This email is sent from the Mantis-Query engine with 1 attachment.</p> 
'; 
$htmlContent .= date("Y.m.d H:i:s")."\n "." ==>> ". $filename;
 
// Header for sender info 
$headers = "From: $fromName"." <".$from.">"; 
 
// Boundary  
$semi_rand = md5(time());  
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
 
// Headers for attachment  
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
// Multipart boundary  
$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
 
// Preparing attachment 
if(!empty($file) > 0){ 
    if(is_file($file)){ 
        $message .= "--{$mime_boundary}\n"; 
        $fp =    @fopen($file,"rb"); 
        $data =  @fread($fp,filesize($file)); 
 
        @fclose($fp); 
        $data = chunk_split(base64_encode($data)); 
        $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
        "Content-Description: ".basename($file)."\n" . 
        "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
    } 
} 
$message .= "--{$mime_boundary}--"; 
$returnpath = "-f" . $from; 
 
// Send email 
$mail = @mail($to, $subject, $message, $headers, $returnpath);  
 
// Email sending status 
// echo $mail?"<h1>Email Sent Successfully!</h1>":"<h1>Email sending failed.</h1>"; 
return $mail;
}