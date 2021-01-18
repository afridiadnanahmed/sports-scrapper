<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\AttendoEmail;
use Mail;
use App\User;
use DOMDocument;
use DOMXPath;
use File;

class FootballController extends Controller {

    public function __construct() {

        DB::enableQueryLog();
    }

    public function newsList(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        $url = 'http://www.espn.in/football/';
        $html = $this->curl($url);

        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $news_xpath = new DOMXPath($doc);

            $news = $news_xpath->query("//article[contains(@class, 'contentItem')][section[contains(@class,'contentItem__content contentItem__content--story has-image contentItem__content--collection')]]");

            $newsList = array();
            if ($news->length > 0) {
                foreach ($news as $n) {
                    $elements = $n->getElementsByTagName('section');
                    foreach ($elements as $childNode) {
                        $data['newsLink'] = $childNode->getElementsByTagName('a')->item(0)->getAttribute("href");
                        $data['title'] = $childNode->getElementsByTagName('h1')->item(0)->nodeValue;
                        $data['desc'] = $childNode->getElementsByTagName('p')->item(0)->nodeValue;
                        $data['img'] = $childNode->getElementsByTagName('img')->item(0)->getAttribute("data-default-src");
                    }
                    $newsList[] = $data;
                }
            }
        }

        return response()->json(array('newslist' => $newsList));
    }

    public function newsDetail(Request $request) {


        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        if (empty($request->input('news_link'))) {
            return response()->json(array('status' => false, 'message' => 'News Link is empty'));
        }
        $url = 'http://www.espn.in' . $request->input('news_link');
//        $url = 'http://www.espn.in/football/club/barcelona/83/blog/post/3208604/four-goal-messi-was-perfect-as-barcelona-cruise-to-6-1-win-over-eibar';

        $html = $this->curl($url);

        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $news_xpath = new DOMXPath($doc);


            $news = $news_xpath->query("//article[contains(@data-src, '" . $request->input('news_link') . "')]");

            if ($news->length > 0) {
                $data = array();
                foreach ($news as $n) {
                    $data['title'] = trim(preg_replace('/\s+/', ' ', $n->getElementsByTagName('header')->item(0)->nodeValue));
                    $data['image'] = trim(explode(',', $n->getElementsByTagName('aside')->item(0)->getElementsByTagName('source')->item(0)->getAttribute("data-srcset"))[0]);

                    $elements = $n->getElementsByTagName('p');
                    $data['details'] = '';
                    foreach ($elements as $p) {
//                        $b = $p->getElementsByTagName('b');
                        if (strlen(trim(preg_replace('/\s+/', ' ', $p->nodeValue))) == 0) {
                            continue;
                        }
                        if ($p->getElementsByTagName('b')->length > 0) {
                            $heading = $p->getElementsByTagName('b')->item(0)->nodeValue;
                            $data['details'] .= '[HEADING]' . $heading . '[/HEADING]';
                            $para = preg_replace('/\s+/', ' ', str_replace($heading, '', $p->nodeValue));
                            if ($para != '') {
                                $data['details'] .= '[PARA]' . trim(preg_replace('/\s+/', ' ', str_replace($heading, '', $para))) . '[/PARA]';
                            }
                        } else {
                            $data['details'] .= '[PARA]' . trim(preg_replace('/\s+/', ' ', $p->nodeValue)) . '[/PARA]';
                        }
                    }
                }
            }
        }

        return response()->json(array('newsDetail' => $data));
    }

    public function cupScores(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }

        $url = 'http://www.espnfc.us/scores';

        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $domXpath = new DOMXPath($doc);


            $leagues = $domXpath->query("//div[contains(@id,'score-leagues')]//div[contains(@class,'score-league')]");
 

            if ($leagues->length > 0) {
                $series = array();


                $i = 1;
                foreach ($leagues as $league) {
                    $trophy = array();
                    $matchesBlocks = $domXpath->query("//div[contains(@id,'score-leagues')]//div[contains(@class,'score-league')][" . $i . "]//div[contains(@class, 'score-box')]");


                    $trophy['category'] = trim($league->getElementsByTagName('h4')->item(0)->getElementsByTagName('a')->item(0)->nodeValue);


                    foreach ($matchesBlocks as $match) {
                        $data = array();
                        $data['matchLink'] = $match->getElementsByTagName("a")->item(0)->getAttribute('href');


                        $divs = $match->getElementsByTagName('div');
                        foreach ($divs as $div) {
                            $className = $div->getAttribute('class');
                            switch ($className) {
                                case 'team-names':
                                    $data['innings-info-1'] = $div->getElementsByTagName('span')->item(0)->nodeValue;
                                    $data['innings-info-2'] = $div->getElementsByTagName('span')->item(1)->nodeValue;
                                    break;

                                case 'team-scores':
                                    $data['team-1-score'] = $div->getElementsByTagName('span')->item(0)->nodeValue;
                                    $data['team-2-score'] = $div->getElementsByTagName('span')->item(1)->nodeValue;
                                    break;

                                case 'game-info':
                                    if ($div->getElementsByTagName('span')->length == 1) {
                                        $data['match-info'] = $div->getElementsByTagName('span')->item(0)->nodeValue;
                                    } else {

                                        $data['match-info'] = $div->getElementsByTagName('span')->item(0)->nodeValue;
                                        if (trim($div->getElementsByTagName('span')->item(0)->nodeValue) != '') {
                                            $data['match-info'] .= '-';
                                        }
                                        $data['match-info'] .= $div->getElementsByTagName('span')->item(1)->nodeValue;
                                    }
                                    break;

                                default:
                                    break;
                            }
                        }
                        $trophy['matches'][] = $data;
                    }
                    $i++;
                    $series[] = $trophy;
                }
            }
        }
//        dd($series);

        return response()->json(array('matches' => $series));
    }

    public function squad(Request $request) {

//        if (empty($request->input('device_token'))) {
//            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
//        }
//        if (empty($request->input('match_url'))) {
//            return response()->json(array('status' => false, 'message' => 'Match url empty'));
//        }

        $url = 'http://www.espn.in/football/match?gameId=490541';
//        $url = $request->input('match_url');

        $html = $this->curl($url);

        $commentory_doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $teamNames = array();

        if (!empty($html)) { //if any html is actually returned
            $commentory_doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $xpath = new DOMXPath($commentory_doc);

            $squads = $xpath->query("//div[contains(@class,'content-tab')]//tbody[position() mod 2 = 1]");

            $squadsTitles = $xpath->query("//span[contains(@class,'team-name-short')]");

            if ($squadsTitles->length > 0) {
                $t = array();

                $teamNames['team1'] = $squadsTitles->item(0)->nodeValue;

                $teamNames['team2'] = $squadsTitles->item(1)->nodeValue;
            }

            if ($squads->length > 0) {


                foreach ($squads as $j => $squad) {

                    $trs = $squad->getElementsByTagName('tr');
                    $team = array();
                    foreach ($trs as $tr) {

                        $team[]['name'] = trim(preg_replace('/\s+/', ' ', $tr->getElementsByTagName('td')->item(0)->getElementsByTagName('div')->item(1)->nodeValue));
                    }
                    $teamNames['team-' . ($j + 1) . '-players'] = $team;
                }
            }
        }

//        dd($teamNames);
        return response()->json(array('squad' => $teamNames));
    }

    public function commentary(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        if (empty($request->input('match_url'))) {
            return response()->json(array('status' => false, 'message' => 'Match url empty'));
        }
//
//        $url = 'http://www.espnfc.us/commentary?gameId=490662';
        $matchLink = explode('=', $request->input('match_url'));
        $url = 'http://www.espn.in/football/commentary?gameId=' . $matchLink[1];

        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $results = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $domXpath = new DOMXPath($doc);

            $commentary = $domXpath->query("//article[contains(@class, 'sub-module match-commentary')]//div[contains(@id,'match-commentary-1-tab-1')]//tr");
//           dd($commentary->length);
            $data = array();

            if ($commentary->length > 0) {

                foreach ($commentary as $mint) {
                    $result = array();
                    $result['time'] = trim(preg_replace('/\s+/', ' ', $mint->firstChild->nodeValue));
                    $result['description'] = trim(preg_replace('/\s+/', ' ', $mint->getElementsByTagName('td')->item(2)->nodeValue));
                    $results[] = $result;
                }
            }
        }
     
        return response()->json(array('commentary' => $results));
    }

    public function summary(Request $request) {
//
        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        if (empty($request->input('match_url'))) {
            return response()->json(array('status' => false, 'message' => 'Match url empty'));
        }

//        $url = 'http://www.espn.in/football/match?gameId=480853';
        $matchLink = explode('=', $request->input('match_url'));
        $url = 'http://www.espn.in/football/match?gameId=' . $matchLink[1];

        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $results = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $domXpath = new DOMXPath($doc);

            $domElements = $domXpath->query("//div[contains(@class, 'team-info players')]/ul[contains(@class,'goal icon-font-before icon-soccer-ball-before icon-soccerball')]");
            $squadsTitles = $domXpath->query("//span[contains(@class,'long-name')]");
            $squadFlags = $domXpath->query("//div[contains(@class,'team-info-logo')]");

            $teamNames = array();
            if ($squadsTitles->length > 0) {

                $teamNames['1'] = $squadsTitles->item(0)->nodeValue;

                $teamNames['2'] = $squadsTitles->item(1)->nodeValue;
            }

            $scores = $domXpath->query("//div[contains(@class,'score-container')]");
            $headingPath = $domXpath->query("//div[contains(@class,'game-details header')]");
            $tourHeading = trim(preg_replace('/\s+/', ' ', $headingPath->item(0)->nodeValue));

            $teamScores = array();
            if ($scores->length > 0) {

                $teamScores['1'] = trim(preg_replace('/\s+/', ' ', $scores->item(0)->nodeValue));

                $teamScores['2'] = trim(preg_replace('/\s+/', ' ', $scores->item(1)->nodeValue));
            }

            foreach ($teamNames as $tni => $tn) {
                $data = array();
                $data['name'] = $teamNames[$tni];
                $data['goals'] = $teamScores[$tni];
                $results['teamInfo']['team' . $tni] = $data;
                $results['teamInfo']['team' . $tni.'-flag'] = $squadFlags->item($tni-1)->getElementsByTagName('img')->item(0)->getAttribute('data-default-src');
            }


            if ($domElements->length > 0) {

                foreach ($domElements as $ei => $element) {
                    $data = array();
//                    $data['team' . ($ei + 1)]['name'] = $teamNames[$ei + 1];
//                    $data['team' . ($ei + 1)]['goals'] = $teamScores[$ei + 1];
//                    $results['teamInfo'][] = $data;

                    $lis = $element->getElementsByTagName('li');
                    foreach ($lis as $i => $li) {
                        $result = array();
                        $dataArr = explode('(', trim(preg_replace('/\s+/', ' ', $li->nodeValue)));
                        $result['player'] = $dataArr[0];
                        $result['time'] = trim($dataArr[1], ')');
                        $result['team'] = $teamNames[$ei + 1];
                        $results['summary'][] = $result;
                    }
                }
            }
        }

        $results['tourHeading'] = $tourHeading;
//        dd($results);
        return response()->json($results);
    }

    public function allSeries(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }



        $url = 'http://www.espnfc.us/scores';

        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $domXpath = new DOMXPath($doc);


            $elements = $domXpath->query("//div[contains(@id,'score-leagues')]//div[contains(@class,'score-league')]/h4");
//           echo $elements->length;exit;

            if ($elements->length > 0) {
                $series = array();


                $i = 1;
                foreach ($elements as $tournament) {
                    $trophy = array();
//                    $matchesBlocks = $domXpath->query("//div[contains(@id,'score-leagues')]//div[contains(@class,'score-league')][" . $i . "]//div[contains(@class, 'score-box')]");
//
//                    $trophy['category'] = trim($league->getElementsByTagName('h4')->item(0)->getElementsByTagName('a')->item(0)->nodeValue);

                    $trophy['title'] = $tournament->getElementsByTagName('a')->item(0)->nodeValue;
                    $link = $tournament->getElementsByTagName('a')->item(0)->getAttribute('href');
                    $trophy['seriesLink'] = str_replace('http://www.espnfc.us', '', $link);
                    $series[] = $trophy;
                }
            }
        }
//        dd($series);

        return response()->json(array('series' => $series));
    }

    public function seriesDetails(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }

        if (empty($request->input('series_url'))) {
            return response()->json(array('status' => false, 'message' => 'Match url empty'));
        }

        $url = 'http://www.espnfc.us' . $request->input('series_url');

        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $domXpath = new DOMXPath($doc);


            $matchesBlocks = $domXpath->query("//div[contains(@id,'score-leagues')]//div[contains(@class, 'score-box')]");

            $matches = array();
            if ($matchesBlocks->length > 0) {


                foreach ($matchesBlocks as $match) {
                    $data = array();
                    $data['matchLink'] = $match->getElementsByTagName("a")->item(0)->getAttribute('href');


                    $divs = $match->getElementsByTagName('div');
                    foreach ($divs as $div) {
                        $className = $div->getAttribute('class');
                        switch ($className) {
                            case 'team-names':
                                $data['innings-info-1'] = $div->getElementsByTagName('span')->item(0)->nodeValue;
                                $data['innings-info-2'] = $div->getElementsByTagName('span')->item(1)->nodeValue;
                                break;

                            case 'team-scores':
                                $data['team-1-score'] = $div->getElementsByTagName('span')->item(0)->nodeValue;
                                $data['team-2-score'] = $div->getElementsByTagName('span')->item(1)->nodeValue;
                                break;

                            case 'game-info':
                                if ($div->getElementsByTagName('span')->length == 1) {
                                    $data['match-info'] = $div->getElementsByTagName('span')->item(0)->nodeValue;
                                } else {

                                    $data['match-info'] = $div->getElementsByTagName('span')->item(0)->nodeValue;
                                    if (trim($div->getElementsByTagName('span')->item(0)->nodeValue) != '') {
                                        $data['match-info'] .= '-';
                                    }
                                    $data['match-info'] .= $div->getElementsByTagName('span')->item(1)->nodeValue;
                                }
                                break;

                            default:
                                break;
                        }
                    }
                    $matches[] = $data;
                }
            }
        }

//        dd($series);

        return response()->json(array('seriesMatches' => $matches));
    }

    public function top5(Request $request) {

        $result = array();

//        NEWS
        $newsData = $this->newsList($request);
        $newsList = json_decode(json_encode($newsData->getData()), TRUE);
        $result['newslist'] = array_slice($newsList['newslist'], 0, 5);

//        SERIES
        $allSeriesData = $this->allSeries($request);
        $allSeriesList = json_decode(json_encode($allSeriesData->getData()), TRUE);
        $result['series'] = array_slice($allSeriesList['series'], 0, 5);

//        matches
        $matchessData = $this->cupScores($request);
        $matchessList = json_decode(json_encode($matchessData->getData()), TRUE);
        $result['matches'] = array_slice($matchessList['matches'], 0, 5);
        foreach ($result['matches'] as $tour) {
            $tour['matches'] = $tour['matches'][0];
            $matches[] = $tour;
        }
        $result['matches'] = $matches;

        return response()->json(array('top5' => $result));
    }

    public function curl($url) {
        $ch = curl_init();
        $timeout = 0; //UNLIMITED
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

// Get URL content
        $html = curl_exec($ch);

        curl_close($ch);

        return $html;
    }

    //    public function cups(Request $request) {
//
//
////        if (empty($request->input('device_token'))) {
////            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
////        }
////        $siteUrl = 'http://www.espncricinfo.com';
//        $url = 'http://www.espn.in/football/story/_/id/18234550/football-leagues-competitions';
//
//        $html = $this->curl($url);
//
//        $cricket_doc = new DOMDocument();
//
//        libxml_use_internal_errors(TRUE); //disable libxml errors
//
//        if (!empty($html)) { //if any html is actually returned
//            $cricket_doc->loadHTML($html);
//
//            libxml_clear_errors(); //remove errors for yucky html
//
//            $footbal_series_xpath = new DOMXPath($cricket_doc);
//
//
//            $series = $footbal_series_xpath->query("//section[contains(@id, 'article-feed')]//div[contains(@class,'article-body')]/p");
//
//            if ($series->length > 0) {
//
//                $data = array();
//
//                foreach ($series as $row) {
//
//                    $data['img'] = $row->getElementsByTagName('img')->item(0)->getAttribute('src');
//                    $data['title'] = $row->nodeValue;
//                    $path = explode('/', $row->getElementsByTagName('a')->item(0)->getAttribute('href'));
//                    $data['cupLink'] = end($path);
//                    $cups[] = $data;
//                }
//            }
//        }
//        return response()->json(array('cups' => $cups));
//    }
}
