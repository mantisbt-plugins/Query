<?php
error_reporting(-1); //turn on errorreporting

require_once( '../../../core.php' );

/** Includes for PHP excel * */
ini_set('include_path', ini_get('include_path') . ';G:\Classes\\');

include 'PHPExcel.php';
require_once 'PHPExcel/IOFactory.php';
include 'PHPExcel/Writer/Excel2007.php';
require_once 'PHPExcel/Cell/AdvancedValueBinder.php';

# This creates a KPI export of all complaints created last week

//PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

#remove for production untill here

# -- functions --
if (!function_exists('getCategory')) {

    function getCategory($catid) {
        $q = db_query ("SELECT * FROM mantis_category_table WHERE id = $catid");
        $r = db_fetch_array($q);
        return $r['name'];
    }
}

if (!function_exists('getJust')) {

    function getJust($rep) {
        switch ($rep) {
            case 0:
                $ret = "Unknown";
                break;
            case 10:
                $ret = "Not Determined Yet";
                break;
            case 30:
                $ret = "Justified";
                break;
            case 50:
                $ret = "Not Justified";
                break;
            case 40:
                $ret = "Confirmed";
                break;
            case 70:
                $ret = "Could not be determined";
                break;
            //default:
            //$ret = "N/A";
        }
        return $ret;
    }

}

if (!function_exists('getStatus')) {

    function getStatus($s) {
        switch ($s) {
            case 0:
                $ret = "Unknown";
                break;
            case 10:
                $ret = "New";
                break;
            case 20:
                $ret = "Feedback";
                break;
            case 30:
                $ret = "Acknowledged";
                break;
            case 40:
                $ret = "Confirmed";
                break;
            case 50:
                $ret = "Assigned";
                break;
            case 80:
                $ret = "Resolved";
                break;
            case 90:
                $ret = "Closed";
                break;
            //default:
            //$ret = "N/A";
        }
        return $ret;
    }

}

if (!function_exists('getBU')) {

    function getBU($severity) {
        switch ($severity) {
            case 0:
                $ret = "Unknown";
                break;
            case 5:
                $ret = "Not Applicable";
                break;
            case 8:
                $ret = "Purchases";
                break;
            case 10:
                $ret = "Leather Finish";
                break;
            case 20:
                $ret = "LF Automotive";
                break;
            case 30:
                $ret = "Performance Coatings";
                break;
            case 40:
                $ret = "Pielcolor";
                break;
            case 50:
                $ret = "Picassian";
                break;
            case 60:
                $ret = "Shoe Finish";
                break;
            case 70:
                $ret = "Wet End";
                break;
            case 80:
                $ret = "Intercompany";
                break;
            //default:
            //$ret = "N/A";
        }
        return $ret;
    }

}

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

if (!function_exists('SubmitYear')) {

    function SubmitYear($d) {
    $date = date("Y", $d);
    return $date;
    }

}

if (!function_exists('SubmitMonth')) {

    function SubmitMonth($d) {
        $date = date("m", $d);
    return $date;
    }

}

if (!function_exists('GetCustomField')) {

    function GetCustomField($bugid, $field) {
        $q = db_query ("SELECT * FROM mantis_custom_field_string_table WHERE bug_id = $bugid AND field_id=$field");
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

if (!function_exists('getRelation')) {

    function getRelation($bugid) {
        $q = db_query ("SELECT * FROM mantis_plugin_SapData_connect_table as con JOIN mantis_plugin_SapData_relations_table as rel ON rel.cust_id=con.sap_rel_id WHERE con.sap_bug_id = $bugid");
        $r = db_fetch_array($q);
        return $r['cust_id'];
    }

}

if (!function_exists('getRelationName')) {

    function getRelationName($bugid) {
        $q = db_query ("SELECT * FROM mantis_plugin_SapData_connect_table as con JOIN mantis_plugin_SapData_relations_table as rel ON rel.cust_id=con.sap_rel_id WHERE con.sap_bug_id = $bugid");
        $r = db_fetch_array($q);
        return $r['cust_name'];
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

if (!function_exists('GetResolvedDateTs')) {

    function GetResolvedDateTs($bugid) {
        $q = db_query ("select date_modified  
                             from mantis_bug_history_table where bug_id=$bugid AND field_name='status' AND new_value=80");
        $r = db_fetch_array($q);
         return $r['date_modified'];
         //return $r['resdate'];
    }
	
}

# --
if (!isset($project_id)) {
$project_id = 9; //SG
//$project_id  = 2; //WW
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
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('50');
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth('25');
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth('20');

//set cell data
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Complaint');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Relation No.');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Relation Name');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Assigned');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Category');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Summary');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Business Unit');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Qty');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Lot no.');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Order no.');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Product');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Package');
$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'UNS');
$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Manufactoring Location');
$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Supplied plant');
$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Justified');
$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Date submitted');
$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Due date');
$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Resolved date');
$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Last update');
$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Status');
$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'Main Type');
$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'Type');
$objPHPExcel->getActiveSheet()->SetCellValue('X1', 'Sub type');
$objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'Root Cause');
$objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'Claim ammount');
$objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'Claim granted');
$objPHPExcel->getActiveSheet()->SetCellValue('AB1', 'Year Submitted');
$objPHPExcel->getActiveSheet()->SetCellValue('AC1', 'Month Submitted');
$objPHPExcel->getActiveSheet()->SetCellValue('AD1', 'Resolved in');
$objPHPExcel->getActiveSheet()->SetCellValue('AE1', '<< 14 Days');

$query = "select FROM_UNIXTIME(date_submitted) as ds, FROM_UNIXTIME(due_date) as dd, FROM_UNIXTIME(last_updated) as ld,
id, handler_id, category_id, summary, severity, reproducibility, `status`, date_submitted
from mantis_bug_table WHERE date_submitted $between AND project_id = $project_id order by id";
//die ($query);
$results = db_query ($query);

$i = 2; //start at line
    while ($r = db_fetch_array($results)) {

$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $r['id']);
//$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, getRelation($r['id']));
$objPHPExcel->getActiveSheet()->getCell('B'.$i)->setValueExplicit(getRelation($r['id']), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, getRelationName($r['id']));
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, getRealname($r['handler_id']));
$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, getCategory($r['category_id']));
$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, cleanStr($r['summary']));
$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, getBU($r['severity']));
//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, GetCustomField($r['id'], 7));
$objPHPExcel->getActiveSheet()->getCell('H'.$i)->setValueExplicit(GetCustomField($r['id'], 7), PHPExcel_Cell_DataType::TYPE_STRING);
//$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, GetCustomField($r['id'], 2));
$objPHPExcel->getActiveSheet()->getCell('I'.$i)->setValueExplicit(GetCustomField($r['id'], 2), PHPExcel_Cell_DataType::TYPE_STRING);
//$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i, GetCustomField($r['id'], 5));
$objPHPExcel->getActiveSheet()->getCell('J'.$i)->setValueExplicit(GetCustomField($r['id'], 2), PHPExcel_Cell_DataType::TYPE_STRING);
//$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i, GetCustomField($r['id'], 6));
$objPHPExcel->getActiveSheet()->getCell('K'.$i)->setValueExplicit(GetCustomField($r['id'], 6), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->SetCellValue('L'.$i, GetCustomField($r['id'], 15));
$objPHPExcel->getActiveSheet()->SetCellValue('M'.$i, GetCustomField($r['id'], 11));
$objPHPExcel->getActiveSheet()->SetCellValue('N'.$i, GetCustomField($r['id'], 4));
$objPHPExcel->getActiveSheet()->SetCellValue('O'.$i, GetCustomField($r['id'], 20));
$objPHPExcel->getActiveSheet()->SetCellValue('P'.$i, getJust($r['reproducibility']));
//$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$i, strdate($r['date_submitted']));
$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$i, $r['ds']);
//$objPHPExcel->getActiveSheet()->SetCellValue('R'.$i, strdate($r['due_date']));
$objPHPExcel->getActiveSheet()->SetCellValue('R'.$i, $r['dd']);
if ($r['status']>=80) {
$resolvedate = GetResolvedDateTs($r['id']);
 $objPHPExcel->getActiveSheet()->SetCellValue('S'.$i, $resolvedate);
}
else {
 $objPHPExcel->getActiveSheet()->SetCellValue('S'.$i, '');
}
//$objPHPExcel->getActiveSheet()->SetCellValue('T'.$i, strdate($r['last_updated']));
$objPHPExcel->getActiveSheet()->SetCellValue('T'.$i, $r['ld']);
$objPHPExcel->getActiveSheet()->SetCellValue('U'.$i, getStatus($r['status']));
$objPHPExcel->getActiveSheet()->SetCellValue('V'.$i, GetCustomField($r['id'], 3));
$objPHPExcel->getActiveSheet()->SetCellValue('W'.$i, GetCustomField($r['id'], 10));
$objPHPExcel->getActiveSheet()->SetCellValue('X'.$i, GetCustomField($r['id'], 9));
$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$i, GetCustomField($r['id'], 8));
$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$i, GetCustomField($r['id'], 16));
$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$i, GetCustomField($r['id'], 17));
$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$i, SubmitYear($r['date_submitted']));
$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$i, SubmitMonth($r['date_submitted']));

#calculate date between resolved and submitted
// SELECT TIMESTAMPDIFF(MONTH,'2003-02-01','2003-05-01')
if (GetResolvedDateTs($r['id']) != '') {
$diffe = GetResolvedDateTs($r['id']);
$diffs = $r['date_submitted']; 
$diff = abs($diffe-$diffs);
$ndiff=$diff/86400;
$numberDays = round($ndiff, 1);
}
else {
    $numberDays = '';
}
/*
echo 'end:'.$diffe.' start:'.$diffs.' diff:'.$diff.' ndiff:'.$ndiff.' NoDays:'.$numberDays.'<br />';
*/
//$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, '=S'.$i.'-Q'.$i.'');
$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, $numberDays);

#calculate if number of days resolved in is <19.4
//$getS = $objPHPExcel->getActiveSheet()->getCell('S'.$i.'')->getCalculatedValue();
//$getAE = $objPHPExcel->getActiveSheet()->getCell('AD'.$i.'')->getCalculatedValue();

if (GetResolvedDate($r['id']) != '') {
  if ( ($numberDays >= 0) and ($numberDays <= 19.4) ) {
		$val = 1;
	}
	else {
		$val = 0;
	}
}
else {
	$val = '-';
}
$objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, $val);


$i++;
        
    }
    //die('---');
//set autofilter on toprow
$objPHPExcel->getActiveSheet()->setAutoFilter(
	$objPHPExcel->getActiveSheet()->calculateWorksheetDimension()
);

//set borders
$objPHPExcel->getActiveSheet()->getStyle('A1:AE1')->applyFromArray($styleThinBlackBorderAll);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('report');

//set pagebreaks
$objPHPExcel->getActiveSheet()->setBreak('A30', PHPExcel_Worksheet::BREAK_ROW);
$objPHPExcel->getActiveSheet()->setBreak('AE30', PHPExcel_Worksheet::BREAK_COLUMN);

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
mail('rik.kleinloog@stahl.com', $subject, $message, $headers, $returnpath);

#and remove it
unlink($fullfile);

$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);