<?
namespace PriceParser;

use PriceParser\Parsers\SingleVisitParser;
use PriceParser\Parsers\ClassesWithTrainerParser;
use PriceParser\Parsers\HolidaysParser;
use PriceParser\Parsers\ProductsParser;
use PriceParser\Parsers\SubscriptionsParser;

class PriceWidget {

    public static function makeWidget(array $data) {
        $parsers = [
            new SingleVisitParser($data),
            new ClassesWithTrainerParser($data),
            new HolidaysParser($data),
            new SubscriptionsParser($data),
            new ProductsParser($data)            
        ];
        
        foreach($data as $idx => $row) {
            $ra = SingleVisitParser::getSingleValue($row);
            $ra = mb_strtolower(str_replace(' ', '', $ra)); 
            foreach($parsers as $parser) {
                if ($parser->getClearName() == $ra) {
                    $parser->addTab($idx);
                    break;
                }
            }             
        }

        $menu = "";
        $tabs = "";
        foreach($parsers as $key => $parser) {
            $tab = $parser->getTabs();
            if (!empty($tab)) {
                $class = empty($menu) ? " active" : "";
                $mi = $parser::gatName();
                $menu.= "<li class=\"tabs-menu__item{$class}\" data-tab=\"tab_{$key}\">{$mi}</li>";
                $tabs.= "<div id=\"tab_{$key}\" class=\"tabs__item{$class}\">{$tab}</div>";
            }
        }
        $res = "<div class=\"tabs parent-tabs\">\n<ul class=\"tabs-menu row-fl jcsb\">\n";
        $res.= $menu . "</ul>\n<div class=\"parent-tabs__cnt\">\n" . $tabs . "</div></div>";
        return $res;
    }
}