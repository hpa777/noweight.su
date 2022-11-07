<?
if (!defined('MODX_BASE_PATH')) {
    $modx_base_path = dirname(__DIR__);    
    define('MODX_BASE_PATH', $modx_base_path);    
}
require_once (MODX_BASE_PATH . '/vendor/autoload.php');
use PriceParser\Tools\DateTool;

if (!empty($_GET["date"])) {
    $dateStr = $_GET["date"];
    $d = date_create_from_format("d.m.Y", $dateStr);
    $year = $d->format("Y");
    $dw = $d->format("w");
    $holidays = DateTool::getHolidaysDateArray($year);
    $res = $dw == 0 || $dw == 6 || in_array($dateStr, $holidays) ? "dayoff" : "weekday";
    echo $res;
}