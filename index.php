<?php
// https://t.me/seoyam
//ini_set("log_errors",1);
//ini_set("error_reporting",E_WARNING | E_ERROR | E_PARSE | E_NOTICE);
//ini_set("error_log",__DIR__."/log_local.txt");
date_default_timezone_set("Asia/Bangkok");
require_once(__DIR__."/php/config.php");
require_once(__DIR__."/php/func.php");
$start = microtime(true);

// Определяем глобальные переменные
$GLOBAL["host"] = $_SERVER["HTTP_HOST"];
$GLOBAL["scheme"] = (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] === "https") ? "https" : "http";
$GLOBAL["uri"] = $_SERVER["REQUEST_URI"];
$GLOBAL["folder"] = substr($GLOBAL["uri"], 0, strrpos($GLOBAL["uri"], "/") + 1);
$GLOBAL["filename"] = substr($GLOBAL["uri"], strrpos($GLOBAL["uri"], "/") + 1);
$GLOBAL["sub"] = substr($GLOBAL["host"], 0, strpos($GLOBAL["host"], "."));
$GLOBAL["domain"] = substr($GLOBAL["host"], strpos($GLOBAL["host"], ".") + 1);
// Проверяем, есть ли в URL ".html?" (то есть, .html с параметрами)
if (preg_match('/(.*\.html)\?.*/', $GLOBAL["uri"], $matches)) {
    $cleanUrl = "{$GLOBAL["scheme"]}://{$GLOBAL["host"]}{$matches[1]}"; // Оставляем только .html без параметров
    $redirectType = rand(0, 1) ? 301 : 302; // 50% 301, 50% 302
    header("Location: $cleanUrl", true, $redirectType);
    exit();
}
// Формируем формат названий страниц в пределах хоста
$GLOBAL["url_format"] = substr(str_shuffle("000111"),0,mt_rand(1,2));
$GLOBAL["separator"] = substr(str_shuffle("-"),0,1);
// Формируем цвета
$GLOBAL["color_background"] = sprintf("#%02X%02X%02X",mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
$b = 0.299*hexdec($GLOBAL["color_background"][1].$GLOBAL["color_background"][2])+0.587*hexdec($GLOBAL["color_background"][3].$GLOBAL["color_background"][4])+0.114*hexdec($GLOBAL["color_background"][5].$GLOBAL["color_background"][6]);
$GLOBAL["color_text"] = ($b<128) ? "#FFFFFF" : "#000000";
// Инициализируем генератор случайных чисел по странице
mt_srand(crc32($GLOBAL["host"].$GLOBAL["uri"]));
// Получаем кейворд страницы
{
    $file_array = list_files(__DIR__ . "/keyword");
    if (!empty($file_array)) {
        $random_file = $file_array[mt_rand(0, count($file_array) - 1)];
        $key_array = file($random_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        shuffle($key_array);
    } else {
        $key_array = [];
    }
    // Проверяем, заканчивается ли URI на "/"
    if (substr($GLOBAL["uri"], -1) == "/") {
        $GLOBAL["keyword"] = str_replace("/", "", urldecode($GLOBAL["uri"]));
        $GLOBAL["keyword"] = str_replace("-", " ", $GLOBAL["keyword"]);
    }
    // Если keyword пустой, берем случайный из файла
    if (empty($GLOBAL["keyword"]) && !empty($key_array)) {
        $GLOBAL["keyword"] = $key_array[mt_rand(0, count($key_array) - 1)];
    }
}
// Формируем дополнительные переменные
$GLOBAL["keyword2"] = mb_convert_case($GLOBAL["keyword"], MB_CASE_TITLE, "UTF-8");
$GLOBAL["hash"] = md5($GLOBAL["keyword2"]);
$GLOBAL["title"] = $GLOBAL["keyword2"];
// Добавляем хост в title, если включено
if ($GLOBAL["title_host"] == "1") {
    $GLOBAL["title"] .= " - " . $GLOBAL["host"];
}
$date = Time();
if ($GLOBAL["log"]=="1")
{
  create_dir(__DIR__."/log");
  add_file(__DIR__."/log/".$GLOBAL["domain"].".txt",date("d.m.Y H:i:s",$date)."\r\n".$_SERVER["HTTP_USER_AGENT"]."\r\n".$_SERVER["REMOTE_ADDR"]."\r\n"."http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."\r\n".$GLOBAL["keyword"]."\r\n\r\n");
}
if (stristr($GLOBAL["uri"], "robots.txt")) {
    header('Content-Type: text/plain; charset=UTF-8');
    echo "User-Agent: *\n";
    echo "Sitemap: http://" . $_SERVER["HTTP_HOST"] . "/sitemap.xml\n";
    exit;
}
if (stristr($GLOBAL["uri"],".css"))
{
  $s = file_get_contents("style.css");
  echo($s);
  exit;
}
// Карта сайта
if (stristr($GLOBAL["uri"],".xml"))
{
  mt_srand((double)microtime()*1000000);
  $keyword_array = array();
  $array = list_files(__DIR__."/keyword");
  if (!empty($array))
  {
    foreach ($array as $key=>$value)
    {
      $array2 = file($value,FILE_IGNORE_NEW_LINES);
      foreach ($array2 as $key2=>$value2)
      {
        $keyword_array[] = $value2;
      }
    }
  }
  shuffle($keyword_array);
  array_splice($keyword_array,$GLOBAL["sitemap_count"]);
  header('Content-Type: application/xml; charset=utf-8');
  header('Cache-Control: no-transform');
  echo('<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
');
  $url = $GLOBAL["scheme"]."://".$GLOBAL["host"].$GLOBAL["folder"];
  echo('  <url>
    <loc>'.$url.'</loc>
    <lastmod>'.date("Y",$date).'-'.date("m",$date).'-'.date("d",$date).'</lastmod>
    <priority>0.'.mt_rand(5,9).'</priority>
  </url>
');
  foreach ($keyword_array as $key=>$value)
  {
    $url = get_page($value,$GLOBAL["url_format"],$GLOBAL["separator"]);
    echo('  <url>
    <loc>'.$url.'</loc>
    <lastmod>'. date("Y",$date).'-'.date("m",$date).'-'.date("d",$date).'</lastmod>
    <priority>1</priority>
  </url>
');
  }
  echo('</urlset>');
  exit;
}
if (stristr($GLOBAL["uri"],".txt"))
{
  mt_srand((double)microtime()*1000000);

  $uri = str_replace(".txt","",$GLOBAL["uri"]);
  $array = explode("-",$uri);
  if (!empty($array[1]) and is_numeric($array[1]))
    $count = $array[1];
  else
    $count = $GLOBAL["sitemap_count"];
  $array = list_files(__DIR__."/keyword");
  if (!empty($array))
  {
    foreach ($array as $key=>$value)
    {
      $array2 = file($value,FILE_IGNORE_NEW_LINES);
      foreach ($array2 as $key2=>$value2)
      {
        $keyword_array[] = $value2;
      }
    }
  }
  shuffle($keyword_array);
  array_splice($keyword_array,$count);
  header('Content-Type: text/plain; charset=utf-8');
  foreach ($keyword_array as $key=>$value)
  {
    $url = get_page($value,$GLOBAL["url_format"],$GLOBAL["separator"]);
    echo($url.'
');
  }
  exit;
}
if (stristr($GLOBAL["uri"],"xrumer.txt"))
{
  mt_srand((double)microtime()*1000000);
  $array = list_files(__DIR__."/keyword");
  foreach ($array as $key=>$value)
  {
    $array2 = file($value,FILE_IGNORE_NEW_LINES);
    foreach ($array2 as $key2=>$value2)
    {
      $keyword_array[] = $value2;
    }
  }
  shuffle($keyword_array);
  array_splice($keyword_array,$GLOBAL["sitemap_count"]);
  header('Content-Type: text/plain; charset=utf-8');
  foreach ($keyword_array as $key=>$value)
  {
    $url = get_page($value,$GLOBAL["url_format"],$GLOBAL["separator"]);
    echo('<a href="'.$url.'">'.$value.'</a>'.'
');
  }
  exit;
}
if (stristr($GLOBAL["uri"],".txt"))
{
  mt_srand((double)microtime()*1000000);
  $uri = str_replace(".txt","",$GLOBAL["uri"]);
  $array = explode("-",$uri);
  if (!empty($array[1]) and is_numeric($array[1]))
    $count = $array[1];
  else
    $count = $GLOBAL["sitemap_count"];
  $array = list_files(__DIR__."/keyword");
  foreach ($array as $key=>$value)
  {
    $array2 = file($value,FILE_IGNORE_NEW_LINES);
    foreach ($array2 as $key2=>$value2)
    {
      $keyword_array[] = $value2;
    }
  }
  shuffle($keyword_array);
  array_splice($keyword_array,$count);
  header('Content-Type: text/plain; charset=utf-8');
  foreach ($keyword_array as $key=>$value)
  {
    $url = get_page($value,$GLOBAL["url_format"],$GLOBAL["separator"]);
    echo($url.'
');
  }
  exit;
}
// Инициализируем генератор случайных чисел по странице
mt_srand(crc32($GLOBAL["host"].$GLOBAL["uri"]));
// Формируем ссылки для внутренней перелинковки
if ($GLOBAL["internal_link"]=="1")
{
  shuffle($key_array);
  $link_array = array();
  for ($i=0; $i<20; $i++)
  {
    if ($GLOBAL["page_mode"]=="1")
    {
      $page = str_replace(" ","-",$key_array[$i]);
	$link_url = htmlspecialchars($GLOBAL["scheme"] . "://" . $GLOBAL["host"] . "/" . $page . "/", ENT_QUOTES, 'UTF-8');
	$link_array[$i][0] = $link_url;
     }
    else
    {
	$url_part = get_string($GLOBAL["url_format"], $GLOBAL["separator"]);
	$encoded_url_part = htmlspecialchars($url_part, ENT_QUOTES, 'UTF-8');
	$link_array[$i][0] = htmlspecialchars($GLOBAL["scheme"] . "://" . $GLOBAL["host"] . "/" . $encoded_url_part . ".html", ENT_QUOTES, 'UTF-8');
    }
	$link_array[$i][1] = ($key_array[$i]);
  }
}
// Формируем ссылки для внешней перелинковки
if ($GLOBAL["external_link"]=="1")
{
  shuffle($key_array);
  $array = file(__DIR__."/domain.txt",FILE_IGNORE_NEW_LINES);
  if ($GLOBAL["sub_mode"]=="1") $sub_array=file(__DIR__."/php/sub.txt",FILE_IGNORE_NEW_LINES);
  $link_array2 = array();
  for ($i=0; $i<20; $i++)
  {
    $domain = $array[mt_rand(0,count($array)-1)];

    if ($GLOBAL["sub_mode"]=="1")
      $sub = $sub_array[mt_rand(0,count($sub_array)-1)];
    else
      $sub = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"),0,mt_rand(4,10));

    if ($GLOBAL["page_mode"]=="1")
    {
      $page = str_replace(" ","-",$key_array[$i]);
      $link_array2[$i][0] = $GLOBAL["pack_scheme"]."://".$sub.".".$domain."/".$page."/";
    }
    else
    {
      $url_format = substr(str_shuffle("00001111"),0,mt_rand(2,4));
      $separator = substr(str_shuffle("-_"),0,1);
      $link_array2[$i][0] = $GLOBAL["pack_scheme"]."://".$sub.".".$domain."/".get_string($url_format,$separator).".html";
    }
    $link_array2[$i][1] = ucwords($key_array[$i]);
  }
}
// Формируем контент
header('Content-Type: text/html; charset=UTF-8');
require_once(__DIR__."/php/page.php");
exit;
// https://t.me/seoyam
?>
