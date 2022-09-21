<?php
if (!defined('MODX_BASE_PATH')) {
    $modx_base_path= __DIR__.'/';    
    define('MODX_BASE_PATH', $modx_base_path);    
}
require_once (MODX_BASE_PATH . 'vendor/autoload.php');

use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PriceParser\Parsers\SingleVisitParser;
use \PriceParser\Parsers\ClassesWithTrainerParser;
use PriceParser\Parsers\HolidaysParser;
use PriceParser\Parsers\ProductsParser;
use PriceParser\Parsers\SubscriptionsParser;

$priceDir = MODX_BASE_PATH . 'upload/price/';
$dataDir = MODX_BASE_PATH . 'price_data/';
$inputFileType = IOFactory::READER_ODS;


$files = scandir(rtrim($priceDir, '/'));
foreach ($files as $file) {
    $filePath = $priceDir . $file;
    if (is_file($filePath) && (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) == strtolower($inputFileType))) {  
        $fileHash = hash('md5',file_get_contents($filePath));
        $hashPath = $dataDir . $file . 'hash';
        if (!file_exists($hashPath) || ($fileHash != file_get_contents($hashPath)) || true) {
            
            $reader = IOFactory::createReader($inputFileType);            
            $reader->setReadDataOnly(false);            
            $spreadsheet = $reader->load($filePath);            
            $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
            $data = $sheet->toArray();
            $p1 = new SingleVisitParser($data, "svp");
            $p2 = new ClassesWithTrainerParser($data, "cwt");
            $p3 = new HolidaysParser($data, "hdp");
            $p4 = new ProductsParser($data, "pps");
            $p5 = new SubscriptionsParser($data, "spp");

            foreach($data as $idx => $row) {
                $ra = SingleVisitParser::getSingleValue($row);
                $ra = mb_strtolower(str_replace(' ', '', $ra));                
                if ($ra == 'разовоепосещение') {
                   $p1->addTab($idx);                   
                } elseif ($ra == 'занятиястренером') {
                    $p2->addTab($idx);
                } elseif ($ra == 'праздникиимероприятия') {
                    $p3->addTab($idx);
                } elseif ($ra == 'товарыидоп.услуги') {
                    $p4->addTab($idx);
                } elseif ($ra == 'абонементы') {
                    $p5->addTab($idx);
                }

            }
            echo $p5->getTabs();
            // запись хэша
            //file_put_contents($hashPath, $fileHash);
            echo('<pre>');
            var_dump($data);
            echo('</pre>');
        }
    }
}
