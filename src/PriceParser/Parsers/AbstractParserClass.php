<?php
namespace PriceParser\Parsers;

abstract class AbstractParserClass {

    protected array $data;    

    protected array $menu;     

    function __construct(array $data)
    {
        $this->data = $data;        
        $this->menu = [];       
    }    

    public function addTab(int $rowId) {               
        $this->menu[self::getSingleValue($this->data[$rowId + 1])] = $this->makeTabs($rowId);
    }

    abstract protected function makeTabs(int $rowId);

    abstract public static function gatName();

    abstract public function getClearName();

    abstract protected function getKey();
    
    public function getTabs()
    {
        $res = "";
        $tabs = "";
        if (count($this->menu) > 1) {        
            $res .= "<ul class=\"tabs-menu row-fl jcsb\">\n";
            $idx = 0;
            foreach($this->menu as $mi => $tab) {
                $class = $idx == 0 ? ' active' : '';
                $id = $this->getKey() . '_' . (++$idx);                
                $res.= "<li class=\"tabs-menu__item{$class}\" data-tab=\"{$id}\">{$mi}</li>\n";
                $tabs.= "<div id=\"{$id}\" class=\"tabs__item{$class}\">\n{$tab}</div>\n";
            }
            $res.= "</ul>\n";
            return "<div class=\"tabs child-tabs\">{$res}{$tabs}</div>";
        } elseif (count($this->menu) == 1) {
            $tabs = array_shift($this->menu);
            return $res . $tabs;
        }        
    }

    protected function makeFoot(int $rowId) {
        $row = array_diff($this->data[$rowId], ['']);
        if (count($row)) {
            $txt = array_shift($row);
            $res = "<div class=\"price__foot\">\n<div class=\"price__foot__txt\">{$txt}</div>\n";
            if ($link = array_shift($row)) {
                $res.= "<a href=\"{$link}\" class=\"button button--hover-opacity\">Подробнее</a>\n";
            } 
            return $res . "</div>";
        }
    }
    
    public static function getSingleValue($arr) {
        $a = array_diff($arr, ['']);
        return array_shift($a);
    }

    protected static function isLink($txt) {
        return (mb_strripos($txt, "ссылка") !== false || mb_strripos($txt, "оплат") !== false);
    }

    public static function getClearText($txt) {
        return mb_strtolower(str_replace(' ', '', $txt));
    }
    
}