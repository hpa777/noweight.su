<?

namespace PriceParser;

use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

use PriceParser\Tools\DateTool;


class ScheduleWidget
{

    public static function getSchedule($time)
    {
        $url = 'https://cloud.1c.fitness/app03/2367/hs/api/v3/classes/';
        $userName = 'sisad@bk.ru';
        $passWord = 'Admin123';
        $apikey = '2665909e-2211-11ed-409c-0050568369e4';
        $clubId = 'acdbdb12-1823-11ea-bbc0-0050568bac14';

        $week = DateTool::getWeekRangeByDate($time);
        $client = new Client();
        $response = $client->get($url, [
            'auth' => [$userName, $passWord],
            'headers' => [
                'Content-Type' => 'application/json',
                'apikey' => $apikey
            ],
            'query' => [
                'club_id' => $clubId,
                'start_date' => $week['start'],
                'end_date' => $week['end']
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        if (!$result['result']) {
            return;
        }
        $arr = [];
        foreach ($result['data'] as $item) {
            $key = date('Y-m-d', strtotime($item["start_date"]));
            if (!array_key_exists($key, $arr)) {
                $arr[$key] = [];
            }
            $card = [
                'time' => DateTool::getTimeRange($item["start_date"], $item["end_date"]),
                'duration' => $item["duration"],
                'title' => $item["service"]["title"]
            ];
            if ($item["capacity"] > 0) {
                $card['capacity'] = "Свободно " . ($item["capacity"] - $item["booked"]);
            } else {
                $card['capacity'] = "Занято " . $item["booked"];
            }
            if (is_array($item["employee"])) {
                $card['employee'] = $item["employee"]["name"];
            }
            if (is_array($item["room"])) {
                $card['room'] = $item["room"]["title"];
            }
            $arr[$key][] = $card;
        }
        $cnt = "";
        if (count($arr)) {
            foreach ($arr as $date => $items) {
                $row = "<div class=\"schedule__row row-fl\">\n";
                $row .= DateTool::getDayOfWeek($date);
                foreach ($items as $item) {
                    $app = "<div class=\"appointment\">\n";
                    $app .= "<div class=\"appointment__time\">{$item['time']}</div>\n";
                    $app .= "<div class=\"appointment__duration\">{$item['duration']} мин.</div>\n";
                    $app .= "<div class=\"appointment__title\">{$item['title']}</div>\n";
                    $app .= "<div class=\"appointment__employee\">{$item['employee']}</div>\n";
                    $app .= "<div class=\"appointment__room\">
                            <svg class=\"blue\"><use xlink:href=\"/images/sprite.svg#point\"></use></svg>
                            <span>{$item['room']}</span>
                        </div>\n";
                    $app .= "<div class=\"appointment__capacity\">{$item['capacity']}</div>\n";
                    $app .= "</div>\n";
                    $row .= $app;
                }
                $row .= "</div>\n";
                $cnt .= $row;
            }
        } else {
            $cnt = "<div class=\"schedule__empty\">На этой неделе расписания пока нет.</div>";
        }
        $prev = $time - 604800;
        $next = $time + 604800;

        $cnt .= "<div class=\"schedule__pagi row-fl\">\n";
        $cnt .= "<button data-tm=\"{$prev}\" class=\"schedule__button row-fl aic schedule__button--left\">\n
            <svg><use xlink:href=\"/images/sprite.svg#arrow\"></use></svg>\n
            <span>предыдущая неделя</span>\n
        </button>\n";
        if (count($arr)) {
            $cnt .= "<button data-tm=\"{$next}\" class=\"schedule__button row-fl aic schedule__button--right\">\n                        
            <span>следующая неделя</span>\n
            <svg><use xlink:href=\"/images/sprite.svg#arrow\"></use></svg>\n
        </button>\n";
        }
        $cnt .= "</div>\n";
        echo $cnt;
    }
}
