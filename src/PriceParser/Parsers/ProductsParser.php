<?
namespace PriceParser\Parsers;

class ProductsParser extends AbstractParserClass {

    private const KEY = 'pdp';

    private const NAME = "ТОВАРЫ И ДОП. УСЛУГИ";    
    
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

    const IMAGE_DIR = 'upload/price/images/';
    
    protected function makeTabs($rowId) {
        $section = self::getClearText(self::getSingleValue($this->data[++$rowId]));
        $tab = "";  
        while (count(array_diff($this->data[++$rowId], ['']))) {
            switch($section) {
                case 'товары' : 
                    $tab.= $this->makeProductRow($this->data[$rowId]);
                    break;
                case 'услуги' :
                    $tab.= $this->makeServiceRow($this->data[$rowId]);
                    break;
            }                       
        }    
        $tab = "<div class=\"price\">\n{$tab}</div>";    
        $tab.= $this->makeFoot(++$rowId);
        return $tab;
    }

    private function makeProductRow($row) {
        if (self::getClearText($row[0]) == 'фото') return '';
        $res = "<div class=\"price__row\">\n";
        for($i = 0; $i < count($row); $i++) {
            if ($i == 0) {
                if (!empty($row[$i]) && file_exists(MODX_BASE_PATH . self::IMAGE_DIR . $row[$i])) {
                    $img = '/' . self::IMAGE_DIR . $row[$i];
                    $res.= "<div class=\"price__col price__col--img\">
                        <div class=\"price__img\">
                            <img class=\"fit-cover\" src=\"{$img}\">
                        </div>
                    </div>";
                } else {
                    $res.= "<div class=\"price__col\"></div>";
                }
            } elseif ($i == 1) {
                $res.= "<div class=\"price__col price__col--head\">{$row[$i]}</div>";
            } elseif (is_numeric($row[$i])) {
                $link = !empty($row[$i + 1]) && !self::isLink($row[$i + 1]) ? 
                    "<a href=\"{$row[$i + 1]}\" class=\"button button--hover-blue price__buy\">Оплатить</a>" : '';
                $res.= "<div class=\"price__col price__col--pr\">{$row[$i]} ₽{$link}</div>";
                break;
            }
        }
        return $res . '</div>';
    }

    private function makeServiceRow($row) {        
        if (empty($row[1])) {
            return '';
        }
        $res = "<div class=\"price__row\">\n";
        $descr = '';
        $i = 4;        
        if (!empty($row[$i])) {            
            $descr = strlen($row[$i]) > 30 ? 
            "<div class=\"price__descr\">{$row[$i]}</div>" 
            : "<span class=\"price__dop\">({$row[$i]})</span>";
        }
        $res.= "<div class=\"price__col price__col--head\">{$row[1]}{$descr}</div>";
        $i = 5;
        if (is_numeric($row[$i])) {
            $link = !empty($row[$i + 1]) && !self::isLink($row[$i + 1]) ? 
                "<a href=\"{$row[$i + 1]}\" class=\"button button--hover-blue price__buy\">Оплатить</a>" : '';
            $res.= "<div class=\"price__col price__col--pr\">{$row[$i]} ₽{$link}</div>";            
        }
        return $res . "</div>\n";
    }

}