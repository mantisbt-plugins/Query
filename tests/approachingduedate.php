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

# This creates an excel file with monthly management report
# last month and year to date

//Definitions
$filename = date(YmdHis) . '.xlsx';
$today = date('Y-m-d');
$now = strtotime($today);
$t_rem_days		= 2;
$t_rem_status	= 50;
//$baseline=time(true)+ ($t_rem_days*24*60*60);
$baseline = $now + ($t_rem_days*24*60*60);

$objPHPExcel = new PHPExcel();
// Set properties
$objPHPExcel->getProperties()->setCreator("SCM");
$objPHPExcel->getProperties()->setLastModifiedBy("SCM");
$objPHPExcel->getProperties()->setTitle("SCM report");
$objPHPExcel->getProperties()->setSubject("SCM report");
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
//$objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
//$objPHPExcel->getActiveSheet()->mergeCells('F2:H2');

//set column widths
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('40');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('30');

//set cell data
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Issue-id');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Summary');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Due date');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Assigned to');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Name');


//$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i = 2; //start at line

$query="select $t_bug_table.id,summary,due_date,username,realname 
from $t_bug_table,$t_user_table 
where $t_bug_table.handler_id=$t_user_table.id 
and status=$t_rem_status 
and due_date>1 and due_date<=$baseline" ;
$results = db_query( $query );
while ($row1 = db_fetch_array($results)) {
	$id 		= $row1['id'];
	$summary	= trim($row1['summary']);
	$duedate	= date( config_get( 'short_date_format' ),$row1['due_date'] );
	$assigned	= $row1['username'];
	$name		= $row1['realname'];


    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $id);
    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $summary);
    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $duedate);
    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $assigned);
    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $name);

    $i++;
}

//set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:E' . $i)->getFont()->setSize(10);
//$objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->getFont()->setBold(true);

//set borders
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleThinBlackBorderAll);
//$objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($styleThinBlackBorderAll);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Approaching Due date');

//set pagebreaks
$objPHPExcel->getActiveSheet()->setBreak('A30', PHPExcel_Worksheet::BREAK_ROW);
$objPHPExcel->getActiveSheet()->setBreak('E30', PHPExcel_Worksheet::BREAK_COLUMN);

//set page orientation and papersize
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
// End sheet


$fullfile = config_get('plugin_Query_download_location', 'Query');
$fullfile .= $filename;

//save it
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save($fullfile);

//open it
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);

