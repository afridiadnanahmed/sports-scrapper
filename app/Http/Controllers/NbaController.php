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

class NbaController extends Controller {

    public function __construct() {

        DB::enableQueryLog();
    }

    public function top5(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }

        $newsList = array();
        $matches = array();

//top5 news
        $url = 'http://www.espn.com/nba/';
        $html = $this->curl($url);

        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $news_xpath = new DOMXPath($doc);

            $news = $news_xpath->query("//article[contains(@class, 'contentItem')][section[contains(@class,'contentItem__content contentItem__content--story has-image')]]");


            if ($news->length > 0) {
                foreach ($news as $nInd => $n) {
                    if ($nInd == 5)
                        break;
                    $elements = $n->getElementsByTagName('section');
                    foreach ($elements as $childNode) {
                        try {
                            $data['newsLink'] = $childNode->getElementsByTagName('a')->item(0)->getAttribute("href");
                            $data['title'] = $childNode->getElementsByTagName('h1')->item(0)->nodeValue;
                            $data['desc'] = $childNode->getElementsByTagName('p')->item(0)->nodeValue;
                            $data['img'] = $childNode->getElementsByTagName('img')->item(0)->getAttribute("data-default-src");
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                    $newsList[] = $data;
                }
            }
        }

//                        dd($newsList);
        $top5['newslist'] = $newsList;

//top5 matches
        $date = date('Y-m-d');
        $url = 'https://www.si.com/nba/scoreboard?date=' . $date;

        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $results = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $domXpath = new DOMXPath($doc);


            $noGames = $domXpath->query("//p[contains(@class, 'no-games-scheduled')]");
            if ($noGames->length > 0) {
                return response()->json(array('noMatches' => 'No Matches For Today.'));
            }
            $matches = $domXpath->query("//div[contains(@class, 'component game')]");

            if ($matches->length > 0) {

                foreach ($matches as $mInd => $match) {

                    if ($mInd == 5)
                        break;

                    $data = array();
                    $data2 = array(
                        'team1' => "",
                        'team2' => "",
                        'team1-score' => "",
                        'team2-score' => "",
//                        'team1-flag' => "",
//                        'team2-flag' => "",
                        'matchLink' => "",
                        'match-info' => ""
                    );
                    $divs = $match->getElementsByTagName('div');
                    foreach ($divs as $div) {
                        $className = $div->getAttribute('class');
                        switch ($className) {
                            case 'collapse-narrow float-right':
                                $data['matchLink'] = $div->getElementsByTagName('a')->item(0)->getAttribute('href');
                                break;

                            case 'team-city':
                                $tind = 1;
                                if (array_key_exists('team1', $data)) {
                                    $data['team' . ($tind + 1)] = trim(preg_replace('/\s+/', ' ', $div->nodeValue));
                                } else {
                                    $data['team' . $tind] = trim(preg_replace('/\s+/', ' ', $div->nodeValue));
                                }
                                break;

                            case 'team-score float-right':
                                $Sind = 1;
                                if (array_key_exists('team1-score', $data)) {
                                    $data['team' . ($Sind + 1) . '-score'] = trim(preg_replace('/\s+/', ' ', $div->nodeValue));
                                } else {
                                    $data['team' . $Sind . '-score'] = trim(preg_replace('/\s+/', ' ', $div->nodeValue));
                                }
                                break;

//                            case 'team-logo media-img':
//                                $Iind = 1;
//                                if (array_key_exists('team1-flag', $data)) {
//                                    $data['team' . ($Iind + 1) . '-flag'] = $div->getElementsByTagName('img')->item(0)->getAttribute('src');
//                                } else {
//                                    $data['team' . $Iind . '-flag'] = $div->getElementsByTagName('img')->item(0)->getAttribute('src');
//                                }
//                                break;

                            case 'float-left status-container':
                                $data['match-info'] = trim(preg_replace('/\s+/', ' ', $div->nodeValue));
                                break;

                            default:
                                break;
                        }
                    }
                    $results[] = array_merge($data2, $data);
                }
            }
        }

        $top5['matches'] = $results;

        return response()->json($top5);
    }

    public function newsList(Request $request) {

//        if (empty($request->input('device_token'))) {
//            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
//        }
        $url = 'http://www.espn.com/nba/';
        $html = $this->curl($url);

        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $news_xpath = new DOMXPath($doc);

            $news = $news_xpath->query("//article[contains(@class, 'contentItem')][section[contains(@class,'contentItem__content contentItem__content--story has-image')]]");

            $newsList = array();
            if ($news->length > 0) {
                foreach ($news as $n) {
                    $elements = $n->getElementsByTagName('section');
                    foreach ($elements as $childNode) {
                        try {
                            $data['newsLink'] = ($childNode->getElementsByTagName('a')->length > 0) ? $childNode->getElementsByTagName('a')->item(0)->getAttribute("href") : '';
                            $data['title'] = $childNode->getElementsByTagName('h1')->item(0)->nodeValue;
                            $data['desc'] = $childNode->getElementsByTagName('p')->item(0)->nodeValue;
                            $data['img'] = $childNode->getElementsByTagName('img')->item(0)->getAttribute("data-default-src");
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                    $newsList[] = $data;
                }
            }
        }

//                        dd($newsList);
        return response()->json(array('newslist' => $newsList));
    }

    public function newsDetail(Request $request) {


        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        if (empty($request->input('news_link'))) {
            return response()->json(array('status' => false, 'message' => 'News Link is empty'));
        }
        $url = 'http://www.espn.com' . $request->input('news_link');
//        $url = 'http://www.espn.com/nba/story/_/id/20852758/new-york-knicks-lose-drama-search-wins';

        $html = $this->curl($url);

        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $news_xpath = new DOMXPath($doc);


//            $news = $news_xpath->query("//article[contains(@data-src, '/nba/story/_/id/20852758/new-york-knicks-lose-drama-search-wins')]");
            $news = $news_xpath->query("//article[contains(@data-src, '" . $request->input('news_link') . "')]");

            if ($news->length > 0) {
                $data = array();
                foreach ($news as $n) {
                    $data['title'] = trim(preg_replace('/\s+/', ' ', $n->getElementsByTagName('header')->item(0)->nodeValue));

                    $asides = $n->getElementsByTagName('aside');
                    foreach ($asides as $aside) {
                        if (isset($data['image'])) {
                            continue;
                        }
                        $class = $aside->getAttribute('class');
                        if ($class == 'inline inline-photo full') {
                            $data['image'] = trim(explode(',', $aside->getElementsByTagName('source')->item(0)->getAttribute("data-srcset"))[0]);
                        }
                    }
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
//        $url = 'https://www.si.com/nba/scoreboard?date=2017-12-25';
        $url = 'https://www.si.com/nba/scoreboard?date=2017-12-23';

        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $results = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $domXpath = new DOMXPath($doc);


            $noGames = $domXpath->query("//p[contains(@class, 'no-games-scheduled')]");
            if ($noGames->length > 0) {
                return response()->json(array('noMatches' => 'No Matches For Today.'));
            }
            $matches = $domXpath->query("//div[contains(@class, 'component game')]");

            if ($matches->length > 0) {

                foreach ($matches as $match) {

                    $data = array();
                    $data2 = array(
                        'team1' => "",
                        'team2' => "",
                        'team1-score' => "",
                        'team2-score' => "",
//                        'team1-flag' => "",
//                        'team2-flag' => "",
                        'matchLink' => "",
                        'match-info' => ""
                    );
                    $divs = $match->getElementsByTagName('div');
                    foreach ($divs as $div) {
                        $className = $div->getAttribute('class');
                        switch ($className) {
                            case 'collapse-narrow float-right':
                                $data['matchLink'] = $div->getElementsByTagName('a')->item(0)->getAttribute('href');
                                break;

                            case 'team-city':
                                $tind = 1;
                                if (array_key_exists('team1', $data)) {
                                    $data['team' . ($tind + 1)] = trim(preg_replace('/\s+/', ' ', $div->nodeValue));
                                } else {
                                    $data['team' . $tind] = trim(preg_replace('/\s+/', ' ', $div->nodeValue));
                                }
                                break;

                            case 'team-score float-right':
                                $Sind = 1;
                                if (array_key_exists('team1-score', $data)) {
                                    $data['team' . ($Sind + 1) . '-score'] = trim(preg_replace('/\s+/', ' ', $div->nodeValue));
                                } else {
                                    $data['team' . $Sind . '-score'] = trim(preg_replace('/\s+/', ' ', $div->nodeValue));
                                }
                                break;

//                            case 'team-logo media-img':
//                                $Iind = 1;
//                                if (array_key_exists('team1-flag', $data)) {
//                                    $data['team' . ($Iind + 1) . '-flag'] = $div->getElementsByTagName('img')->item(0)->getAttribute('src');
//                                } else {
//                                    $data['team' . $Iind . '-flag'] = $div->getElementsByTagName('img')->item(0)->getAttribute('src');
//                                }
//                                break;

                            case 'float-left status-container':
                                $data['match-info'] = trim(preg_replace('/\s+/', ' ', $div->nodeValue));
                                break;



                            default:
                                break;
                        }
                    }
//                    $data2 = $data;
                    $results[] = array_merge($data2, $data);
                }
            }
        }
//        dd($series);

        return response()->json(array('matches' => $results));
    }

    public function squad(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        if (empty($request->input('match_url'))) {
            return response()->json(array('status' => false, 'message' => 'Match url empty'));
        }

        $url = $request->input('match_url');

        $html = $this->curl($url);

        $commentory_doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $commentory_doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $xpath = new DOMXPath($commentory_doc);

            $squads = $xpath->query("//div[contains(@class,'content-tab')]//tbody[position() mod 2 = 1]");

            $squadsTitles = $xpath->query("//span[contains(@class,'team-name-short')]");

            if ($squadsTitles->length > 0) {
                $t = array();

                $t[0] = $squadsTitles->item(0)->nodeValue;

                $t[1] = $squadsTitles->item(1)->nodeValue;
            }

            if ($squads->length > 0) {

                $team = array();

                foreach ($squads as $j => $squad) {

                    $team[$t[$j]] = array();

                    $trs = $squad->getElementsByTagName('tr');

                    foreach ($trs as $tr) {

                        $team[$t[$j]][] = $tr->getElementsByTagName('td')->item(0)->getElementsByTagName('a')->item(0)->nodeValue;
                    }
                }
            }
        }


        return response()->json(array('squad' => $team));
    }

    public function commentary(Request $request) {

//        if (empty($request->input('device_token'))) {
//            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
//        }
//        if (empty($request->input('match_url'))) {
//            return response()->json(array('status' => false, 'message' => 'Match url empty'));
//        }
//        $url = 'https://www.si.com/nba/game/1948290/play-by-play';
        $url = 'https://www.si.com' . $request->input('match_url') . '/play-by-play';

        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $result = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $domXpath = new DOMXPath($doc);

            $commentary = $domXpath->query("//table[contains(@class, 'schedules')]//tr[not(@class='table-heading')]");

            if ($commentary->length == 0) {
                return response()->json(array('noCommentary' => 'No Commentary.'));
            }

            $data = array();

            if ($commentary->length > 0) {

                $result = array();
                foreach ($commentary as $mint) {
                    $arr = array();
                    $arr['time'] = $mint->firstChild->nodeValue;
                    $arr['description'] = trim(preg_replace('/\s+/', ' ', $mint->getElementsByTagName('td')->item(2)->nodeValue));
                    $result[] = $arr;
                }
            }
        }

        return response()->json(array('commentary' => $result));
    }

    public function summary(Request $request) {
//
        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        if (empty($request->input('match_url'))) {
            return response()->json(array('status' => false, 'message' => 'Match url empty'));
        }

//        $url = 'https://www.si.com/nba/game/1948290/box-score';
        $url = 'https://www.si.com' . $request->input('match_url') . '/box-score';


        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $results = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $domXpath = new DOMXPath($doc);

            $domElements = $domXpath->query("//div[contains(@class, 'collapse-narrow clearfix')]//div[contains(@class,'box-score-container')]/div[contains(@class,'box-score-team')]");
            if ($domElements->length == 0) {
                return response()->json(array('noSummary' => 'No Summary.'));
            }

            $squads = $domXpath->query("//div[contains(@class, 'score-tile-large')]//div[contains(@class,'media vertically-center team')]");

            $tourHeadingPath = $domXpath->query("//div[contains(@class, 'tile-game-heading game-heading table-heading')]");
            $tourHeading = $tourHeadingPath->item(0)->nodeValue;
            $results['tourHeading'] = sTrim($tourHeading);

            if ($squads->length > 0) {
                foreach ($squads as $si => $squad) {
                    $team['flag'] = '';
                    $team['name'] = '';
                    $team['points'] = '';
                    $divs = $squad->getElementsByTagName('div');
                    $team['flag'] = $divs->item(0)->getElementsByTagName('img')->item(0)->getAttribute('src');
                    $team['name'] = trim(preg_replace('/\s+/', ' ', $divs->item(2)->getElementsByTagName('a')->item(0)->nodeValue));
                    $team['points'] = trim(preg_replace('/\s+/', ' ', $divs->item(4)->nodeValue));
                    $results['teamInfo']['team' . ($si + 1)] = $team;
                }
            }


            if ($domElements->length > 0) {

                foreach ($domElements as $ei => $element) {

                    $trs = $element->getElementsByTagName('tr');

                    foreach ($trs as $i => $tr) {
//                        dd(($tr->childNodes->length)/2-1);
                        $plyer = array(
                            'player' => '',
                            'points' => '',
                            'team' => ''
                        );
                        if ($i == 0 || $i == ($trs->length - 1))
                            continue;
                        $plyer['player'] = sTrim($tr->firstChild->nodeValue);
                        $plyer['points'] = sTrim($tr->getElementsByTagName('td')->item(($tr->childNodes->length) / 2 - 1)->nodeValue);
                        $plyer['team'] = sTrim($results['teamInfo']['team' . ($ei + 1)]['name']);
                        $results['summary'][] = $plyer;
                    }
                }
            }
        }

        return response()->json($results);
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

}
