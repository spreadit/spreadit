<?php
use Knp\Snappy\Image as ThumbDL;

class Utility
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
                    return substr(trim(html_entity_decode($title[1], ENT_QUOTES, 'UTF-8')), 0, 128);
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
                        return substr(trim(html_entity_decode($e->plaintext, ENT_QUOTES, 'UTF-8')), 0, 128);
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

    public static function getThumbnailFromUrl($url)
    {
        try {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $fcontents = file_get_contents($url);
            $ftype = $finfo->buffer($fcontents);
            $ftype_pieces = explode("/", $ftype);
            $is_text = ((count($ftype_pieces) == 2) && strcmp($ftype_pieces[0], "text") == 0);

            $image_types = ["image/gif", "image/jpeg", "image/jpg", "image/bmp", "image/png", "image/tiff", "image/svg"];
            $is_image = in_array(strtolower($ftype), $image_types);
        } catch (Exception $e) {
            return "";
        }

        try {
            $snappy = new ThumbDL('/usr/local/bin/wkhtmltoimage');
            $snappy->setOption('stop-slow-scripts', true);
            $snappy->setOption('width', "1024");
            $snappy->setOption('height', "576");

            $host = parse_url($url, PHP_URL_HOST);
            if(strcmp($host, "www.youtube.com") == 0) {
                parse_str(parse_url($url, PHP_URL_QUERY), $vars);
                $image = $snappy->getOutput(URL::to("/util/imagewrapper?url=".urlencode("http://img.youtube.com/vi/".$vars['v']."/0.jpg")));
            } else if(strcmp($host, "youtu.be") == 0) {
                $image = $snappy->getOutput(URL::to("/util/imagewrapper?url=".urlencode("http://img.youtube.com/vi".parse_url($url, PHP_URL_PATH)."/0.jpg")));
            } else if(strcmp($host, "vimeo.com") == 0) {
                $img_id = parse_url($url, PHP_URL_PATH);
                $img_hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video$img_id.php"));
                $image = $snappy->getOutput(URL::to("/util/imagewrapper?url=".$img_hash[0]['thumbnail_large']));
            } else {
                if($is_text) {
                    $image = $snappy->getOutput($url);
                } else if($is_image) { 
                    $image = $snappy->getOutput(URL::to("/util/imagewrapper?url=".urlencode($url)));
                } else {
                    return "";
                }
            }
        } catch (Exception $e) {
            return "";
        }

        try {
            $tmpfname = tempnam("/tmp", "spreadit");
            $handle = fopen($tmpfname, "w");
            fwrite($handle, $image);
            fclose($handle);
        } catch(Exception $e) {
            return "";
        }

        try {
            $generated_name = md5($url . time());
            $resizer = Image::make($tmpfname);
            $resizer->resize(128, 72);

            $resizer->save(public_path() . "/assets/thumbs/".$generated_name.".jpg");
        } catch(Exception $e) {
            return "";
        }

        return $generated_name;
    }

    public static function getSortTimeframe()
    {
        return Cookie::get(SortController::TIMEFRAME_COOKIE_NAME, SortController::TIMEFRAME_COOKIE_DEFAULT);
    }

    public static function getSortMode()
    {
        return Cookie::get(SortController::SORTBY_COOKIE_NAME, SortController::SORTBY_COOKIE_DEFAULT);
    }

    public static function gfycat($url)
    {
        $json = file_get_contents("http://upload.gfycat.com/transcode?fetchUrl=" . urlencode($url));
        $data = json_decode($json);
        if(isset($data->error)) {
            throw Exception("gfycat:" . $data->error);
        }

        return "http://gfycat.com/" . $data->gfyName;
    }

    //http://stackoverflow.com/a/834355/778858
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if($length == 0) {
            return true;
        }
        
        return (substr($haystack, -$length) === $needle);
    }
}
