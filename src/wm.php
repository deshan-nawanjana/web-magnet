<?php

    /*
        WebMagnet by Deshan Nawanjana
        https://github.com/deshan-nawanjana/web-magnet
    */

    // get url from query or return
	if(!isset($_GET['url'])) { echo 'No URL given.'; exit(); }

    // function to convert url to file
	function url_to_nme($x) {
		$x = str_replace(':', '-', $x);
		$x = str_replace('/', '-', $x);
		$x = str_replace('?', '-', $x);
		$x = str_replace('&', '-', $x);
		$x = str_replace('=', '-', $x);
		return $x;
	}

	$url = $_GET['url'];

    // get cache option
	if(isset($_GET['cache'])) { $che = 1; }

    // create cache folder
    if(!file_exists('curl_tmp/')) { mkdir('curl_tmp/'); }

    // create cache file name
	if(isset($che)) {
		$nme = 'curl_tmp/' . url_to_nme($url) . '.txt';
	}

    // return cache if available
	if(isset($nme)) {
		if(file_exists($nme) && isset($che)) {
			echo file_get_contents($nme);
			exit();
		}
	}

    // get http content from curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// blindly accept the certificate
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	// decode response
	curl_setopt($ch, CURLOPT_ENCODING, true);
	$response = curl_exec($ch);
	curl_close($ch);

    // cache if enabled
	if(isset($nme)) {
		$file = fopen($nme, 'w');
		fwrite($file, $response);
		fclose($file);
	}

	// return new content
	echo $response;

?>