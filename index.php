<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>URL Explainer</title>

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h1>URL Explainer</h1>
			<input id="url" size="100">
			<button type="button" onclick="myFunction()">Explain</button>
			<br> Copy and paste your URL above! <i>Please make sure to include the http:// or https:// protocol. </i>
			<p></p>
		</div>
	</div>
	</div>

    <script src="URI.js"></script>

    <script>
	
    // does all things to explain URL
    function myFunction() {
        $('[data-toggle="popover"]').popover();
        $("p").empty();
        var url = String(document.getElementById("url").value);
        parseURL(url);
        //detectRedirect(url);
        //findSiteTitle(url);
        //isSafe(url);
        $.ajax({
              url: proxyurl,
              async: true,
              success: function (response) {
                  if(!URI(site).equals(response)) {$( "p" ).append("<br>This URL redirects to: <b>" + response + "</b><br>");}
              },
              error: function () {
                  $('p').append('<br>This site does not exist!');
              }
        });
	}

    // detects any shortened/redirected URLs
    function detectRedirect(site) {
        var proxyurl = "getRedirect.php?url=" + site;
        $.ajax({
              url: proxyurl,
              async: true,
              success: function (response) {
                  if(!URI(site).equals(response)) {$( "p" ).append("<br>This URL redirects to: <b>" + response + "</b><br>");}
              },
              error: function () {
                  $('p').append('<br>This site does not exist!');
              }
          });
          
    }

    // site title
    function findSiteTitle(site) {
        var proxyurl = "getURLTitle.php?url=" + site;
          $.ajax({
            url: proxyurl,
            async: true,
            success: function(response) {
                $( "p" ).append("<br>Site Title: " + response+ "<br>");
            },   
            error: function(e) {
                $( "p" ).append( "<br>site title cannot be found" );
            }
          });
    }

    // uses URI.js to parse URL into components, explain each part?
    function parseURL(url) {
        var uri = new URI(url);

        if (uri == null) {
            $( "p" ).append( "Please enter a valid URL." );
            return;
        }
        
        else {
            uri.normalize();
            uri.readable();
            if (uri.domain()) {
                $( "p" ).append("<br>This URL goes to: <b>" + uri.domain() + "</b><br>");
            }
            if (uri.protocol()) {
                $( "p" ).append("<br>Protocol: " + uri.protocol());
                /*if (uri.protocol().indexOf("s") != -1) {
                    $( "p" ).append(" <a href='#' data-toggle='popover' data-trigger='hover' title='HTTPS' data-content='requires secure connection'><span class='glyphicon glyphicon-ok-sign' aria-hidden='true'></span></a>");
                }*/
            } 
            if (uri.userinfo()) {
                $( "p" ).append("<br>Userinfo: " + uri.userinfo());
            }
            if (uri.domain()) {
                $( "p" ).append("<br>Domain: " + uri.domain());
            }
            if (uri.subdomain()) {
                $( "p" ).append("<br>Subdomain: " + uri.subdomain());
            }
            if (uri.port()) {
                $( "p" ).append("<br>Port: " + uri.port());
            }
            if (uri.path()) {
                $( "p" ).append("<br>Path: " + uri.path());
            }
            if (uri.query()) {
                $( "p" ).append("<br>Search: " + uri.query());
            }
            if (uri.fragment()) {
                $( "p" ).append("<br>Fragment: " + uri.fragment());
            }
            $( "p" ).append("<br>");
        }

        function isSafe(url) {
        var request = "https://sb-ssl.google.com/safebrowsing/api/lookup?client=serenaz&key=AIzaSyCyMC1-qe7EfBT-utXlOM3mIyWr2OQThFE&appver=1.5.2&pver=3.1&url=" + URI.encode(url);
        var client = new XMLHttpRequest()
	client.open("GET", "http://example.com/hello")
	client.onreadystatechange = function() { 
	if (request.status == 200) {
	$( "p" ).append("UNSAFE");
	}
	else if (request.status == 204) {
	$( "p" ).append("SAFE");
	}
	else {
	$( "p" ).append("something's wrong with your url");
	}
	}
	client.send()
        }
        
        /*<table class = "table">
        <tr>
            <th>Protocol:</th>
            <td>result.protocol</td>
        </tr>
        <tr>
            <th>Username:</th>
            <td>result.username</td>
        </tr>
        <tr>
            <th>Password:</th>
            <td>result.password</td>
        </tr>
        <tr>
            <th>Hostname:</th>
            <td>result.hostname</td>
        </tr>
        <tr>
            <th>Port:</th>
            <td>result.port</td>
        </tr>
        <tr>
            <th>Path:</th>
            <td>result.path</td>
        </tr>
        <tr>
            <th>Query:</th>
            <td>result.query</td>
        </tr>
        <tr>
            <th>Fragment:</th>
            <td>result.fragment</td>
        </tr>
        <tr>
            <th rowspan="2">Telephone:</th>
            <td>555 77 854</td>
        </tr>
        <tr>
            <td>555 77 855</td>
        </tr>
        </table>*/
    }

	</script>

    <?php

    $url = $_REQUEST["url"];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    
    // get redirect
    $effectiveurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    echo "<br>Effective URL: $effectiveurl<br>"

    // get title
    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    $nodes = $doc->getElementsByTagName('title');
    $title = $nodes->item(0)->nodeValue;
    echo "<br>Title: $title <br>";

    curl_close($ch);

    // get Google safe browsing result
    $req = "https://sb-ssl.google.com/safebrowsing/api/lookup?client=serenaz&key=AIzaSyCyMC1-qe7EfBT-utXlOM3mIyWr2OQThFE&appver=1.5.2&pver=3.1&url=" + $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $req);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    echo curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    ?>

</body>
</html>