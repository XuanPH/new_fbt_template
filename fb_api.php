<?php 
header('Access-Control-Allow-Origin: *');  
// header('Access-Control-Allow-Origin: http://mysite1.com', false);
// header('Access-Control-Allow-Origin: http://example.com', false);
// header('Access-Control-Allow-Origin: https://www.mysite2.com', false);
// header('Access-Control-Allow-Origin: http://www.mysite2.com', false);
class xmlreturn {
    public $url;
    public $author;
}

function _viewSource($url){ 
    $parse_url = parse_url($url); 
    $headers = array("Host: mp3.zing.vn",
                    "Cookie:tuser=0; _znu=1; fuid=50996e0e5b1fe277076f8cefed4da311; SRVID=s65174_8132; _zploc=A1984242600; __mp3sessid=5D5E6614B2A9; adtimaUserId=2000.22f17a8d8d8765d93c96.1499077512785.b7271d3b; BANNER_OFF=; _zmp3=0.4289175017668234; __sessid=1834.2575336417.3484762826.1500518392; ___sessid=5543.2575340126.3484766535.1500518392.2467534993; __zi=2000.22f17a8d8d8765d93c96.1499077512785.b7271d3b; _ga=GA1.2.183300528.1498535280; _gid=GA1.2.1748553486.1500446424; atmpv=1"
    ); 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL,$url); 
    curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Linux; U; Android 4.0.3; ko-kr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30"); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  
    curl_setopt($ch, CURLOPT_REFERER, $url); 
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); 
    curl_setopt($ch, CURLOPT_HEADER, false); 
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($ch); 
    curl_close($ch); 
    return $result; 
}
$token = 'EAACW5Fg5N2IBABarRKZA8voM7k7qh95G0hH0smIZCFfgspYffQy2v7rPvs7ItS5qDeJDpdvItmoglxRb4aOcAx7LsM7sMbyX7KbVnWJoRzW9pJhFsLkf5vtVUt3aI7Sloe9TsD1ePulifUuDWAyMPaBNY9ZC8s6vB2YFGct6V7ZCiUEG8G6G';
$post_id = '100003455062019_862604483864712';
$loginUrl = 'https://graph.fb.me/v2.10/'.$post_id.'?fields=comments.limit(600000000){comments.limit(600000000){message,created_time,from},message,from}&access_token='.$token;
$result = json_decode(file_get_contents($loginUrl));
$result = $result->comments->data;
foreach($result as $item){
    echo $item->message;
}
//var_dump ($result);

//$content = file_get_contents('http://mp3.zing.vn/bai-hat/Doi-Mat-Wanbi-Tuan-Anh/ZWZAOZEW.html');
//$decoded_content = gzdecode($content);
//echo ($decoded_content);
?>