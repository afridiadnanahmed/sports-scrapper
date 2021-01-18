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

class SportsController extends Controller {

    public function __construct() {

        DB::enableQueryLog();
    }

    public function defaultSports(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }

        $data = array(
            'Soccer', 'Cricket', 'Hockey', 'Tennis', 'Basketball', 'Volleyball', 'Baseball', 'Rugby', 'Boxing'
        );

        return response()->json(array('categories' => $data));
    }

    public function squad(Request $request) {

//        if (empty($request->input('device_token'))) {
//            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
//        }
//        if (empty($request->input('match_url'))) {
//            return response()->json(array('status' => false, 'message' => 'Match url empty'));
//        }

        $url = 'http://www.espncricinfo.com/series/18037/game/1120289/Pakistan-vs-Sri-Lanka-4th-ODI-pakistan-v-sri-lanka-odi-series/';
//        $url = str_replace('scorecard', 'game', $request->input('match_url'));

        $html = $this->curl($url);

        $commentory_doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $teamNames = array();
        if (!empty($html)) { //if any html is actually returned
            $commentory_doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $cricket_series_xpath = new DOMXPath($commentory_doc);


            $squads = $cricket_series_xpath->query("//article[contains(@class, 'boxscore-tabs sub-module-mobile-combine-split combined squad')]//tbody");
            $squadsTitles = $cricket_series_xpath->query("//span[contains(@class,'short-name')]");

            $teamNames['team1'] = $squadsTitles[0]->nodeValue;
            $teamNames['team2'] = $squadsTitles[1]->nodeValue;
            $teams = array();

            if ($squads->length > 0) {

                $squadIndex = 1;
                foreach ($squads as $j => $squad) {
                    $team = array();
                    if ($j > 1) {
                        break;
                    }

                    $trs = $squad->getElementsByTagName('tr');
                    foreach ($trs as $tind => $tr) {
                        if ($tind > 10)
                            break;
                        $team[]['name'] = $tr->childNodes->item(0)->nodeValue;
//                        $team[$t[$j]][$tr->childNodes->item(0)->nodeValue] = $tr->childNodes->item(1)->nodeValue;
                    }
                    $teamNames['team-' . $squadIndex . '-players'] = $team;
                    $squadIndex++;
                }
            }
        }


        return response()->json(array('teams' => $teamNames));
    }

    public function cricketInfo(Request $request) {


//        if (empty($request->input('device_token'))) {
//            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
//        }
//        $siteUrl = 'http://www.espncricinfo.com';
        $url = 'http://www.espncricinfo.com/ci/engine/match/index.html?view=week';

        $html = $this->curl($url);

        $cricket_doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $cricket_doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $cricket_series_xpath = new DOMXPath($cricket_doc);


            $series_type1 = $cricket_series_xpath->query("//div[contains(@class, 'match-section-head')]//h2");

            if ($series_type1->length > 0) {
                $i = 1;

//                $data = array();
                $series = array();
                foreach ($series_type1 as $row) {
                    $trophy = array();
                    $trophy['category'] = trim($row->nodeValue);

                    $matchesBlocks = $cricket_series_xpath->query("//section[contains(@class, 'matches-day-block')][" . $i . "]//section[contains(@class, 'default-match-block')]");

                    for ($n = 1; $n <= $matchesBlocks->length; $n++) {
                        $data = array();
                        $matchSections = $cricket_series_xpath->query("//section[contains(@class, 'matches-day-block')][" . $i . "]//section[contains(@class, 'default-match-block')][" . $n . "]//div");

                        foreach ($matchSections as $k => $match) {

                            if ($match->getAttribute('class') == "match-articles") {
                                continue;
                            }

                            if ($match->getAttribute('class') == 'match-info') {
                                $anchor = $match->getElementsByTagName('a')->item(0);
                                $data['matchLink'] = trim($anchor->getAttribute("href"));
                            }
                            $data[$match->getAttribute('class')] = trim(preg_replace('/\s+/', ' ', $match->nodeValue));
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

    public function top5(Request $request) {


//        if (empty($request->input('device_token'))) {
//            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
//        }
//     NEWS

        $urlNews = 'http://www.espncricinfo.com/ci/content/story/news.html';
        $html = $this->curl($urlNews);
        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors

        $result = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $news_xpath = new DOMXPath($doc);


            $news = $news_xpath->query("//article[contains(@class, 'story-item')]");

            if ($news->length > 0) {
                $data = array();
                foreach ($news as $ind => $n) {
                    if ($ind == 5)
                        break;
                    $data = array();
                    $data['type'] = 'news';
                    $elements = $n->getElementsByTagName('*');
                    foreach ($elements as $childNode) {
                        $class = $childNode->getAttribute('class');
                        switch ($class) {
                            case 'match-title':
                                $data['class'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'author no-thumb':
                                $data['author'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'author ':
                                $data['author'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'story-imgwrap':
                                $img = $n->getElementsByTagName('img')->item(0);
                                $imgSrc = trim($img->getAttribute("src"));
                                $data['image'] = $imgSrc;
                                break;
                            case 'story-title':
                                $data['title'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                $anchor = $childNode->getElementsByTagName('a')->item(0);
                                $data['newsLink'] = trim($anchor->getAttribute("href"));
                                break;
                            default:
                                break;
                        }
                    }
                    $newsList[] = $data;
                }
                $result['news'] = $newsList;
            }
        }
//        END NEWS
//       dd($newsList);
//       TOP EVENTS
        $url = 'http://www.espncricinfo.com/ci/engine/match/index.html?view=live';

        $html = $this->curl($url);

        $cricket_doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $cricket_doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $cricket_series_xpath = new DOMXPath($cricket_doc);


            $series_type1 = $cricket_series_xpath->query("//div[contains(@class, 'match-section-head')]//h2");

            if ($series_type1->length > 0) {
                $i = 1;

//                $data = array();
                $series = array();
                foreach ($series_type1 as $row) {
                    if ($i > 5)
                        break;
                    $trophy = array();
                    $trophy['category'] = trim($row->nodeValue);

                    $matchesBlocks = $cricket_series_xpath->query("//section[contains(@class, 'matches-day-block')][" . $i . "]//section[contains(@class, 'default-match-block')]");

                    for ($n = 1; $n <= $matchesBlocks->length; $n++) {
                        if ($n > 2)
                            break;
                        $data = array();
                        $matchSections = $cricket_series_xpath->query("//section[contains(@class, 'matches-day-block')][" . $i . "]//section[contains(@class, 'default-match-block')][" . $n . "]//div");

                        foreach ($matchSections as $k => $match) {
                            $data['type'] = 'live';
                            if ($match->getAttribute('class') == "match-articles") {
                                continue;
                            }

                            if ($match->getAttribute('class') == 'match-info') {
                                $anchor = $match->getElementsByTagName('a')->item(0);
                                $data['matchLink'] = trim($anchor->getAttribute("href"));
                            }
                            $data[$match->getAttribute('class')] = trim(preg_replace('/\s+/', ' ', $match->nodeValue));
                        }
                        $trophy['match'] = $data;
                    }
                    $i++;
                    $series[] = $trophy;
                }
                $result['matches'] = $series;
            }
        }

//CATEGORIES
        $url = 'http://www.espncricinfo.com/ci/engine/series/index.html?view=month';

        $html = $this->curl($url);

        $cricket_doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $cricket_doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $cricket_series_xpath = new DOMXPath($cricket_doc);


            $series_type1 = $cricket_series_xpath->query("//div[contains(@class, 'match-section-head')]//h2");

            $categoryTypes = array('Tests', 'One-Day Internationals', 'Twenty20 Internationals', 'Women\'s One-Day Internationals', 'tour');

            if ($series_type1->length > 0) {
                $i = 1;

                $categories = array();
                foreach ($series_type1 as $row) {
//                    echo $row->nodeValue;exit;
                    if (in_array($row->nodeValue, $categoryTypes)) {
                        $matchesBlocks = $cricket_series_xpath->query("//section[contains(@class, 'series-summary-wrap')][" . $i . "]");

                        foreach ($matchesBlocks->item(0)->childNodes as $ind => $section) {
                            if ($ind == 1)
                                break;
                            $data = array();
                            $data['type'] = 'series';
                            $data['link'] = $section->getElementsByTagName('a')->item(0)->getAttribute('href');
                            $data['title'] = $section->getElementsByTagName('a')->item(0)->nodeValue;
                            $date = trim(preg_replace('/\s+,/', ' ', $section->getElementsByTagName('div')->item(2)->nodeValue));
                            $data['title'] .= '-' . $date;
                            $categories[] = $data;
                        }
                        $i++;
                    }else {
                        $i++;
                        continue;
                    }
                }
                $result['series'] = $categories;
            }
        }

//END CATEGORIES


        return response()->json(array('top5' => $result));
    }

    public function series(Request $request) {
        //        if (empty($request->input('device_token'))) {
//            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
//        }
//        if (empty($request->input('match_url'))) {
//            return response()->json(array('status' => false, 'message' => 'Match url empty'));
//        }


        $url = 'http://www.espncricinfo.com/ci/engine/series/index.html?view=season';

        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $results = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $xpath = new DOMXPath($doc);



            $elements = $xpath->query("//section[contains(@class, 'series-summary-block collapsed')]");

            if ($elements->length > 0) {
                foreach ($elements as $element) {

                    $result = array();
                    $result['matchLink'] = $element->getElementsByTagName('a')->item(0)->getAttribute('href');
                    $result['title'] = trim(preg_replace('/\s+/', ' ', $element->getElementsByTagName('a')->item(0)->nodeValue));
                    $results[] = $result;
                }
            }
        }
//        dd($results);
        return response()->json(array('series' => $results));
    }

    public function seriesDetail(Request $request) {
        //        if (empty($request->input('device_token'))) {
//            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
////        }
//        if (empty($request->input('match_url'))) {
//            return response()->json(array('status' => false, 'message' => 'Match url empty'));
//        }


        $url = 'http://www.espncricinfo.com/ci/engine/series/1075481.html';
//        $url = str_replace('game', 'scorecard', $request->input('match_url'));

        $html = $this->curl($url);
//        echo $html;
//        exit;
        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $results = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $xpath = new DOMXPath($doc);



            $elements = $xpath->query("//div[contains(@class, 'news-list large-20 medium-20 small-20')]/ul[not(parent::li)]/li");

            if ($elements->length > 0) {
                foreach ($elements as $element) {

                    if ($element->firstChild->tagName == 'hr') {
                        continue;
                    }
                    $result = array();
                    $result['matchLink'] = $element->getElementsByTagName('a')->item(0)->getAttribute('href');
                    $result['match-info'] = explode('-', trim(preg_replace('/\s+/', ' ', $element->getElementsByTagName('h2')->item(0)->nodeValue)))[1];
                    $result['match-info'] .= '-';
                    $result['match-info'] .= explode('-', trim(preg_replace('/\s+/', ' ', $element->getElementsByTagName('h2')->item(0)->nodeValue)))[2];
                    $result['innings-info'] = explode('-', trim(preg_replace('/\s+/', ' ', $element->getElementsByTagName('h2')->item(0)->nodeValue)))[0];
                    $result['match-status'] = $element->getElementsByTagName('span')->item($element->getElementsByTagName('span')->length - 1)->nodeValue;
                    $result['match-result'] = trim(preg_replace('/\s+/', ' ', $element->getElementsByTagName('b')->item(0)->nodeValue));
                    $results[] = $result;
                }
            }
        }
//        dd($results);
        return response()->json(array('matches' => $results));
    }

    public function scoreBoard(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        if (empty($request->input('match_url'))) {
            return response()->json(array('status' => false, 'message' => 'Match url empty'));
        }

//        $url = 'http://www.espncricinfo.com/series/10883/scorecard/1072307/australia-vs-england-3rd-test-eng-tour-of-aus-and-nz-2017-18/';
        $url = $request->input('match_url');

        $html = $this->curl($url);
//        echo $html;
//        exit;
        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $results = array();
        $teams = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $xpath = new DOMXPath($doc);



            $matchSummary = $xpath->query("//article[contains(@class, 'sub-module scorecard')]");

            $teamNames = $xpath->query("//div[contains(@class,'col-b')]//ul[contains(@class, 'cscore_competitors ')]/li//span[contains(@class,'cscore_name cscore_name--long')]");
            $teams['team1'] = $teamNames[0]->nodeValue;
            $teams['team2'] = $teamNames[1]->nodeValue;

            if ($matchSummary->length > 0) {


                foreach ($matchSummary as $team) {
                    $elements = $team->getElementsByTagName('*');
//                  $elementss = $team->getElementsByClassName('wrap batsmen');
//                  dd($elementss);
                    $result = array();
                    $cInd = 0;
                    $bInd = 0;
                    foreach ($elements as $childNode) {
                        $class = $childNode->getAttribute('class');
                        switch ($class) {
                            case 'accordion-header':
                                $heading = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                $result['title'] = $heading;
                                break;
                            case 'wrap batsmen':
                                $arrIndex = array('R', 'M', 'B', '4s', '6s', 'SR');
                                $i = 0;

                                foreach ($childNode->childNodes as $child) {
                                    if ($i == sizeof($arrIndex)) {
                                        break;
                                    }
                                    $childClass = $child->getAttribute('class');
//                                dd($childClass);
                                    switch ($childClass) {
                                        case 'cell batsmen':
                                            $batsman = trim(preg_replace('/\s+/', ' ', $child->nodeValue));
                                            $result['batsmen'][$cInd] = array();
                                            $result['batsmen'][$cInd]['name'] = $batsman;
                                            break;
                                        case 'cell commentary':
                                            $result['batsmen'][$cInd]['result'] = trim(preg_replace('/\s+/', ' ', $child->nodeValue));
                                            break;
                                        case 'cell runs':
                                            $result['batsmen'][$cInd][$arrIndex[$i]] = trim(preg_replace('/\s+/', ' ', $child->nodeValue));
                                            $i++;
                                            break;


                                        default:
                                            break;
                                    }
                                }
                                $cInd++;
                                break;
                            case 'scorecard-section bowling':
                                $result['bowling'][$bInd] = array();
                                $arrIndex = array('O', 'M', 'R', 'W', 'ECON', 'WD', 'NB');

                                $childtags = $childNode->getElementsByTagName('tbody')->item(0);
                                $trLength = $childtags->childNodes->length;
                                foreach ($childtags->childNodes as $trI => $tr) {
                                    $i = 0;

//                                    if ($trI == (int) $trLength - 1) {
//                                        $result['bowling'][$bInd]['fow'] = $tr->childNodes->item(0)->nodeValue;
//                                        continue;
//                                    }
                                    $result['bowling'][$bInd]['name'] = $tr->childNodes->item(0)->nodeValue;
                                    foreach ($tr->childNodes as $j => $td) {
                                        if ($i == sizeof($arrIndex)) {
                                            break;
                                        }
                                        if ($j == 0 || $j == 1 || $j == 12 || $td->nodeValue == '') {
                                            continue;
                                        }
                                        $result['bowling'][$bInd][$arrIndex[$i]] = trim(preg_replace('/\s+/', ' ', $td->nodeValue));
                                        $i++;
                                    }
                                    $bInd++;
                                }
                                break;
                            default:
                                break;
                        }
                    }
                    $results[] = $result;
                }
            }
        }

//        dd($results);
        return response()->json(array('teams' => $teams, 'summary' => $results));
    }

    public function summary(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        if (empty($request->input('match_url'))) {
            return response()->json(array('status' => false, 'message' => 'Match url empty'));
        }

        $url = 'http://www.espncricinfo.com/series/10883/game/1072307/australia-vs-england-3rd-test-eng-tour-of-aus-and-nz-2017-18';
//        $url = str_replace('scorecard', 'game', $request->input('match_url'));

        $html = $this->curl($url);

        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $results = array();
        $teams = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $xpath = new DOMXPath($doc);

            $result = array();
            $teamContents = $xpath->query("//div[contains(@class,'col-b')]//ul[contains(@class, 'cscore_competitors ')]/li");

            $headingPath = $xpath->query("//div[contains(@class,'cscore_overview')]//div[contains(@class,'cscore_info-overview')]");
            $tourHeading = $headingPath->item(0)->nodeValue;

            if ($teamContents->length > 0) {
                $ind = 1;
                foreach ($teamContents as $team) {
//                    dd($team->nodeValue);
                    $elements = $team->getElementsByTagName('div');
                    $picture = $team->getElementsByTagName('picture');
                    $result['team' . $ind . '-flag'] = strstr($picture->item(0)->getElementsByTagName('img')->item(0)->getAttribute('data-src'), '&', TRUE);

                    foreach ($elements as $childNode) {


                        $class = $childNode->getAttribute('class');

                        switch ($class) {
//                            case 'team__banner__wrapper':
//                                $picture = $childNode->getElementsByTagName('picture')->item(0);
////                                dd($picture->getElementsByTagName('img')->item(0)->getAttribute('data-src'));
//                                $img = $picture->getElementsByTagName('img')->item(0)->getAttribute('data-src');
//                                $result['team' . $ind . '-flag'] = $img;
//                                break;
                            case 'cscore_truncate':
                                $teanName = trim(preg_replace('/\s+/', ' ', $childNode->getElementsByTagName('span')->item(1)->nodeValue));
                                $result['team' . $ind] = $teanName;
                                break;
                            case 'cscore_score ':
                                $score = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                $result['team' . $ind . '-score'] = $score;

                                break;

                            default:
                                break;
                        }
                    }
                    $ind++;
                }
            }

            $teams = $result;

            $matchSummary = $xpath->query("//article[contains(@class, 'sub-module scorecard-summary')]//div[contains(@class,'content')][1]/div[contains(@class,'inning')]");
//            dd($matchSummary);
//            echo $matchSummary->length;exit;
            if ($matchSummary->length > 0) {

                $team = $matchSummary[$matchSummary->length - 1];
                $result = array();
//                foreach ($matchSummary as $team) {
                $result['title'] = $team->getElementsByTagName('h4')[0]->nodeValue;

                $uls = $team->getElementsByTagName('ul');

//                    BATSMEN
                $lis = $uls[0]->getElementsByTagName('li');
                $result['batsmen'] = array();
                $batsman = array();
                foreach ($lis as $li) {
                    $batsman['name'] = trim(preg_replace('/\s+/', ' ', $li->getElementsByTagName('a')->item(0)->nodeValue));
                    $batsman['score'] = trim(preg_replace('/\s+/', ' ', $li->childNodes[1]->nodeValue));
                    $result['batsmen'][] = $batsman;
                }

//                    BOWLERS
                $lis = $uls[1]->getElementsByTagName('li');
                $result['bowling'] = array();
                $bowler = array();
                foreach ($lis as $li) {
                    $bowler['name'] = trim(preg_replace('/\s+/', ' ', $li->getElementsByTagName('a')->item(0)->nodeValue));
                    $bowler['score'] = trim(preg_replace('/\s+/', ' ', $li->childNodes[1]->nodeValue));
                    $result['bowling'][] = $bowler;
                }

                $results[] = $result;
//                }
            }
        }
//
//                print_r($teams);

        $results['tourHeading'] = $tourHeading;

        return response()->json(array('teams' => $teams, 'summary' => $results));
    }

    public function commentary(Request $request) {

        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        if (empty($request->input('match_url'))) {
            return response()->json(array('status' => false, 'message' => 'Match url empty'));
        }

//        $url = 'http://www.espncricinfo.com/series/11143/game/1104284/Bangladesh-vs-Australia-1st-Test-Australia-in-Bangladesh-Test-Series';
        $url = str_replace('scorecard', 'game', $request->input('match_url'));

        $html = $this->curl($url);

        $commentory_doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        $data = array();
        if (!empty($html)) { //if any html is actually returned
            $commentory_doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $cricket_series_xpath = new DOMXPath($commentory_doc);


            $balls = $cricket_series_xpath->query("//div[contains(@class, 'commentary-item') and not(@class='commentary-item end-of-over')]");

            $data = array();

            if ($balls->length > 0) {

                foreach ($balls as $ball) {

                    $elements = $ball->getElementsByTagName('div');

                    $result = array();
                    foreach ($elements as $childNode) {
                        $class = $childNode->getAttribute('class');
                        switch ($class) {
                            case 'time-stamp':
                                $result['ball'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'over-circle low-score':
                                $result['result'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'over-circle wicket':
                                $result['result'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'description':
                                $result['description'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));

                                break;

                            default:
                                break;
                        }
                    }
                    if (!empty($result))
                        $data[] = $result;
                }
            }
        }


//        dd($data);
        return response()->json(array('commentary' => $data));
    }

    public function newsList(Request $request) {


        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        $webRequest = TRUE;
//        if (!empty($request->input('web_request'))) {
//            $webRequest = TRUE;
//        }
        $url = 'http://www.espncricinfo.com/ci/content/story/news.html';
        $html = $this->curl($url);

//        $html = File::get(storage_path() . '/cricketresult/news.html', 'r');
        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $news_xpath = new DOMXPath($doc);


            $news = $news_xpath->query("//article[contains(@class, 'story-item')]");

            if ($news->length > 0) {
                $data = array();
                foreach ($news as $n) {
                    $data = array();
                    $elements = $n->getElementsByTagName('*');
                    foreach ($elements as $childNode) {
                        $class = $childNode->getAttribute('class');
                        switch ($class) {
                            case 'match-title':
                                $data['class'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'author no-thumb':
                                $data['author'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'author ':
                                $data['author'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'story-imgwrap':
                                $img = $n->getElementsByTagName('img')->item(0);
                                $imgSrc = trim($img->getAttribute("src"));
                                $data['image'] = $imgSrc;
                                break;
                            case 'story-title':
                                $data['title'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                $anchor = $childNode->getElementsByTagName('a')->item(0);
                                $data['newsLink'] = trim($anchor->getAttribute("href"));
                                break;
                            case 'story-brief':
                                if ($webRequest) {
                                    $data['desc'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                    break;
                                } else {
                                    break;
                                }
                            default:
                                break;
                        }
                    }
                    $newsList[] = $data;
                }
            }
        }

        return response()->json(array('newslist' => $newsList));
    }

    public function newsList2(Request $request) {

//
        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        $webRequest = TRUE;
//        if (!empty($request->input('web_request'))) {
//            $webRequest = TRUE;
//        }
        $url = 'http://www.espncricinfo.com';
        $html = $this->curl($url);

        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors
        $newsList = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $news_xpath = new DOMXPath($doc);


            $sections = $news_xpath->query("//section[contains(@class, 'contentCollection')]");


            if ($sections->length > 0) {
                foreach ($sections as $si => $section) {
                    $news = $section->getElementsByTagName('article');
//                $news = $news_xpath->query("//article[contains(@class, 'contentItem')]");
                    if ($section->getElementsByTagName('header')->length == 0) {
                        continue;
                    }

                    foreach ($news as $n) {
                        $data = array();
                        $elements = $n->getElementsByTagName('*');
                        foreach ($elements as $childNode) {
//                        $data['class'] = trim(preg_replace('/\s+/', ' ', $section->getElementsByTagName('header')->item(0)->nodeValue));
                            $class = $childNode->getAttribute('class');
                            switch ($class) {
                                case 'media-wrapper_image':
                                    $img = $childNode->getElementsByTagName('img')->item(0);
                                    if (is_null($img))
                                        continue;
                                    $imgSrc = trim($img->getAttribute("data-default-src"));
                                    $data['image'] = $imgSrc;
                                    $data['author'] = 'Author';
                                    break;
                                case 'contentItem__title contentItem__title--story':
                                    $data['title'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                    break;
                                case 'contentItem__header':
                                    $data['class'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                    break;
                                case 'contentItem__padding':
                                    $data['newsLink'] = trim($childNode->getAttribute("href"));
                                    break;
                                case 'contentItem__subhead contentItem__subhead--story':
                                    if ($webRequest) {
                                        $data['desc'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                        break;
                                    } else {
                                        break;
                                    }
                                default:
                                    break;
                            }
                        }
                        if (empty($data) || count($data) < 5 || !array_key_exists('desc', $data) || strstr($data['newsLink'], 'video'))
                            continue;
                        else
                            $newsList[] = $data;
                    }
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
        $url = 'http://www.espncricinfo.com' . $request->input('news_link');
//echo $url;exit;
//        $url = 'http://www.espncricinfo.com/india/content/story/1130280.html';

        $html = $this->curl($url);
        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors
        $data = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $news_xpath = new DOMXPath($doc);

            $news = $news_xpath->query("//section[contains(@id, 'article-feed')]//article[contains(@class, 'article')][1]");
//            $news = $news_xpath->query("//div[contains(@class, 'main-story-container col-1-1')]");

            if ($news->length > 0) {
                foreach ($news as $n) {
                    $data = array();
                    $elements = $n->getElementsByTagName('*');
                    $data['details'] = '';
                    foreach ($elements as $childNode) {
                        if ($childNode->tagName == 'p') {
                            $data['details'] .= '<p>' . trim(preg_replace('/\s+/', ' ', $childNode->nodeValue)) . '</p>';
                            continue;
                        }
                        $class = $childNode->getAttribute('class');
                        switch ($class) {
                            case 'author has-bio':
                                $data['author'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'author':
                                $data['author'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'timestamp':
                                $data['date'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'article-header':
                                $heading = $childNode->getElementsByTagName('h1')->item(0);
                                $data['title'] = trim(preg_replace('/\s+/', ' ', $heading->nodeValue));
                                break;
                            case 'img-wrap':
                                $img = $n->getElementsByTagName('img')->item(0);
                                $imgSrc = trim($img->getAttribute("src"));
                                if ($imgSrc == '') {
                                    $img = $n->getElementsByTagName('source')->item(0);
                                    $imgSrc = trim($img->getAttribute("srcset"));
                                    $data['image'] = $imgSrc;
                                } else {
                                    $data['image'] = $imgSrc;
                                }
                                break;
                            case 'iframe-video article-figure video  active':
                                $img = $n->getElementsByTagName('source')->item(0)->getAttribute("src");
                                $imgSrc = trim($img);
                                $data['image2'] = $imgSrc;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }

        return response()->json(array('newsDetail' => $data));
    }

    public function newsDetail2(Request $request) {


        if (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }
        if (empty($request->input('news_link'))) {
            return response()->json(array('status' => false, 'message' => 'News Link is empty'));
        }
//        $url = 'http://www.espncricinfo.com' . $request->input('news_link');

        $url = 'http://www.espncricinfo.com/india/content/story/1125005.html';

        $html = $this->curl($url);

        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors
        $data = array();
        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $news_xpath = new DOMXPath($doc);


            $news = $news_xpath->query("//section[contains(@id, 'article-feed')");
            dd($news->length);
            if ($news->length > 0) {
                foreach ($news as $n) {
                    $data = array();
                    $elements = $n->getElementsByTagName('*');
                    $data['details'] = '';
                    foreach ($elements as $childNode) {
                        if ($childNode->tagName == 'p') {
                            $data['details'] .= trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                            continue;
                        }
                        $class = $childNode->getAttribute('class');
                        switch ($class) {
                            case 'date col-3-12':
                                $data['date'] = trim(preg_replace('/\s+/', ' ', $childNode->nodeValue));
                                break;
                            case 'story-headline col-10-12':
                                $heading = $childNode->getElementsByTagName('h1')->item(0);
                                $data['title'] = trim(preg_replace('/\s+/', ' ', $heading->nodeValue));
                                break;
                            case 'video-section col-1-1 first-image':
                                $img = $n->getElementsByTagName('img')->item(0);
                                $imgSrc = trim($img->getAttribute("src"));
                                $data['image'] = $imgSrc;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }


        return response()->json(array('newsDetail' => $data));
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
