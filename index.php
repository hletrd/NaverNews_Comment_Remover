<?php
//error_reporting(-1);
//ini_set("display_errors", 1);
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$this_url = 'http://nn.0101010101.com/';

require_once('mobile.php');
$detect = new Mobile_Detect;
if ($detect->isMobile()) {
	$urlbase = 'http://m.news.naver.com';
} else {
	$urlbase = 'http://news.naver.com';
}

if (!isset($_GET['link'])) {
	$url = $urlbase;
} else if (strpos($_GET['link'], '..') === false) {
	$requrl = $_SERVER['REQUEST_URI'];
	$url = $urlbase . $requrl;
} else {
	$url = $urlbase;
}
curl_setopt($ch, CURLOPT_URL, $url);
$data = curl_exec($ch);



if (strpos(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL), 'm.news.naver.com') !== false) {
	header('Content-Type: text/html; charset=UTF-8');
	$ctype = 'UTF-8';
} else {
	header('Content-Type: text/html; charset=EUC-KR');
	$ctype = 'EUC-KR';
}

$match = array('href="http://news.naver.com/', 'href="http://m.news.naver.com/', 'href="/', 'id="social-comment"');
$replace = array('href="' . $this_url, 'href="' . $this_url, 'href="' . $this_url, 'id="social-comment" style="display: none;"');


$data = str_replace($match, $replace, $data);
$data = preg_replace('/<iframe id="ifrMemo"[^<]+><\/iframe>/', '', $data);
if ($detect->isMobile()){
	$data = preg_replace('/<body[^<]*>/', '<body><div style="font-size: 30pt; font-family: \'' . mb_convert_encoding('맑은 고딕', $ctype, 'UTF-8') . '\', \'Apple SD Gothic Neo\'; z-index: 10000; position: fixed !important; top: 0px !important; left: 0px !important; width: 100% !important; height: 50px; background-color: white;"><a style="text-align: center; width: 100%; position: absolute; left: 0px !important; top: 20px !important;" href="' . curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) . '">' . mb_convert_encoding('네이버 뉴스에서 보기', $ctype, 'UTF-8') . '</a></div>', $data);
} else {
	$data = preg_replace('/<body[^<]*>/', '<body><div style="font-size: 30pt; font-family: \'' . mb_convert_encoding('맑은 고딕', $ctype, 'UTF-8') . '\', \'Apple SD Gothic Neo\'; z-index: 10000; position: fixed !important; top: 0px !important; left: 0px !important; width: 100% !important; height: 50px; background-color: white;"><a style="text-align: center; width: 100%; position: absolute; left: 0px !important; top: 0px !important;" href="' . curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) . '">' . mb_convert_encoding('네이버 뉴스에서 보기', $ctype, 'UTF-8') . '</a></div>', $data);
}
echo $data;
