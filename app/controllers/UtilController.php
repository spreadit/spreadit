<?php

class UtilController extends BaseController
{
    public static function prettySubstr($data, $max_length=70)
    {
        $data = trim($data);

        if(strlen($data) > $max_length) {
            $data = wordwrap($data, $max_length);
            $data = explode("\n", $data);
            $data = array_shift($data);
        }
        if(strlen($data) > $max_length) {
            $data = substr($data, 0, $max_length);
        }

        return $data;
    }

    public static function prettyUrl($url, $max_length=70)
    {
        $url = self::prettySubstr($url, $max_length);
        
        //from: http://stackoverflow.com/a/7568253
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
        $url = trim($url, "-");
        $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);

        return $url;
    }

    public static function prettyAgo($tm, $rcs = 0)
    {
        $cur_tm = time(); $dif = $cur_tm-$tm;
        $pds = array('second','minute','hour','day','week','month','year','decade');
        $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
        for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

        $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
        if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
        return $x;
    }

    public static function titleFromUrl($url)
    {
        if(filter_var($url, FILTER_VALIDATE_URL)) {
            try {
                $str = file_get_contents($url, NULL, NULL, 0, 16384);
            } catch(Exception $e) {
                return "url not found";
            }

            if(strlen($str) > 0) {
                preg_match("/\<title\>(.*)\<\/title\>/", $str, $title);           
                
                if(count($title) > 1) {
                    return substr(trim($title[1]), 0, 128);
                } else {
                    try {
                        $html = Sunra\PhpSimple\HtmlDomParser::str_get_html($str);
                    } catch(ErrorException $e) {
                        return "title parsing error";
                    }

                    if(!$html) {
                        return "sorry, we couldn't get a title";
                    } 

                    foreach($html->find("title") as $e) {
                        return substr(trim($e->plaintext), 0, 128);
                    }

                    return "no title found";
                }
            }
        } else {
            return "not url";
        }
    }

    public static function urlExists($url)
    {
        try {
            file_get_contents($url);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }
}
