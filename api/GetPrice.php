<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
if (!defined('MODX_BASE_PATH')) {
    $modx_base_path = dirname(__DIR__) . '/';    
    define('MODX_BASE_PATH', $modx_base_path);    
}
require_once (MODX_BASE_PATH . 'vendor/autoload.php');

use \PhpOffice\PhpSpreadsheet\IOFactory;
use PriceParser\Parsers\AbstractParserClass;
use PriceParser\Parsers\SingleVisitCalcParser;
use PriceParser\Parsers\HolidaysCalcParser;

$priceDir = MODX_BASE_PATH . 'upload/price/';
$dataDir = MODX_BASE_PATH . 'price_data/';
$inputFileType = IOFactory::READER_ODS;

$files = scandir(rtrim($priceDir, '/'));
foreach ($files as $file) {
    $filePath = $priceDir . $file;
    if (is_file($filePath) && (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) == strtolower($inputFileType))) {  
        $fileHash = hash('md5', file_get_contents($filePath));
        $hashPath = $dataDir . $file . 'hash';
        if (!file_exists($hashPath) || ($fileHash != file_get_contents($hashPath))) {
            $result = createPrice($inputFileType, $filePath);
            //$modx->cacheManager->set('price_widget', $result, 7200);
            file_put_contents($hashPath, $fileHash);
        } else {
            $result = null; //$modx->cacheManager->get('price_widget');
            if (!$result) {
                $result = createPrice($inputFileType, $filePath);
                //$modx->cacheManager->set('price_widget', $result, 7200);
            } 
        }
    }
}
/*
echo "<pre>";
var_dump($result);
echo "</pre>";
*/
echo json_encode($result);

function createPrice($inputFileType, $filePath) {
    $reader = IOFactory::createReader($inputFileType);            
    $reader->setReadDataOnly(true);            
    $spreadsheet = $reader->load($filePath);            
    $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
    $data = $sheet->toArray();
    $parsers = [
        new SingleVisitCalcParser($data),
        new HolidaysCalcParser($data)            
    ];    
    foreach($data as $idx => $row) {
        $ra = AbstractParserClass::getSingleValue($row);
        $ra = mb_strtolower(str_replace(' ', '', $ra)); 
        foreach($parsers as $parser) {
            if ($parser->getClearName() == $ra) {
                $parser->addTab($idx);
                break;
            }
        }             
    }
    $result = [];
    foreach($parsers as $parser) {
        $result[$parser->getKey()] = $parser->getData();
    }
    return $result;
}