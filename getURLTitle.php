<!DOCTYPE html>
<html>
<body>

<?php
function file_get_contents_curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

$url = $_REQUEST["url"];
$html = file_get_contents_curl($url);

// method 1
preg_match('/<title>(.+)<\/title>/',$html,$matches);
$title = $matches[1];
echo $title;

/*
// method 2 for other meta data
//parsing begins here:
$doc = new DOMDocument();
@$doc->loadHTML($html);
$nodes = $doc->getElementsByTagName('title');

//get and display what you need:
$title = $nodes->item(0)->nodeValue;
echo "Title: $title". '<br/><br/>';
*/
?>

</body>
</html>