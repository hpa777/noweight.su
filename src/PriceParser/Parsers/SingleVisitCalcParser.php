<?
namespace PriceParser\Parsers;

class SingleVisitCalcParser extends AbstractParserClass {   
    
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

    public function getKey() {
        return self::KEY;
    }

    public function getData() {
        $result = [];
        foreach ($this->menu as $key => $value) {
            $result[] = [
                "place" => $key,
                "prices" => $value
            ];
        }        
        return $result;
    }

    protected function makeTabs($rowId) {
        $times = $this->makeHeadRow($this->data[$rowId + 2]);
        $res = [];
        $this->makeRow($this->data[$rowId + 3], $times, $res);
        $this->makeRow($this->data[$rowId + 4], $times, $res);
        return $res;        
    }

    private array $excludeCols;
    
    private function makeHeadRow($row) {
        $this->excludeCols = [];
        $res = [];
        for($i = 1; $i < count($row); $i++) {
            if (empty($row[$i])) {
                $this->excludeCols[] = $i;
            } else if (self::isLink($row[$i])) {
                continue;
            } else {
                $res[] = $row[$i];
            }
        }
        return $res;
    }

    private function makeRow(array $row, array $times, array &$result) {
        $day = "";
        $c = 0;
        $res = [];                
        for($i = 0; $i < count($row); $i++) {
            if (in_array($i, $this->excludeCols)) {
                continue;
            } elseif ($i == 0) {
                $day = strcasecmp($row[$i], "Будний") === 0 ? "weekday" : "dayoff";
            } elseif (empty($row[$i])) {
                $c++;
            } elseif (is_numeric($row[$i])) {                
                $timeStr = $times[$c];
                if (strcasecmp($timeStr, "Безлимит") === 0) {
                    $t = 5;                    
                } else {
                    $ta = explode(' ', $timeStr);                    
                    if (count($ta)) {
                        $t = (int)$ta[0];
                    }                    
                }
                $res[] = [
                    "time" => $timeStr,
                    "price" => $row[$i],
                    "tn" => $t
                ];
                $c++;
                $i++;
            }
        } 
        $result[$day] = $res;               
    }
}