<?php
// Settings
$dropboxURL = "https://dl.dropbox.com/u/1234567/"; // This is the URL that you wish to proxy through
$dropboxIndex = "index.html"; // This is the index file that is retrieved when no filename is given
$dropboxCopyrightHeader = "<!-- Copyright header -->\n"; // A copyright notice that is added to the top of HTML files
$dropboxCopyrightFooter = "\n<!-- Dropbox Proxy (http://github.com/AngeloG/Dropbox-proxy) -->"; // Same as above, but appears at the bottom

// Code
$filename = substr($_SERVER['REQUEST_URI'], 1);
if($filename == "") {
  $filename = $dropboxIndex;
}
$url = $dropboxURL . $filename;
$extension = substr($filename, -4);

if($extension) {
  $mime = "text/plain";
  $prepend = "";
  $append = "";

  switch($extension) {
  case ".png": $mime = "image/png"; break;
  case ".jpg": $mime = "image/jpg"; break;
  case ".gif": $mime = "image/gif"; break;
  case ".txt": $mime = "text/plain"; break;
  case "html":
  case ".htm":
    $mime = "text/html";
    $prepend = $dropboxCopyrightHeader;
    $append = $dropboxCopyrightFooter;
    break;
  default:
    header("Location: " . $url);
    exit;
  }

  $contents = @file_get_contents($url);
  if($contents) {
    header("Content-type: " . $mime);
    echo $prepend;
    echo $contents;
    echo $append;
    exit;
  } else {
    header("Content-type: text/html");
    echo "<!DOCTYPE html><html><head><title>Server error</title></head><body>";
    echo "<h1>Server error</h1>";
    echo "<p>Server gave an error for the file " . htmlentities($filename) . "</p>";
    echo "</body></html>";
    exit;
  }
} else {
  header("Location: " . $url);
  exit;
}
?>