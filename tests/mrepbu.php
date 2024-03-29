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

# This creates an excel file with monthly management report
# last month and year to date


#functions

if (!function_exists('getCategory')) {
function getCategory($c) {
    if ($c <> 0) {
        $q = db_query ("select name from mantis_category_table WHERE id = $c ");
        $r = db_fetch_array($q);
        return $r['name'];
    }
    else {
        return '';
    }
}
}

if (!function_exists('getBU')) {
function getBU($severity) {
    switch ($severity) {
        case 0:
            $ret = "-";
            break;
        case 5:
            $ret = "N/A";
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
    }
    return $ret;
}
}

if (!function_exists('getUnit')) {
function getUnit($id) {
    if ($id <> 0) {
        $q = db_query ("select name from mantis_project_table WHERE id = $id ");
        $r = db_fetch_array($q);
        return $r['name'];
    }
    else {
        return '';
    }
}
}

//Definitions
$filename = date(YmdHis) . '.xlsx';
$today = date('Y-m-d');
$thisyear = date('Y');
$thismonth = date('m');
$lastmonth = date('m') - 1;
$now = strtotime($today);
$startofthisyear = strtotime($thisyear . '-1-1');
$pastmonth = strtotime($thisyear . '-' . $lastmonth);

$bunits = array(0, 5, 8, 10, 20, 30, 40, 50, 60, 70);

#check if the project_id is set
if (!isset($project_id)) {
    $project_id = 2;
}

#create Excel
$objPHPExcel = new PHPExcel();
// Set properties
$objPHPExcel->getProperties()->setCreator("SCM");
$objPHPExcel->getProperties()->setLastModifiedBy("SCM");
$objPHPExcel->getProperties()->setTitle("SCM report");
$objPHPExcel->getProperties()->setSubject("SCM report per Business Unit");
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
$objPHPExcel->getActiveSheet()->mergeCells('A1:B1');
$objPHPExcel->getActiveSheet()->mergeCells('D3:F3');
$objPHPExcel->getActiveSheet()->mergeCells('H3:J3');

//set column widths
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('5');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('25');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('20');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('3');
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth('3');
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth('10');

//set cell data
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Complaints');

$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Units');
$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Business');
$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Category');
$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'New');
$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Resolved');
$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Closed');
$objPHPExcel->getActiveSheet()->SetCellValue('G2', ' ');
$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'New');
$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'Resolved');
$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Closed');
$objPHPExcel->getActiveSheet()->SetCellValue('K2', ' ');
$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'Open');

$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'In the previous month');
$objPHPExcel->getActiveSheet()->SetCellValue('H3', 'Year to date');
$objPHPExcel->getActiveSheet()->SetCellValue('L3', 'current');
$objPHPExcel->getActiveSheet()->SetCellValue('M3', 'Over due');

$objPHPExcel->getActiveSheet()->getStyle('A3:L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i = 4; //start line
#loop business
foreach ($bunits as $bu) {
    #loop category for business unit
    $qcat = db_query ("select id from mantis_category_table WHERE project_id = $project_id ");
     while ($rcat = db_fetch_array($qcat)) {

        #last month total created
        $qlmt = db_query ("select count(id) as new
             from mantis_bug_table
             where project_id=" . $project_id . "
                AND severity=$bu
                AND category_id=".$rcat['id'] ."
                AND date_submitted BETWEEN $pastmonth AND $now ");
        $rlmt = db_fetch_array($qlmt);
        #last month total resolved
        $qlmr = db_query ("select count(id) as resolved
             from mantis_bug_table
                where project_id=" . $project_id . "
                AND severity=$bu
                AND category_id=".$rcat['id'] ."
                AND status = 80
                AND date_submitted BETWEEN $pastmonth AND $now ");
        $rlmr = db_fetch_array($qlmr);
        #last month total closed
        $qlmc = db_query ("select count(id) as closed
             from mantis_bug_table
             where project_id=" . $project_id . "
                AND severity=$bu
                AND category_id=".$rcat['id'] ."
                AND status = 90
                AND date_submitted BETWEEN $pastmonth AND $now ");
        $rlmc = db_fetch_array($qlmc);
        #this year total created
        $qyt = db_query ("select count(id) as new
             from mantis_bug_table
             where project_id=" . $project_id . "
                AND severity=$bu
                AND category_id=".$rcat['id'] ."
                AND date_submitted BETWEEN $startofthisyear AND $now");
        $ryt = db_fetch_array($qyt);
        #this year total resolved
        $qyr = db_query ("select count(id) as resolved
             from mantis_bug_table
             where project_id=" . $project_id . "
                AND severity=$bu
                AND category_id=".$rcat['id'] ."
                AND status = 80
                AND date_submitted BETWEEN $startofthisyear AND $now ");
        $ryr = db_fetch_array($qyr);
        #this year total closed
        $qyc = db_query ("select count(id) as closed
             from mantis_bug_table
             where project_id=" . $project_id . "
                AND severity=$bu
                AND category_id=".$rcat['id'] ."
                AND status = 90
                AND date_submitted BETWEEN $startofthisyear AND $now ");
        $ryc = db_fetch_array($qyc);

        #this year total closed
        $qc = db_query ("select count(id) as open
             from mantis_bug_table
             where project_id=" . $project_id . "
                AND severity=$bu
                AND category_id=".$rcat['id'] ."
                AND status < 80");
        $rc = db_fetch_array($qc);
		
		#this year total over due where not resolved
			$qod = db_query ("select count(id) as overdue
             FROM mantis_bug_table 
			 WHERE last_updated > due_date
				AND status < 80
				AND project_id=" . $project_id . "
                AND severity=$bu
                AND category_id=".$rcat['id'] ."
");
    $rod = db_fetch_array($qod);

        if ($rlmt['new'] > 0 || $rlmr['resolved'] > 0 || $rlmc['closed'] > 0 || $ryt['new'] > 0 || $ryr['resolved'] > 0 || $ryc['closed'] > 0) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, getUnit($project_id));
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, getBU($bu));
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, getCategory($rcat['id']));
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $rlmt['new']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $rlmr['resolved']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $rlmc['closed']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, ' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $ryt['new']);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $ryr['resolved']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $ryc['closed']);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, ' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, $rc['open']);
			$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, $rod['overdue']);

            $i++;
        }
    }
}

//set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1:M3')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A1:M3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4:M' . $i)->getFont()->setSize(10);

//set borders
$objPHPExcel->getActiveSheet()->getStyle('A1:M3')->applyFromArray($styleThinBlackBorderAll);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Complaints BU');

//set pagebreaks
$objPHPExcel->getActiveSheet()->setBreak('A30', PHPExcel_Worksheet::BREAK_ROW);
$objPHPExcel->getActiveSheet()->setBreak('M30', PHPExcel_Worksheet::BREAK_COLUMN);

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
$headers = "From: $from"."\r\n";
$headers.="Return-Path: $from"."\r\n";

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
  $returnpath = "-f" . $from;
  //$ok = mail($to, $subject, $message, $headers, $returnpath);
  $ok = mail(rikkleinloog@stahl.com);
  


#and remove it
unlink($fullfile);

$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);

