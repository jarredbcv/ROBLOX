<?php
    if (array_key_exists('param',$_GET)) {
        echo file_get_contents('https://search.roblox.com/catalog/json?Category=9&Keyword='.urlencode($_GET['param']));
    }
?>