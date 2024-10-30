<?php

	header('Content-type: application/xml');

	parse_str($_SERVER['QUERY_STRING'], $params);

	//echo file_get_contents("http://track.mtbachelor.com/tyt.asp?passmediacode=" . $params['passmediacode'] . "&season=" . $params['season'] . "&currentday=" . $params['currentday']);

	$GLOBALS['season' . $params['widget_num']] = $params['season'];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"http://track.mtbachelor.com/tyt.asp?passmediacode=" . $params['passmediacode'] . "&season=" . $params['season'] . "&currentday=" . $params['currentday']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$contents=curl_exec($ch);
	curl_close($ch);
	echo $contents;
?>