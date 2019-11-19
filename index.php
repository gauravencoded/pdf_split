<?php
require('fpdf/fpdf.php');
require_once('./FPDI/src/autoload.php');
include 'pdfparser/vendor/autoload.php';
use setasign\Fpdi\Fpdi;



function split_pdf($filename, $end_directory = false)
{
    $parser = new \Smalot\PdfParser\Parser();
    $source_pdf    = $parser->parseFile('a.pdf');
    $text = $source_pdf->getText();
    $occurence=null;
    preg_match_all('/[:][ ][R][G]/',$text,$occurence,PREG_PATTERN_ORDER);
    $end_directory = $end_directory ? $end_directory : './';
$new_path = preg_replace('/[\/]+/', '/', $end_directory.'/'.substr($filename, 0, strrpos($filename, '/')));

if (!is_dir($new_path))
{
// Will make directories under end directory that don't exist
// Provided that end directory exists and has the right permissions
mkdir($new_path, 0777, true);
}

$pdf = new FPDI();
$pagecount = $pdf->setSourceFile('a.pdf'); // How many pages?
//echo 'page count ' . $pagecount. '<br>';
// Split each page into a new PDF


$new_pdf = new FPDI();
for ($i = 1; $i <= $pagecount; $i++) {
  
$new_pdf->setSourceFile('a.pdf');
    $new_pdf->AddPage();
    $new_pdf->useTemplate($new_pdf->importPage($i));
    echo (count($occurence)). '<br>';
    if($i % ($pagecount/count($occurence[0])) == 0 ){ //|| $i >= ($pagecount/count($occurence))
        echo $i;
        try {
            $new_filename = $end_directory.str_replace('.pdf', '', 'data').'_'.$i.".pdf";
            $new_pdf->Output($new_filename, "F");
            echo "Page ".$i." split into ".$new_filename."<br />\n";
            $new_pdf = new FPDI();
        } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    }

// }
}
}
// Create and check permissions on end directory!
split_pdf("filename.pdf", 'split/');
?>

