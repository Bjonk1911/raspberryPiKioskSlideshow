<?php
$version = "RaspberryPi Kiosk Slideshow - Version " . file_get_contents("./version");
echo "<!-- " . $version . " -->\r\n";
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $version; ?></title>
  <meta name="robots" content="noindex, nofollow">

  <style>
    html, body {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      background-color: black;
    }

    #slideshow {
      width: 100vw;
      height: 100vh;
      object-fit: contain; /* keeps aspect ratio, no cropping */
      display: block;
      background-color: black;
    }
  </style>
</head>

<body>

<img id="slideshow" src="datetimenow.html">

<script type="text/javascript">
  var urls = [];
<?php
  $slideshow = file("./files/slideshow.txt");
  $outputSize = 0;
  $delay = 14 / 2 * 1000;

  for ($i = 0; $i < count($slideshow); $i++) {
      if (!(substr(trim($slideshow[$i]), 0, 1) === "#")) {
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
  var img = document.getElementById("slideshow");

  function showNext() {
    img.src = urls[urlnow];
    urlnow++;
    if (urlnow === urls.length) {
      urlnow = 0;
    }
    setTimeout(showNext, delay);
  }

  setTimeout(showNext, 1000);
</script>

</body>
</html>
