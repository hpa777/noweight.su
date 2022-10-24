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
    <div class="wrapper">
        <div class="main-part">
                      
            <div class="container-fl footer-pad">
                <div class="schedule">


<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
if (!defined('MODX_BASE_PATH')) {
    $modx_base_path= __DIR__.'/';    
    define('MODX_BASE_PATH', $modx_base_path);    
}
require_once (MODX_BASE_PATH . 'vendor/autoload.php');

use \PriceParser\ScheduleWidget;
$time = time();
ScheduleWidget::getSchedule($time);
?>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>
