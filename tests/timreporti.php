<?php
error_reporting(-1); //turn on errorreporting

require_once( '../../core.php' );

/** Includes for PHP excel * */
ini_set('include_path', ini_get('include_path') . ';G:\Classes\\');

include 'PHPExcel.php';
require_once 'PHPExcel/IOFactory.php';
include 'PHPExcel/Writer/Excel2007.php';
require_once 'PHPExcel/Cell/AdvancedValueBinder.php';

# This creates a KPI export of all complaints created last week

//PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

#remove for production untill here


$t_core_path = config_get('core_path');

# -- functions --


if (!function_exists('getRealname')) {

    function getRealname($id) {
        if ($id <> 0) {
            $q = db_query ("SELECT * FROM mantis_user_table WHERE id = $id");
            $r = db_fetch_array($q);
            return $r['realname'];
        }
        else {
            return '';
        }
    }

}

if (!function_exists('dodate')) {
    function dodate($d) {
	$newdate = explode('-', $d);
            $ny = $newdate[0];
            $nm = $newdate[1];
            $nd = $newdate[2];
            return substr($ny . '-' . $nm . '-' . $nd, 0, 10);
    }
}

if (!function_exists('strdate')) {
    function strdate($d) {
$date = date("Y-m-d", $d);
return $date;
    }
}



if (!function_exists('GetCustomField')) {

    function GetCustomField($bugid) {
        $q = db_query ("SELECT * FROM mantis_custom_field_string_table WHERE bug_id = $bugid AND field_id=6");
        $r = db_fetch_array($q);
        return $r['value'];
    }

}

if (!function_exists('CleanStr')) {

    function CleanStr($str) {
        $nstr = str_replace(config_get('plugin_Query_separator', 'Query'), " ", $str);
        $nstr = str_replace("\n", " ", $nstr);
        $nstr = str_replace("\r", " ", $nstr);
        return $nstr;
    }

}

if (!function_exists('GetResolvedDate')) {

    function GetResolvedDate($bugid) {
        //$q = db_query ("select * from mantis_bug_history_table where bug_id=$bugid AND field_name='status' AND new_value=80");
        $q = db_query ("select FROM_UNIXTIME(date_modified) as resdate 
                             from mantis_bug_history_table where bug_id=$bugid AND field_name='status' AND new_value=80");
         $r = db_fetch_array($q);
         //return strdate($r['date_modified']);
         return $r['resdate'];
    }
	
}

if (!function_exists('getRelation')) {

    function getRelation($bugid) {
        $q = db_query ("SELECT * FROM mantis_plugin_SapData_connect_table as con JOIN mantis_plugin_SapData_relations_table as rel ON rel.cust_id=con.sap_rel_id WHERE con.sap_bug_id = $bugid");
        $r = db_fetch_array($q);
        return $r['cust_name'];
    }

}

if (!function_exists('GetResolvedDateTs')) {

    function GetResolvedDateTs($bugid) {
        $q = db_query ("select date_modified  
                             from mantis_bug_history_table where bug_id=$bugid AND field_name='status' AND new_value=80");
        $r = db_fetch_array($q);
         return $r['date_modified'];
         //return $r['resdate'];
    }
	
}

if (!function_exists('ExpiredDays')) {

    function ExpiredDays($task_id) {
        $diff = db_query ("SELECT DATEDIFF (task_completed,task_created)
        as difference from mantis_plugin_Tasks_defined_table where task_id=$task_id");
        $r = db_fetch_array($diff);
        return $r['difference'];
       
    }
	
}

if (!function_exists('Crea')) {

    function Crea($task_id) {
        $date = db_query ("SELECT task_created from mantis_plugin_Tasks_defined_table where task_id=$task_id");
        $r = db_fetch_array($date);        
        $spl = str_split($r['task_created'], 10);
        return $spl[0];
       
    }
	
}

if (!function_exists('Clo')) {

    function Clo($task_id) {
        $date = db_query ("SELECT task_completed from mantis_plugin_Tasks_defined_table where task_id=$task_id");
        $r = db_fetch_array($date);        
        $spl = str_split($r['task_completed'], 10);
        return $spl[0];
       
    }
	
}



# --
if (!isset($project_id)) {
//$project_id = 9; //SG
$project_id  = 8; //WW
//$project_id  =0; //NONE
}

if (!isset($month_start)) {
 $month_start  = 1; 
}
if (!isset($month_end)) {
 $month_end  = 31; 
}
$tmonth = date('m');

#get last month only
//$first = strtotime(date("Y-m-$month_start",strtotime('first day last month') )) .'';
//$first = strtotime(date("Y-m-$month_start",strtotime('last month') )) .'';
$first = strtotime(date("2013-01-01",strtotime('first day') )) .'';
//$last = strtotime(date("Y-$tmonth-01",strtotime('first day') )) .'';
$last = strtotime(date("Y-m-d")) .'';

//date('m/d/y', strtotime('last day last month')); # 01/31/10
//echo date("Y-m-01",strtotime('first day last month')).'<br />';
//echo date("Y-m-31",strtotime('last day last month') ) .'<br />';
//echo date("Y-$tmonth-$month_end",strtotime('last day last month') ) .'<br />';
$between = "BETWEEN $first AND $last ";
//$between = " ";


$filename = date(YmdHis) . '.xlsx';

$objPHPExcel = new PHPExcel();
// Set properties
$objPHPExcel->getProperties()->setCreator("SCM");
$objPHPExcel->getProperties()->setLastModifiedBy("SCM");
$objPHPExcel->getProperties()->setTitle("SCM report");
$objPHPExcel->getProperties()->setSubject("SCM Extended Report");
$objPHPExcel->getProperties()->setDescription("Report generated by SCM");

//border definitions
$styleThinBlackBorderOut = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        )
    ),
);
$styleThinBlackBorderAll = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
        'inside' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        )
    ),
);
$styleThickBlackBorderOut = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
            'color' => array('argb' => 'FF000000'),
        )
    ),
);

$styleThinBlackBorderHor = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
        'inside' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        )
    ),
);

/* new sheet */
// start creating the sheet
$objPHPExcel->setActiveSheetIndex(0);

//set column widths
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('70');
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('20');


//set cell data
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Complaint ID');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Task ID.');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Product');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Task Description');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Task Handler');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Task Creation Date');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Task Close Date');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'No of Days');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Customer');


$query = "select id, project_id FROM mantis_bug_table WHERE status!=90 and project_id = $project_id order by id";
//die ($query);
$results = db_query ($query);

$i = 2; //start at line
    while ($r = db_fetch_array($results)) {
$taskquery = "select * from  mantis_plugin_Tasks_defined_table where bug_id=$r[id]";
$tasks = db_query ($taskquery);
 while ($t = db_fetch_array($tasks)) {
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $r['id']);
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $t['task_id']);
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, GetCustomField($r['id']));
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, cleanStr($t['task_desc']));
$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, getRealname($t['task_handler']));
$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, Crea($t['task_id']));
$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, Clo($t['task_id']));
$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, ExpiredDays($t['task_id']));
$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, getRelation($r['id']));
$i++;
     }     
}

        
 
    //die('---');
//set autofilter on toprow
$objPHPExcel->getActiveSheet()->setAutoFilter(
	$objPHPExcel->getActiveSheet()->calculateWorksheetDimension()
);

//set borders
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleThinBlackBorderAll);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('report');

//set pagebreaks
$objPHPExcel->getActiveSheet()->setBreak('A30', PHPExcel_Worksheet::BREAK_ROW);
$objPHPExcel->getActiveSheet()->setBreak('G30', PHPExcel_Worksheet::BREAK_COLUMN);

//set page orientation and papersize
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
// End sheet


$fullfile = config_get('plugin_Query_download_location', 'Query');
$fullfile .= $filename;

//save it
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($fullfile);

//mail it


$from = config_get('plugin_Query_from_address', 'Query');
$recipients = explode(",", $target);
#now retrieve email addresses and fill the TO variable
$fields = count($recipients);
$to = '';

# now create the to address

for ($i = 0; $i < $fields; $i++) {
    if ($i > 0) {
        $to .= ",";
    }
    $username = $recipients[$i];
    $domain = strstr($username, '@');
    if (empty($domain)) {
        $userid = user_get_id_by_name($username);
        $t_email = user_get_email($userid);
    }
    else {
        $t_email = $username;
    }
    if ($t_email <> '') {
        $to .= $t_email;
    }
}

if ($to == '') {
    return false;
}

$today = date("F j, Y, g:i a");
$body = lang_get('plugin_query_body') . " \n\n";
$body .= $filename . " \n\n";
$body .= lang_get('plugin_query_date') . " \n\n";
$body .= $today;

// email fields: to, from, subject, and so on
$subject = "SCM-Report :" . $schedule_name;
$message = date("Y.m.d H:i:s") . "\n 1 attachment";
$headers = "From: $from";
$headers.="Return-Path:$from\r\n";

// boundary
$semi_rand = md5(time());
$mime ary = "==Multipart_Boundary_x{$semi_rand}x";

// headers for attachment
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime ary}\"";

// multipart boundary
$message = "--{$mime ary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";

// preparing attachment
if (is_file($fullfile)) {
    $message .= "--{$mime ary}\n";
    $fp = @fopen($fullfile, "rb");
    $data = @fread($fp, filesize($fullfile));
    @fclose($fp);
    $data = chunk_split(base64_encode($data));
    $message .= "Content-Type: application/octet-stream; name=\"" . basename($filename) . "\"\n" .
            "Content-Description: " . basename($filename) . "\n" .
            "Content-Disposition: attachment;\n" . " filename=\"" . basename($filename) . "\"; size=" . filesize($fullfile) . ";\n" .
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
}
else {
    return false;
}
$returnpath = "-f" . $from."\r\n";
//mail($to, $subject, $message, $headers, $returnpath);
mail('dennis.geus@stahl.com', $subject, $message, $headers, $returnpath);

#and remove it
unlink($fullfile);

$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);