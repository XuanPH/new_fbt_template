<?php
set_time_limit(0);
error_reporting(0);
$log = fopen('log.html', 'w');
fwrite($log, '');
fclose($log);
$like = fopen('like.html', 'w');
fwrite($like, '');
fclose($like);
$token    = "EAACW5Fg5N2IBABarRKZA8voM7k7qh95G0hH0smIZCFfgspYffQy2v7rPvs7ItS5qDeJDpdvItmoglxRb4aOcAx7LsM7sMbyX7KbVnWJoRzW9pJhFsLkf5vtVUt3aI7Sloe9TsD1ePulifUuDWAyMPaBNY9ZC8s6vB2YFGct6V7ZCiUEG8G6G";

$idpost   = "675639245913325_1243780069099237"; //id Post trên Page
$idpost2  = "1243780069099237_"; //id post
$namepage = "BossGiay"; //name page
$getlike = json_decode(request('https://graph.facebook.com/v2.9/' . $idpost . '?fields=reactions.limit(10000).summary(true)&access_token=' . $token), true);
$countlike   = $getlike['reactions']['summary']['total_count'];
for ($i = 0; $i < $countlike; $i++) {
    $idlike = $getlike['reactions']['data'][$i]['id'];
    $luulog = fopen("like.html", "a");
    $data   = $idlike . '|';
    fwrite($luulog, $data);
    fclose($luulog);
}
$like = file_get_contents("like.html");
$getcmt   = json_decode(request('https://graph.facebook.com/' . $idpost . '/?fields=comments.summary(true).limit(10000){message}&access_token=' . $token), true);
$count    = $getcmt['comments']['summary']['total_count'];
$ketqua   = "706"; //câu trả lời không dấu , viết thường
for ($b = 0; $b < $count; $b++) {
    $cmt   = $getcmt['comments']['data'][$b]['message'];
    $idcmt = $getcmt['comments']['data'][$b]['id'];
    
    /* Check kết quả Cmt */
    if (strpos(strtolower(vn($cmt)), $ketqua) !== false) {
        /* Check Reply Cmt*/
        $getrepcmt    = json_decode(request('https://graph.facebook.com/v2.9/' . $idpost2 . $idcmt . '?fields=comments.summary(true).limit(10000){message}&access_token=' . $token), true);
        $count_repcmt = $getrepcmt['comments']['summary']['total_count'];
        
        for ($c = 0; $c < $count_repcmt; $c++) {
            $cmtrep   = $getrepcmt['comments']['data'][$c]['message'];
            $idcmtrep = $getrepcmt['comments']['data'][$c]['id'];
            $checktag = json_decode(request('https://graph.facebook.com/' . $idcmtrep . '?fields=message_tags&access_token=' . $token), true);
            $counttag = count($checktag['message_tags']);
            if ($counttag >= 3) {
                $getinfo  = json_decode(request('https://graph.facebook.com/' . $idcmtrep . '?fields=from&access_token=' . $token), true);
                $name     = $getinfo['from']['name'];
                $iduser   = $getinfo['from']['id'];
                /* Check Share */
                $getshare = file_get_contents('https://graph.facebook.com/' . $iduser . '/feed?limit=30&access_token=' . $token);
				if (strpos($like, $iduser) !== false) {
                    if (strpos($getshare, $namepage) !== false) {
                        $data = $name . "|<a href='https://facebook.com/" . $idcmtrep . "'>Xem Comment</a>(reply)(đã like)<br>\n";
                    } else {
                        $data = $name . "|<a href='https://facebook.com/" . $idcmtrep . "'>Xem Comment</a>(reply)(Chưa Share)(đã like)</br>\n";
                    
                    }
				}else{
					
					if (strpos($getshare, $namepage) !== false) {
                        $data = $name . "|<a href='https://facebook.com/" . $idcmtrep . "'>Xem Comment</a>(reply)(chưa like)<br>\n";
                    } else {
                        $data = $name . "|<a href='https://facebook.com/" . $idcmtrep . "'>Xem Comment</a>(reply)(Chưa Share)(chưa like)</br>\n";
                    
                    }
					
					
					
				}
				
				echo $data;
				$luulog = fopen("log.html", "a");
                fwrite($luulog, $data);
                fclose($luulog);
                
            }
            
        }
		/* END Check Reply Cmt*/
		
         /* Check Tag Friend*/
        $checktag = json_decode(request('https://graph.facebook.com/' . $idcmt . '?fields=message_tags&access_token=' . $token), true);
        $counttag = count($checktag['message_tags']);
        if ($counttag >= 3) {
            $getinfo  = json_decode(request('https://graph.facebook.com/' . $idcmt . '?fields=from&access_token=' . $token), true);
            $name     = $getinfo['from']['name'];
            $iduser   = $getinfo['from']['id'];
            /* Check Share */
            $getshare = file_get_contents('https://graph.facebook.com/' . $iduser . '/feed?limit=30&access_token=' . $token);
			
			if (strpos($like, $iduser) !== false) {
                    if (strpos($getshare, $namepage) !== false) {
                        $data = $name . "|<a href='https://facebook.com/" . $idcmt . "'>Xem Comment</a>(đã like)<br>\n";
                    } else {
                        $data = $name . "|<a href='https://facebook.com/" . $idcmt . "'>Xem Comment</a>(reply)(Chưa Share)(đã like)</br>\n";
                    
                    }
				}else{
					
					if (strpos($getshare, $namepage) !== false) {
                        $data = $name . "|<a href='https://facebook.com/" . $idcmt . "'>Xem Comment</a>(chưa like)<br>\n";
                    } else {
                        $data = $name . "|<a href='https://facebook.com/" . $idcmt . "'>Xem Comment</a>(Chưa Share)(chưa like)</br>\n";
                    
                    }
				}
			echo $data;
            $luulog = fopen("log.html", "a");
            fwrite($luulog, $data);
            fclose($luulog);
        }
        
    }
    
}

function request($url)
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return FALSE;
    }
    
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HEADER => FALSE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_ENCODING => '',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36',
        CURLOPT_AUTOREFERER => TRUE,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 0
    );
    
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    unset($options);
    return $http_code === 200 ? $response : FALSE;
}
function vn($str)
{
    
    $unicode = array(
        
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        
        'd' => 'đ',
        
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        
        'i' => 'í|ì|ỉ|ĩ|ị',
        
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        
        'D' => 'Đ',
        
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ'
        
    );
    
    foreach ($unicode as $nonUnicode => $uni) {
        
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        
    }
    
    return $str;
    
}
?>