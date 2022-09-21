<?
namespace PriceParser\Parsers;

class SubscriptionsParser extends AbstractParserClass {    
    
    private const KEY = 'ssp';

    private const NAME = "АБОНЕМЕНТЫ";    
    
    private string $clearName;

    function __construct(array $data)
    {
        parent::__construct($data);
        $this->clearName = mb_strtolower(str_replace(' ', '', self::NAME));
    }

    public static function gatName() {
        return self::NAME;
    }

    public function getClearName() {
        return $this->clearName;
    }

    protected function getKey() {
        return self::KEY;
    }

    protected function makeTabs($rowId) {        
        $tab = $this->makeHeadRow($this->data[$rowId + 2]);
        $rows = [];
        for ($i = $rowId + 3; $i < count($this->data); $i = $i + 2) {            
            if (!self::getSingleValue($this->data[$i])) {
                break;
            }            
            $rows[] = $this->makeRow($i);
        }
        for ($ri = 0; $ri < count($rows); $ri++) {
            $class = $rows[$ri]["hasDescr"] && $ri + 1 < count($rows) && !$rows[$ri + 1]["hasDescr"] ? " price__col--rowspan" : "";
            $tab.= str_replace("%class%", $class, $rows[$ri]["row"]);            
        }
        $tab = "<div class=\"price\">\n{$tab}</div>";
        $tab.= $this->makeFoot(++$i);        
        return $tab;
    }
    
    
    private function makeHeadRow($row) {
        $this->excludeCols = [];
        $res = "<div class=\"price__row price__row--head\">\n<div class=\"price__col\"></div>";
        for($i = 2; $i < count($row); $i++) {
            if (empty($row[$i])) {                
                break;
            } else {
                $res.= "<div class=\"price__col\">{$row[$i]}</div>\n";
            }
        }
        return $res . "</div>";
    }

    private function makeRow($rowId) {          
        $res = "<div class=\"price__row price__row--border\">\n";
        $res.= "<div class=\"price__col price__col--head%class%\">";
        if (!empty($this->data[$rowId][0])) {
            $res.= "<span>{$this->data[$rowId][0]}</span>\n";
        }        
        $res.= "</div>";
        $days = [];
        $times = [];
        for ($i = $rowId; $i < $rowId + 2; $i++) {
            $days[] = $this->data[$i][2];
            $times[] = $this->data[$i][3];
        }
        $days = implode("<br>", $days);
        $times = implode("<br>", $times);
        $res.= "<div class=\"price__col price__col--valign price__col--txt1\">{$days}</div>\n";
        $res.= "<div class=\"price__col price__col--valign price__col--txt2\">{$times}</div>\n";
        $res.= "<div class=\"price__col price__col--valign price__col--pr\"><span>{$this->data[$rowId][4]} ₽</span></div>\n";
        $res.= "<div class=\"price__col price__col--valign price__col--head\">";
        if (!empty($this->data[$rowId][5]) && !self::isLink($this->data[$rowId][5])) {
            $res.= "<a href=\"{$this->data[$rowId][5]}\" class=\"button button--hover-blue price__buy price__buy--static\">Оплатить</a>";
        }
        $res.= "</div>\n</div>";
        return ["row" => $res, "hasDescr" => !empty($this->data[$rowId][0])];
    }
}