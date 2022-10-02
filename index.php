<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimal-ui">
<script defer="defer" src="/js/manifest.js"></script>
<script defer="defer" src="/js/vendor.js"></script>
<script defer="defer" src="/js/app.js"></script>
<link href="/app/css/app.css" rel="stylesheet"></head>
<body>
    <div class="wrapper wrapper--min">
        <div class="main-part">
                      
            
<?

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
if (!defined('MODX_BASE_PATH')) {
    $modx_base_path= __DIR__.'/';    
    define('MODX_BASE_PATH', $modx_base_path);    
}
require_once (MODX_BASE_PATH . 'vendor/autoload.php');

use \PhpOffice\PhpSpreadsheet\IOFactory;
use PriceParser\PriceWidget;

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
            $reader->setReadDataOnly(true);            
            $spreadsheet = $reader->load($filePath);            
            $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
            $data = $sheet->toArray();
            
            $res = PriceWidget::makeWidget($data);            
            // запись хэша
            //file_put_contents($hashPath, $fileHash);
            
        }
    }
}
?>
          

        


        


        <div class="bg-blue" style="background-color: brown;">
            <div class="container-fl section">
                <div class="h1 h1--white">ЦЕНЫ</div>
                <?=$res?>
                <div class="button-center">
                    <a href="" class="button button--hover-opacity">в раздел</a>
                </div>
            </div>
        </div> 
        
        
        


        


        

                
        </div>
        
    </div>
</body>
</html>
