<?
namespace PriceParser\Parsers;

class SingleVisitParser extends AbstractParserClass {   
    
    private const KEY = 'svp';

    private const NAME = "РАЗОВОЕ ПОСЕЩЕНИЕ";    
    
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
        $tab.= $this->makeRow($this->data[$rowId + 3]);
        $tab.= $this->makeRow($this->data[$rowId + 4]);
        $tab = "<div class=\"price\">\n{$tab}</div>";
        $tab.= $this->makeFoot($rowId + 6);
        return $tab;
    }

    private array $excludeCols;
    
    private function makeHeadRow($row) {
        $this->excludeCols = [];
        $res = "<div class=\"price__row price__row--head\">\n";
        for($i = 0; $i < count($row); $i++) {
            if (empty($row[$i])) {
                $this->excludeCols[] = $i;
            } else if (self::isLink($row[$i])) {
                continue;
            } else {
                $res.= "<div class=\"price__col\">{$row[$i]}</div>\n";
            }
        }
        return $res . "</div>";
    }

    private function makeRow(array $row) {        
        $res = "<div class=\"price__row\">\n";
        for($i = 0; $i < count($row); $i++) {
            if (in_array($i, $this->excludeCols)) {
                continue;
            } elseif ($i == 0) {
                $res.= "<div class=\"price__col price__col--head\">{$row[$i]}</div>\n";
            } elseif (empty($row[$i])) {
                $res.= "<div class=\"price__col price__col--pr\"></div>\n";
            } elseif (is_numeric($row[$i])) {
                $link = !empty($row[$i + 1]) && !self::isLink($row[$i + 1]) ? 
                    "<a href=\"{$row[$i + 1]}\" class=\"button button--hover-blue price__buy\">Оплатить</a>" : '';
                $res.= "<div class=\"price__col price__col--pr\">{$row[$i]} ₽{$link}</div>";
                $i++;
            }
        }        
        return $res . '</div>';
    }
}