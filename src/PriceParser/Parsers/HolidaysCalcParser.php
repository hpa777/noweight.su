<?
namespace PriceParser\Parsers;

class HolidaysCalcParser extends AbstractParserClass {

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

    public function getKey() {
        return self::KEY;
    }
    
    public function getData() {
        return array_shift($this->menu);
    }

    protected function makeTabs($rowId) {
        $this->makeHeadRow($this->data[++$rowId]);
        $res = [];
        while ($c = count(array_diff($this->data[++$rowId], ['']))) {            
            if ($c == $this->count) {                
                $res[] = $this->makeRow($rowId);
            }
        }        
        return $res;
    }
    
    private $count;
    
    private function makeHeadRow($row) {        
        for ($i = 2; $i < count($row); $i++) {
            if (empty($row[$i])) {
                $this->count = $i;
                break;
            } elseif (self::isLink($row[$i])) {
                continue;
            } 
        }        
    }

    private function makeRow(int $rowId) {
        $name = $this->data[$rowId][0];
        $txt = !empty($this->data[$rowId + 1][0]) ? $this->data[$rowId + 1][0] : '';        
        $price = 0;        
        for ($i = 2; $i < $this->count; $i++) {
            if (is_numeric($this->data[$rowId][$i])) {
                $price = $this->data[$rowId][$i];
                break;                
            }
        }
        return [
            "name" => $name,
            "txt" => $txt,
            "price" => $price
        ];
    }
    
}