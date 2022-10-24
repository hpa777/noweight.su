<?php
namespace PriceParser\Tools;

class DateTool {

    public static function getWeekRangeByDate($ts) {        
        $start = date('w', $ts) == 1 ? $ts : strtotime('last monday', $ts);
        $end = strtotime('next sunday', $start);
        return [
            'start' => date('Y-m-d H:i:s', $start), 
            'end' => date('Y-m-d H:i:s', $end + 86399)
        ];
    }

    public static function getTimeRange($start, $end) {
        $s = date('H:i', strtotime($start));
        $e = date('H:i', strtotime($end));
        return $s . '-' . $e;
    }

    static $days = ["вс", "пн", "вт", "ср", "чт", "пт", "сб"];

    static $months = [
        '01' => 'Января',
        '02' => 'Февраля',
        '03' => 'Марта',
        '04' => 'Апреля',
        '05' => 'Мая',
        '06' => 'Июня',
        '07' => 'Июля',
        '08' => 'Августа',
        '09' => 'Сентября',
        '10' => 'Октября',
        '11' => 'Ноября',
        '12' => 'Декабря'
    ];

    public static function getDayOfWeek($date) {        
        $time = strtotime($date);        
        $day = date('w', $time);
        $class = $day == 0 || $day == 6 ? "red" : "blue";
        $d = self::$days[$day];
        $dw = "<span class=\"{$class}\">{$d}</span>";
        $d = date('j', $time);
        $m = self::$months[date('m', $time)];
        return "<div class=\"schedule__date\">{$dw}{$d} {$m}</div>\n";
    }
}