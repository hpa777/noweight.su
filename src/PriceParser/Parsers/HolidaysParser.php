<?
namespace PriceParser\Parsers;

class HolidaysParser extends AbstractParserClass {

    private const KEY = 'hdp';

    private const NAME = "ПРАЗДНИКИ И МЕРОПРИЯТИЯ";    
    
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
        $head = $this->makeHeadRow($this->data[++$rowId]);
        $tab = "";
        while ($c = count(array_diff($this->data[++$rowId], ['']))) {                    
            if ($c == $this->count) {
                $tab.= $head;                
                $tab.= $this->makeRow($rowId);
            }
        }
        $tab = "<div class=\"price price--3\">\n{$tab}</div>";    
        $tab.= $this->makeFoot(++$rowId);
        return $tab;
    }
    
    private $count;
    
    private function makeHeadRow($row) {
        $res = "<div class=\"price__row price__row--head\">\n<div class=\"price__col\"></div>";
        for ($i = 2; $i < count($row); $i++) {
            if (empty($row[$i])) {
                $this->count = $i;
                break;
            } elseif (self::isLink($row[$i])) {
                continue;
            } else {
                $res.= "<div class=\"price__col\">{$row[$i]}</div>\n";
            }
        }
        return $res . "</div>";
    }

    private function makeRow(int $rowId) {
        $row = $this->data[$rowId];
        $res = "<div class=\"price__row\">\n" . $this->makeHolidayCol($rowId);        
        for ($i = 2; $i < $this->count; $i++) {
            if (is_numeric($row[$i])) {
                $link = !empty($row[$i + 1]) && !self::isLink($row[$i + 1]) ? 
                    "<a href=\"{$row[$i + 1]}\" class=\"button button--hover-blue price__buy\">Оплатить</a>" : '';
                $res.= "<div class=\"price__col price__col--pr\">{$row[$i]} ₽{$link}</div>";
            }
        }
        return $res . '</div>';
    }

    private function makeHolidayCol(int $rid) {
        $name = $this->data[$rid][0];
        $label = !empty($this->data[$rid][1]) ? "<span class=\"holiday-col__label\">{$this->data[$rid][1]}</span>" : '';
        $txt = !empty($this->data[++$rid][0]) ? "<div class=\"holiday-col__txt\">{$this->data[$rid][0]}</div>\n" : '';
        $link = !empty($this->data[++$rid][1]) && !self::isLink($this->data[$rid][1]) ? "<a href=\"{$this->data[$rid][1]}\" class=\"button button--hover-blue holiday-col__button\">{$this->data[$rid][0]}</a>" : '';
        return "<div class=\"price__col\">\n<div class=\"holiday-col\">\n<div class=\"holiday-col__title\">{$name}{$label}</div>\n{$txt}{$link}</div>\n</div>";
    }
}