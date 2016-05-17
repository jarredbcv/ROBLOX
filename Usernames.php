<?php
	// There's probably a simpler way to do this but I just don't know the APIs...
	libxml_use_internal_errors(true);
	if (array_key_exists('userId',$_GET)) {
		$url = 'https://www.roblox.com/users/'.urlencode($_GET['userId']).'/profile';
	}
	if ($url) {
		$doc = new DOMDocument();
		$doc->loadHTMLFile($url);
		$find = new DomXPath($doc);
		$node = $find->query('//span[@data-toggle="tooltip"]')->item(0);
		if ($node) {
			$usernames = explode(', ',str_replace('Previous usernames: ','',$node->getAttribute('title')));
		} else {
			$usernames = array();
		}
		array_unshift($usernames,$find->query('//div[@profile-header-layout="profileHeaderLayout"]')->item(0)->getAttribute('data-profileusername'));
		echo json_encode($usernames);
	}
?>