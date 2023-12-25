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
# This creates a KPI export of all complaints

# -- functions --

if (!function_exists('ProjectName')) {

    function ProjectName($pid) {
        $q = db_query ("SELECT * FROM mantis_project_table WHERE id = $pid");
        $r = db_fetch_array($q);
        return $r['name'];
    }
}

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
if (!function_exists('humandate')) {

    function humandate($d) {
        return date('Y-m-d', $d);
    }

}
if (!function_exists('SubmitYear')) {

    function SubmitYear($d) {
        return date('Y', $d);
    }

}
if (!function_exists('SubmitMonth')) {

    function SubmitMonth($d) {
        return date('m', $d);
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
        $q = db_query ("select * from mantis_bug_history_table where bug_id=$bugid AND field_name='status' AND new_value=80");
         $r = db_fetch_array($q);
         return humandate($r['date_modified']);
    }
	
}

$lastweek	= mktime(0, 0, 0, date("m"), date("d")-8,   date("Y")); // 8 dagen geleden is zelfde dag een week geleden
$now		= mktime(0, 0, 0, date("m"), date("d")-1,   date("Y")); // vandaag telt niet mee, dus tot en met gisteren

//echo $lastweek;
//echo '<br />';
//echo $now;

# --
if (!isset($project_id)) {
//$project_id = 9; //SG
$project_id  = 2; //WW
//$project_id  =0; //NONE
}

$filename = date(YmdHis) . '.xlsx';

$objPHPExcel = new PHPExcel();
// Set properties
$objPHPExcel->getProperties()->setCreator("SCM");
$objPHPExcel->getProperties()->setLastModifiedBy("SCM");
$objPHPExcel->getProperties()->setTitle("SCM report");
$objPHPExcel->getProperties()->setSubject("SCM custom Report");
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
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('5');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('50');
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('10');

//set cell data
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Complaint');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Unit');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Date Submitted');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Summary');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Relation No.');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Relation Name');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Business Unit');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Product');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Type');

//$query = "select * from mantis_bug_table WHERE date_submitted $between AND project_id = $project_id order by id";
//$query = "select * from mantis_bug_table order by id";
$query = "select * from mantis_bug_table WHERE date_submitted BETWEEN $lastweek AND $now order by id";
// echo $query;

$results = db_query ($query);

$i = 2; //start at line
    while ($r = db_fetch_array($results)) {

$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $r['id']);
$project = ProjectName($r['project_id']);
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $project);
$submitted = humandate($r['date_submitted']);
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $submitted);
$summary = cleanStr($r['summary']);
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $summary);
$relationnr = getRelation($r['id']);
$objPHPExcel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($relationnr, PHPExcel_Cell_DataType::TYPE_STRING);
$relationname = getRelationName($r['id']);
$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, $relationname);
$bu = getBU($r['severity']);
$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, $bu);
$objPHPExcel->getActiveSheet()->getCell('H'.$i)->setValueExplicit(GetCustomField($r['id'], 6), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->getCell('I'.$i)->setValueExplicit(GetCustomField($r['id'], 10), PHPExcel_Cell_DataType::TYPE_STRING);
	  
$i++;
        
    }
//set autofilter on toprow
$objPHPExcel->getActiveSheet()->setAutoFilter(
	$objPHPExcel->getActiveSheet()->calculateWorksheetDimension()
);

//set borders
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleThinBlackBorderAll);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('report');

//set pagebreaks
$objPHPExcel->getActiveSheet()->setBreak('A30', PHPExcel_Worksheet::BREAK_ROW);
$objPHPExcel->getActiveSheet()->setBreak('H30', PHPExcel_Worksheet::BREAK_COLUMN);

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