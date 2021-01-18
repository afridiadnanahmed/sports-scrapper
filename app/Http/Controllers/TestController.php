<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DOMDocument;
use DOMXPath;
use File;

class TestController extends Controller
{
    public function index()
    {
        $url = 'https://www.summersphc.com/blog/';
        $html = $this->curl($url);

        $doc = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors

        if (!empty($html)) { //if any html is actually returned
            $doc->loadHTML($html);

            libxml_clear_errors(); //remove errors for yucky html

            $blogs_xpath = new DOMXPath($doc);

            $months = $blogs_xpath->query("//li[@class='level-2']");

            $blogsList = array();
            if ($months->length > 0) {

                foreach ($months as $ml) {
                    $monthUrl = $ml->getElementsByTagName('a')->item(0)->getAttribute("href");
                    $blogsCount = (int)trim($ml->getElementsByTagName('em')->item(0)->nodeValue, '()');
                    $monthsLinks[$monthUrl] = $blogsCount;
                }

                foreach ($monthsLinks as $monthUrl => $blogCount) {
                     // dd($monthUrl);
                     $url = 'https://www.summersphc.com'. $monthUrl;
                    if( $blogCount > 3 ){
                        $callCount = (int)ceil($blogCount/3);
                        for ($i=1; $i <= $callCount ; $i++) {
                            $html = $this->curl($url, $i);
                            if (!empty($html)) { //if any html is actually returned
                                $doc->loadHTML($html);
                                libxml_clear_errors(); //remove errors for yucky html
                                $blogs_xpath = new DOMXPath($doc);

                                $blogs = $blogs_xpath->query("//ul[@class='post-list']//a[@class='more-btn']");
                                if ($blogs->length > 0) {
                                    foreach ($blogs as $blog) {
                                        $blogsList[] = $blog->getAttribute('href');
                                    }
                                }
                            }
                            // break 2;
                        }
                        // dd($blogsList);
                    }else{
                        // continue;
                        $html = $this->curl($url);

                        if (!empty($html)) { //if any html is actually returned
                            $doc->loadHTML($html);
                            libxml_clear_errors(); //remove errors for yucky html
                            $blogs_xpath = new DOMXPath($doc);

                            $blogs = $blogs_xpath->query("//ul[@class='post-list']//a[@class='more-btn']");
                            if ($blogs->length > 0) {
                                foreach ($blogs as $blog) {
                                    $blogsList[] = $blog->getAttribute('href');
                                }
                            }
                        }
                    }

                }

            }
            // dd($blogsList);
            $this->writeCsv( $blogsList );
        }

        return response()->json(array('message' => $blogsList));
    }

    public function importBlog()
    {
        $csvFile = file(storage_path("blogsList.csv"));
        // dd($csvFile);
        foreach ($csvFile as $k => $line) {
            $blogDetails = [];
            $url = trim('https://www.summersphc.com'. $line);
            // $url = 'https://www.summersphc.com/blog/2016/april/connect-with-summers-phc-online-/';
            $html = new \Htmldom($url);
            $blogDetails[] = $html->find('head link[rel=canonical]')[0]->href;
            $blogDetails[] = $html->find('head meta[name=description]')[0]->content;
            $blogDetails[] = $html->find('head meta[property=og:title]')[0]->content ?? '';
            $blogDetails[] = $html->find('head meta[property=og:description]')[0]->content ?? '';
            $blogDetails[] = $html->find('head meta[property=og:image]')[0]->content ?? '';
            $blogDetails[] = $html->find('head meta[name=twitter:card]')[0]->content ?? '';
            $blogDetails[] = $html->find('head meta[name=twitter:title]')[0]->content ?? '';
            $blogDetails[] = $html->find('head meta[name=twitter:description]')[0]->content ?? '';
            $blogDetails[] = $html->find('head meta[name=twitter:image]')[0]->content ?? '';
            $blogDetails[] = $html->find('article[id=MainZone] h1[itemprop=headline]')[0]->plaintext;
            $blogDetails[] = $html->find('article[id=MainZone] img')[0]->src;
            $blogDetails[] = $html->find('article[id=MainZone] time')[0]->plaintext;
            $blogDetails[] = trim(str_replace(',', '|||||', $html->find('div[class=content-box] ')[0]->innertext));
            // dd($blogDetails);

            $result = $this->writeCsv( $blogDetails );
        }
        echo 'done';
        exit;

    }

    public function writeCsv( $data )
    {

          $file = fopen(storage_path("blogs.csv"),"a+");

          fputcsv($file, $data);

          fclose($file);
          return;
    }

    public function curl($url, $i = 0 ) {
        $ch = curl_init();
        $timeout = 0; //UNLIMITED
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        if( $i > 0 ){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('_m_' => 'BlogSystemMonth','BlogSystemMonth$FTR0$PagingID' => $i));
        }

        // Get URL content
        $html = curl_exec($ch);

        curl_close($ch);

        return $html;
    }
}
