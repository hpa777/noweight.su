<?

namespace PriceParser;

use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

use PriceParser\Tools\DateTool;


class ScheduleWidget
{

    public static function getSchedule()
    {
        $url = 'https://cloud.1c.fitness/app03/2367/hs/api/v3/classes/';
        $userName = 'sisad@bk.ru';
        $passWord = 'Admin123';
        $apikey = '2665909e-2211-11ed-409c-0050568369e4';
        $clubId = 'acdbdb12-1823-11ea-bbc0-0050568bac14';

        $range = DateTool::getQueryTimeRange();        
        $client = new Client();
        $response = $client->get($url, [
            'auth' => [$userName, $passWord],
            'headers' => [
                'Content-Type' => 'application/json',
                'apikey' => $apikey
            ],
            'query' => [
                'club_id' => $clubId,
                'start_date' => $range['start'],
                'end_date' => $range['end']
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        if (!$result['result']) {
            return;
        }
        $schedule = [];
        foreach($result['data'] as $item) {
            $time = strtotime($item["start_date"]);
            $week = date("W", $time);
            if (!array_key_exists($week, $schedule)) {
                $schedule[$week] = [];
            }
            $hour = date("H", $time);
            if (!array_key_exists($hour, $schedule[$week])) {
                $schedule[$week][$hour] = [];
                $year = date("Y", $time);
                $head = "";
                for($day = 1; $day <= 7; $day++) {
                    $t = strtotime($year."W".$week.$day);
                    if (!isset($schedule[$week]['head'])) {
                        $head .= DateTool::getDayOfWeek($t);
                    }                    
                    $schedule[$week][$hour][date("d", $t)] = [];
                }
                if (!empty($head)) {
                    $schedule[$week]['head'] = $head;
                }
            }
            $day = date("d", $time);

            $card = [
                'date' => date('d.m.Y', strtotime($item["start_date"])),
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
            $schedule[$week][$hour][$day][] = $card;
        }
        foreach($schedule as &$week) {
            ksort($week);
        }
        if (count($schedule)) {
            $cnt = "<div class=\"schedule\">\n";
            foreach ($schedule as $k1 => $week) {                                
                $weekStr = "<div class=\"schedule__week\">\n";
                                
                foreach ($week as $hour => $row) {
                    if ($hour == "head") {
                        $weekStr .= "<div class=\"schedule__row row-fl jcsb\">\n{$row}</div>\n";
                        continue;
                    }
                    $rowStr = "<div class=\"schedule__row row-fl jcsb\">
                    <div class=\"schedule__time schedule__time--left\">{$hour}:00</div>
                        <div class=\"schedule__time schedule__time--right\">{$hour}:00</div>\n";
                    
                    foreach ($row as $col) {
                        $colStr = "<div class=\"schedule__col\">\n";   
                        foreach ($col as $item) {
                            $data = json_encode($item);
                            $app = "<div class=\"appointment show-popup\" data-options='{$data}' data-pid=\"schedule-form\">\n";
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
                            $colStr .= $app;
                        }                        
                        $colStr .= "</div>\n";
                        $rowStr .= $colStr;
                    }                    
                    $rowStr .= "</div>\n";
                    $weekStr .= $rowStr;
                }                
                $weekStr .= "</div>\n";
                $cnt .= $weekStr;                 
                               
            }
            $cnt .= "</div>\n";
        } else {
            $cnt = "<div class=\"schedule__empty\">На этой неделе расписания пока нет.</div>";
        }                
        echo $cnt;
    }
}
