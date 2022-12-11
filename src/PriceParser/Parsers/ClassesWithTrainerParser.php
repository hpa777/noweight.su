<?
namespace PriceParser\Parsers;

class ClassesWithTrainerParser extends AbstractParserClass {
    
    private const KEY = 'cwtp';

    private const NAME = "ЗАНЯТИЯ С ТРЕНЕРОМ";    
    
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
        $head = $this->makeHeadRow($this->data[$rowId + 2]); 
        $tab = $head;      
        $tab.= $this->makeRow($this->data[$rowId + 4]);
        //$tab.= $head;        
        $tab.= $this->makeRow($this->data[$rowId + 5]);
        $tab = "<div class=\"price price--2\">\n{$tab}</div>";
        $tab.= $this->makeFoot($rowId + 7);
        return $tab;
    }   
    
    private function makeHeadRow($row) {
        $info = self::getSingleValue($row);
        return "<div class=\"price__row price__row--head\">
            <div class=\"price__col\">день</div>
            <div class=\"price__col price__col--colspan\">
                <span>{$info}</span>
            </div>                                     
        </div>\n";
    }

    private function makeRow(array $row) {        
        $res = "";
        for($i = 0; $i < count($row); $i++) {
            if ($i == 0) {                    
                $res.= "<div class=\"price__col price__col--head\">{$row[$i]}</div>\n";                    
            } elseif (is_numeric($row[$i])) {
                $res.="<div class=\"price__col price__col--pr\"><div class=\"price-withdescr row-fl aic\">\n";
                if (!empty($row[$i - 1]) && !self::isLink($row[$i - 1])) {  
                    $res.= "<a href=\"{$row[$i-1]}\" class=\"button button--hover-blue price__buy\">Оплатить</a>\n";
                }
                $res.="<span>{$row[$i]} ₽</span>\n";
                if (!empty($row[$i + 1])) {
                    $res.="<div class=\"price-withdescr__txt\">{$row[$i + 1]}</div>\n";
                }
                $res.="</div></div>\n";                    
            }
        }
        return "<div class=\"price__row\">\n{$res}</div>\n";        
    }

}