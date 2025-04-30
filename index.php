<?php
$version = "RaspberryPi Kiosk Slideshow - Version " . file_get_contents("./version");
echo "<!-- " . $version . " -->\r\n";
?>
<!DOCTYPE html>
<html>
<head>
  <title>
      <?php echo $version; ?>
  </title>
  <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
  <style type="text/css">
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
      background-color: black;
    }

    iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: none;
      padding-top: 0;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      -webkit-box-sizing: border-box;
    }
  </style>
</head>
<body>
<iframe src="datetimenow.html" id="firstFrame" style="visibility: hidden;"></iframe>
<iframe src="datetimenow.html" id="secondFrame" style="visibility: visible;"></iframe>
<script type="text/javascript">
  var urls = [];
  <?php
  $slideshow = file("./files/slideshow.txt");
  $outputSize = 0;
  $delay = 14 / 2 * 1000;

  for ($i = 0; $i < count($slideshow); $i++) {
      if (!(substr($slideshow[$i], 0, 1) === "#")) {
          $enhancedUrl = trim($slideshow[$i]);
          if (!(substr($enhancedUrl, 0, 4) === "http")) {
              $enhancedUrl = "./files/" . $enhancedUrl;
          }
          echo "urls[" . $outputSize . "] = '" . $enhancedUrl . "';\r\n";
          $outputSize++;
      } else if (substr($slideshow[$i], 0, 35) === "# setting-transition-delay=") {
          $delayvalue = substr($slideshow[$i], 35);
          $delay = $delayvalue / 2 * 1000;
      }
  }
  echo "var delay = " . $delay . ";\r\n";
  ?>
  var urlnow = 0;
  setTimeout("loadIntoFirstFrame()", 1000);

  function loadIntoFirstFrame() {
    document.getElementById('firstFrame').src = urls[urlnow];
    urlnow++;
    if (urlnow === urls.length) {
      urlnow = 0;
    }
    setTimeout("switchToFirstFrame()", delay);
  }

  function switchToFirstFrame() {
    document.getElementById('firstFrame').style.visibility = 'visible';
    document.getElementById('secondFrame').style.visibility = 'hidden';
    setTimeout("loadIntoSecondFrame()", delay);
  }

  function loadIntoSecondFrame() {
    document.getElementById('secondFrame').src = urls[urlnow];
    urlnow++;
    if (urlnow === urls.length) {
      urlnow = 0;
    }
    setTimeout("switchToSecondFrame()", delay);
  }

  function switchToSecondFrame() {
    document.getElementById('firstFrame').style.visibility = 'hidden';
    document.getElementById('secondFrame').style.visibility = 'visible';
    setTimeout("loadIntoFirstFrame()", delay);
  }
</script>
</body>
</html>