<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimal-ui">
<script defer="defer" src="/js/manifest.js"></script>
<script defer="defer" src="/js/vendor.js"></script>
<script defer="defer" src="/js/app.js"></script>
<link href="/css/app.css" rel="stylesheet"></head>
<body>
    <div class="wrapper">
        <div class="main-part">
                      
            <div class="container-fl footer-pad">
                


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

ScheduleWidget::getSchedule();
?>

<div class="form__popup" id="schedule-form">
            <div class="form form--spaceman animate__bounceIn" style="animation-duration:1s;animation-delay:0s;animation-iteration-count:1;visibility: visible;">
                <form method="post" class="form__body ajax-form">
                    <input type="hidden" name="template" value="schedule">                    
                    <div class="form__title">Записаться</div>
                    <div class="form__options">                        
                        <div class="appointment__time"><span class="option__date"></span> <span class="option__time"></span></div>                        
                        <div class="appointment__title"><span class="option__title"></span></div>
                        <div class="appointment__employee"><span class="option__employee"></span></div>
                        <div class="appointment__room"><span class="option__room"></span></div>
                    </div>
                    <div class="form__row">
                        <div class="form__group">
                            <input type="text" class="input-field phone-mask" name="phone" required
                                placeholder="Ваш телефон*"
                                data-inputmask="'mask':'+7(999)999-99-99'">
                            <div class="form__group__mes"></div>
                        </div>
                    </div>
                    <div class="form__row">
                        <div class="form__group">
                            <input type="text" class="input-field" placeholder="Ваше имя*" name="name" required>
                            <div class="form__group__mes"></div>
                        </div>
                    </div>                    
                    <div class="form__row mb--11">
                        <div class="form__group">
                            <textarea class="input-field" placeholder="Комментарий" name="message"></textarea>
                        </div>
                    </div>
                    <div class="form__row mb--11">
                        <div class="form__group">
                            <div class="checkbox row-fl aic">
                                <input id="cb4" type="checkbox" checked
                                    data-btnid="sendbtn4">
                                <label for="cb4"></label>
                                <span>Согласие на обработку персональных данных</span>
                            </div>
                        </div>
                    </div>
                    <div class="form__row form__row--center">
                        <button id="sendbtn4" type="submit" class="button button--hover-opacity">записаться</button>
                    </div>
                </form>
                <div class="form__close">&times;</div>            
            </div>
        </div>
                
            </div>
        </div>
        
    </div>
</body>
</html>
