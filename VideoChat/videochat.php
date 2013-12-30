<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Let's Chit Chat</title>
	<link rel="stylesheet" href="loading.css" type="text/css" />
	<link rel="stylesheet" href="fancybutton.css" type="text/css" />
	<!--script src="jquery-1.4.2.min.js" type="text/javascript" /-->
	<script src="http://static.opentok.com/webrtc/v2.0/js/TB.min.js"></script>
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	
	<!-- fancyBox -->
	<link rel="stylesheet" href="css/jquery.fancybox.css?v=2.0.5" type="text/css" media="screen" />
	<script type="text/javascript" src="js/jquery.fancybox.pack.js?v=2.0.5"></script>

	<!-- fancyBox button helpers -->
	<link rel="stylesheet" href="css/jquery.fancybox-buttons.css?v=2.0.5" type="text/css" media="screen" />
	<script type="text/javascript" src="js/jquery.fancybox-buttons.js?v=2.0.5"></script>

	<!-- fancyBox thumbnail helpers -->
	<link rel="stylesheet" href="css/jquery.fancybox-thumbs.css?v=2.0.5" type="text/css" media="screen" />
	<script type="text/javascript" src="js/jquery.fancybox-thumbs.js?v=2.0.5"></script>
	
	<?php
		$isCaller=1;
	    require_once 'Opentok-PHP-SDK/API_Config.php';
	    require_once 'Opentok-PHP-SDK/OpenTokSDK.php';

	    $apiObj = new OpenTokSDK('', '');

	    if(isset($_REQUEST['sessionId'])) {
	        $sessionId = $_REQUEST['sessionId'];
			$isCaller=0;
	    }
	    else {
	        $session = $apiObj->create_session($_SERVER["REMOTE_ADDR"], array(SessionPropertyConstants::P2P_PREFERENCE=>"enabled"));
	        $sessionId = $session->getSessionId();
	    }
	?>
	
	<script type="text/javascript">
	    var apiKey = '';
	    var sessionId = '<?php print $sessionId; ?>';
	    var token = '<?php print $apiObj->generate_token($sessionId); ?>';

	    TB.setLogLevel(TB.DEBUG);

	    var session = TB.initSession(sessionId);
	    session.addEventListener('sessionConnected', sessionConnectedHandler);
		session.addEventListener("streamCreated", streamCreatedHandler);
	    session.connect(apiKey, token);

	    var publisher;

	    function sessionConnectedHandler(event) {
	      // Put my webcam in a div
	      var publishProps = {height:240, width:320};
	      publisher = TB.initPublisher(apiKey, 'myPublisherDiv', publishProps);
	      // Send my stream to the session
	      session.publish(publisher);
		  document.getElementById("ball").style.display="none";
		  document.getElementById("ball1").style.display="none";
		  document.getElementById("message").style.display="none";
		  document.getElementById("home").style.backgroundColor="#101010";
		  subscribeToStreams(event.streams);
	    }
		
		function streamCreatedHandler(event) {
	    subscribeToStreams(event.streams);
		}
		
		function subscribeToStreams(streams) {
			var publishProps = {height:600, width:800};
			for (i = 0; i < streams.length; i++) {
				var stream = streams[i];
				if (stream.connection.connectionId != session.connection.connectionId) {
					session.subscribe(stream, 'new', publishProps);
				}
			}
		}
  </script>
</head>

<body class="home" id="home">
	<div class="ball" id="ball"></div>
	<div class="ball1" id="ball1"></div>
	<div class="message" id="message">Loading....</div>
	<?php 
		if($isCaller==1){
			//echo "<textarea id='pi' cols='150' rows='1' align='center'>Share this URL:";
			//echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]."?sessionId=".$sessionId; 
			//echo '</textarea>';
			//echo "<script type='text/javascript'> alert('Share this URL: ";
			//echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]."?sessionId=".$sessionId;
			//echo "');</script>";
			echo "<div id='url'><b>Share this URL:</b><br/>";
			echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]."?sessionId=".$sessionId; 
			echo '</div>';
			//echo '<div id="header"><a class="fancybox" href="#url">Get URL To Connect...</a></div>';
			echo '<div id="header"><div id="mybutton" class="column"><!--layout purposes only--><div class="button-wrapper"><a href="#url" class="fancybox button ocean">GetURL...</a></div></div></div>';
		}
	?>
	
	<div id="new"></div>
	<div id="myPublisherDiv"></div>
	<div id="footer">Coded by <font color="red">Dhruva Bhaswar</font> aka <font color="red">elektron</font></div>
	<script type="text/javascript">
		$(document).ready(function() {
		$(".fancybox").fancybox({
							helpers: { 
								title: {
									type: 'inside'
										}
									}
								});
		});
	</script> 
</body>
</html>
