<?php 
header('Access-Control-Allow-Origin: *');  
ini_set('max_execution_time', 300);
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
function _getUserFromUID($uid,$token)
{
    if ($uid == '' || $uid == null)
    {
        return '';
    }
    $url = "https://graph.facebook.com/fql?q=select+id,name,url,pic,type+from+profile+where+id+=+".$uid."&access_token=".$token; //675639245913325
    $result = json_decode(file_get_contents($url));
    $profile_user = reset($result);
    return array_values($profile_user)[0];
}
function _convertReply($replays,$token)
{
    if ($replays != null)
    {
        $list_cmt_reply = [];
        foreach($replays as $cmt_reply)
        {
            $item = array(
                "message" => $cmt_reply->message,
                "created_time" => $cmt_reply->created_time,
                "id" => $cmt_reply->id,
                "from" => _getUserFromUID($cmt_reply->from->id,$token)
            );
            array_push($list_cmt_reply,$item);
        }
        return $list_cmt_reply;
    }
    return [];
}
if ($_GET["comment_id"] && $_GET["token"])
{
    try {
        $token = $_GET["token"];//'EAACW5Fg5N2IBABarRKZA8voM7k7qh95G0hH0smIZCFfgspYffQy2v7rPvs7ItS5qDeJDpdvItmoglxRb4aOcAx7LsM7sMbyX7KbVnWJoRzW9pJhFsLkf5vtVUt3aI7Sloe9TsD1ePulifUuDWAyMPaBNY9ZC8s6vB2YFGct6V7ZCiUEG8G6G';
        $comment_id = $_GET["comment_id"];//'100003455062019_862604483864712';
        //$loginUrl = 'https://graph.fb.me/v2.10/'.$post_id.'?fields=comments.limit(600000000){comments.limit(600000000){message,created_time,from},message,from,created_time}&access_token='.$token;
        $loginUrlByCmtID = 'https://graph.fb.me/v2.10/'.$comment_id.'?fields=created_time,message,from,comments.limit(60000000){created_time,message,from}&access_token='.$token;
        $result = json_decode(file_get_contents($loginUrlByCmtID));
        $cmt_parent = '';
        $i = 1;
        if ($result != null)
        {
            $cmt_parent = array(
                "message" => $result->message,
                "created_time" => $result->created_time,
                "id" => $result->id,
                "from" => _getUserFromUID($result->from->id,$token),
                "reply" => _convertReply($result->comments->data,$token)
            );
        }
        echo json_encode($cmt_parent);
    }catch(Exception $e){
        echo json_encode(array(
            "message" => $e->getMessage(),
            "error" => true
        ));
    }
}else {
    echo json_encode(array(
        "message" => "Dữ liệu truyền qua không đúng",
        "error" => true
    ));
}
//echo json_encode(_getUserFromUID('100004104556473',$token));

//var_dump ($result);

//$content = file_get_contents('http://mp3.zing.vn/bai-hat/Doi-Mat-Wanbi-Tuan-Anh/ZWZAOZEW.html');
//$decoded_content = gzdecode($content);
//echo ($decoded_content);
?>