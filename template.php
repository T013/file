<?php // https://t.me/seoyam
function get_template()
{
  GLOBAL $GLOBAL, $key_array, $content_array, $replacement_position, $replacement_counter, $replacement_string;
  $content_array = init();
  $count_insert = 0;
  while ($count_insert<8 or $count_insert>12)
  {
    $content="";
    $count = mt_rand(4,6);
    for ($i=1; $i<=$count; $i++)
    {
      $div_tree = div_tree();
      $content = $content.$div_tree;
    }
    $count_insert = preg_match_all('!\[insert\]!si',$content,$match);
  }
  $content = $content_array["header"].$content.$content_array["end"];
// Формируем таблицу стилей
  $style = '<style>
body * { margin: 0; padding: 0; }
body { font-family: [Arial|Tahoma|Verdana|Times New Roman|Georgia|Trebuchet MS|Garamond|Helvetica|sans-serif]; font-size: [14-16]px; color: [color:1]; background-color: [background_color:1]; margin: 0; padding: 0; }
h1 { margin-bottom: [20-30]px !important; }
h2 { margin-bottom: [10-20]px !important; }
h2 { margin-bottom: [10-20]px !important; }
a { color: [color:1]; }
ol { margin-left: [15-25]px !important; margin-bottom: [20-30]px !important; }
ul li a { display: block; padding: [5-10]px [15-25]px; }
dl { margin: [20-30]px 0px !important; }
dt { font-weight: bold; }
dd { margin-bottom: [10-15]px !important; }
table{text-align:left;border-collapse:collapse!important;margin-top:[20-30]px!important;margin-bottom:[30-40]px!important;width:100%}table tr th,table tr td{border:1px solid [color:3]!important;padding:[4-10]px!important}@media screen and (max-width:600px){table{display:block;width:100%;overflow-x:auto;white-space:nowrap}}
pre { border-left: 1px solid [color:3]; padding: [5-10]px [20-30]px !important; margin: [20-40]px 0px !important; white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; word-wrap: break-word; overflow: hidden; }
.st1 { font-family: [Arial|Tahoma|Verdana|Times New Roman|Georgia|Trebuchet MS|Garamond|Helvetica|sans-serif|MS Sans Serif]; font-size: [20-24]px; text-align: [left|center|right]; color: [color:2]; background-color: [background_color:2]; padding: [15-25]px; }
.st1 a { color: [color:2]; text-decoration: none; }
.st1 ul li { list-style: none outside; float: left; font-size: [14-18]px; margin-right: [20-30]px; }
.st1 ul li a { font-size: [14-16]px; color: [color:2]; text-decoration: none; }
.st2 { clear: both; float: [content_align]; width: [content_width]%; font-family: [Arial|Tahoma|Verdana|Times New Roman|Georgia|Trebuchet MS|Garamond|Helvetica|sans-serif|MS Sans Serif]; font-size: [14-16]px; color: [color:3]; background-color: [background_color:3]; line-height: 1.[4-8]; }
.st2 p { margin-bottom: [15-25]px; }
.st2 div { margin: [15-25]px; }
.st2 img { margin: [15-25]px; }
.st2 a { color: [color:3]; text-decoration: underline !important; }
.st2 a:hover { color: [color:3]; text-decoration: underline; }
.st2 ul { margin: [20-30]px [15-25]px; }
.st2 ul li { margin: 0px !important; padding: 0px !important; }
.st2 ul li a { padding: 0px [5-10]px; }
.st2 ol li { margin-bottom: [5-15]px; }
.st3 { float: [sidebar_align]; width: [sidebar_width]%; font-family: [Arial|Tahoma|Verdana|Times New Roman|Georgia|Trebuchet MS|Garamond|Helvetica|sans-serif|MS Sans Serif]; font-size: [14-16]px; color: [color:4]; background-color: [background_color:4]; line-height: 1.[4-8]; }
.st3 ul li { list-style: none outside; }
.st3 ul li a { display: block; color: [color:5]; background-color: [background_color:5]; text-decoration: none; padding: [10-20]px [15-25]px; }
.st3 ul li a:hover { color: [color:6]; background-color: [background_color:6]; }
.st4 { clear: both; }
.st5 { clear: both; font-family: [Arial|Tahoma|Verdana|Times New Roman|Georgia|Trebuchet MS|Garamond|Helvetica|sans-serif|MS Sans Serif]; font-size: [14-16]px; text-align: [left|center|right]; color: [color:7]; background-color: [background_color:7]; padding: [15-25]px; }
@media screen and (max-width: [600-700]px) { .st2 { width: 100%; } .st3 { width: 100%; } }
</style>';
  preg_match_all('!^<div class="(.*?)">!msi',$content,$match);
  $style = preg_replace('!.st1!si','.'.$match[1][0],$style);
  $style = preg_replace('!.st2!si','.'.$match[1][1],$style);
  $style = preg_replace('!.st3!si','.'.$match[1][2],$style);
  $style = preg_replace('!.st4!si','.'.$match[1][3],$style);
  $style = preg_replace('!.st5!si','.'.$match[1][count($match[1])-1],$style);
  $header_style = $match[1][0];
  $content_style = $match[1][1];
  $link_style = $match[1][2];
  $clear_style = $match[1][3];
  preg_match_all('!\[background_color:(.*?)\]!msi',$style,$match);
  foreach ($match[1] as $key=>$value)
  {
    $background_color = color_random();
    $style = preg_replace('!\[background_color:'.$value.'\]!msi',$background_color,$style);
    if (color_brightness($background_color)==1) $color = "#000000"; else $color = "#FFFFFF";
    $style = preg_replace('!\[color:'.$value.'\]!msi',$color,$style);
  }
  $style = get_macro2($style);
  if (mt_rand(1,100)<=50)
  {
    preg_match('!body {.*?}!si',$style,$match);
    $match[0] = preg_replace('!margin:.*?;!si','margin: 0 auto;',$match[0]);
    $style_width = mt_rand(980,1600);
    $match[0] = preg_replace('!{ !si','{ width: '.$style_width.'px; ',$match[0]);
    $style = preg_replace('!body {.*?}!si',$match[0],$style);
    $style = preg_replace('!</style>!si','@media screen and (max-width: '.$style_width.'px) { body { width: 100%; margin: 0px; } }
</style>',$style);
  }
  preg_match('!<style>(.*?)</style>!si',$style,$match);
  $style2 = shuffle_style($match[1]);
  $style = preg_replace('!<style>(.*?)</style>!si',$style2,$style,1);
  $style = preg_replace_callback('!#000000!si','color_replace_dark',$style);
  $style = preg_replace_callback('!#FFFFFF!si','color_replace_light',$style);

  preg_match_all('!font-family: (.*?);!si',$style,$match);
  foreach ($match[1] as $key=>$value)
  {
    if (strpos($value," ")) $style=str_replace_once('font-family: '.$value.';','font-family: \''.$value.'\';',$style);
  }

  if (mt_rand(1,100)<=50)
  {
    $content_align = "left";
    $sidebar_align = "right";
  }
  else
  {
    $content_align = "right";
    $sidebar_align = "left";
  }
  $style = str_replace('[content_align]',$content_align,$style);
  $style = str_replace('[sidebar_align]',$sidebar_align,$style);
  $content_width = mt_rand(60,70);
  $sidebar_width = 100-$content_width;
  $style = str_replace('[content_width]',$content_width,$style);
  $style = str_replace('[sidebar_width]',$sidebar_width,$style);
  $style = preg_replace('!<p>\s+</p>!msi','',$style);
  $content = preg_replace('!\[style\]!si',$style,$content);
  $tag_array = array("<p>","<span>","<h2>","<h3>");
  $tag = '<a href="'.$GLOBAL["scheme"]."://".$GLOBAL["host"].$GLOBAL["uri"].'"><?php echo($GLOBAL["keyword2"]); ?></a>';
  if (mt_rand(1,100)<=50)
  {
    $tag_random = $tag_array[mt_rand(0,count($tag_array)-1)];
    $tag_random1 = $tag_random;
    if (mt_rand(1,100)<=50) $tag_random1=str_replace('>',' class="'.get_name().'">',$tag_random1);
    $tag_random2 = str_replace('<','</',$tag_random);
    $tag = $tag_random1.$tag.$tag_random2;
  }
  $content = preg_replace('!\[insert\]!si',$tag,$content,1);
  preg_match('!^<div class="'.$header_style.'">.*?^</div>!msi',$content,$match);
  $content2 = preg_replace('!^</div>!msi',$content_array["indent"].'<div style="clear:both;"></div>
</div>',$match[0],1);
  $content = preg_replace('!^<div class="'.$header_style.'">.*?^</div>!msi',$content2,$content,1);
  preg_match('!^<div class="'.$content_style.'">.*?^</div>!msi',$content,$match);
  $content2 = preg_replace('!\[insert\]!si','[main]',$match[0],1);
  $content = preg_replace('!^<div class="'.$content_style.'">.*?^</div>!msi',$content2,$content,1);
  preg_match('!^<div class="'.$link_style.'">.*?^</div>!msi',$content,$match);
  $content2 = preg_replace('!\[insert\]!si','<ul>[relate(10-20)]</ul>',$match[0],1);
  $content = preg_replace('!^<div class="'.$link_style.'">.*?^</div>!msi',$content2,$content,1);
  $content = substr_replace($content,'[copyright]',strrpos($content,'[insert]'),strlen('[insert]'));
  $content = preg_replace('!\[insert\]!si','<ul>[relate(2-4)]</ul>',$content,mt_rand(1,2));
  $content = preg_replace('!\[insert\]!si','',$content);
  $template = $content;
  $content2 = '<h1><?php echo($GLOBAL["keyword2"]); ?></h1>
<?php echo($header_list); ?>
<?php echo("<p>".$bd_content."</p>"); ?>
<?php echo($content); ?>';
  $template = preg_replace('!\[main\]!si',$content2,$template,1);
  $y1 = date("Y");
  $y2 = date("Y")-2;
  $template = preg_replace('!\[copyright\]!si',$GLOBAL["keyword2"].' &copy; '.get_value_range($y2."-".$y1),$template);
  $template = preg_replace('!<title></title>!si','<title><?php echo mb_strtoupper($GLOBAL["keyword2"]); ?> <?php echo($GLOBAL["title1"]); ?></title>',$template);
  $template = get_macro2($template);
  $result = $template;
  return ($result);
}
function div_tree($k1=2,$k2=6)
{
  GLOBAL $content_array;
  $s="";
  $count = mt_rand($k1,$k2);
  $i=0;
  while ($i<$count)
  {
    $s = $s.str_repeat($content_array["indent"],$i);
    $s = $s.'<div class="'.get_name().'">
';
    if ($i>1 and mt_rand(1,100)<=50) $s=$s.div_subtree($i);
    $i++;
  }
  $s = $s.str_repeat($content_array["indent"],$i);
  $s = $s.'[insert]
';
  $i--;
  while ($i>=0)
  {
    $n=$i;
    $s = $s.str_repeat($content_array["indent"],$n);
    $s = $s.'</div>
';
    $i--;
  }
  return ($s);
}
function div_subtree($k)
{
  GLOBAL $content_array;
  $s = $s.str_repeat($content_array["indent"],$k+1);
  $s = $s.'[insert]
';
  $count = mt_rand(2,$k);
  $i=$k;
  while ($i>=2)
  {
    $s = $s.str_repeat($content_array["indent"],$i);
    $s = $s.'</div>
';
    $i--;
  }
  $i++;

  while ($i<=$k)
  {
    $n=$i;
    $s = $s.str_repeat($content_array["indent"],$n);
    $s = $s.'<div class="'.get_name().'">
';
    $i++;
  }
  return ($s);
}
function get_name($k1=3,$k2=6)
{
  $array = array("aeiouy","bcdfghjklmnpqrstvwxz");
  $k = mt_rand(0,1);
  $s = "";
  $count = mt_rand($k1,$k2);
  for ($i=1; $i<=$count; $i++)
  {
    $n = mt_rand(0,strlen($array[$k])-1);
    $s = $s.$array[$k][$n];
    if ($k==1) $k--; else $k++;
  }
  return ($s);
}
function init()
{
  GLOBAL $GLOBAL;
  $content_array = array();
  $title_separator_array = array(" - ",". "," | ",", "," : ");
  $content_array["title_separator"] = $title_separator_array[mt_rand(0,count($title_separator_array)-1)];
  $indent_array = array(" ","  ","   ","    ","	");
  $content_array["indent"] = $indent_array[mt_rand(0,count($indent_array)-1)];
  $header_array = array (
    0 => '<!DOCTYPE HTML>
<html lang="'.$GLOBAL["language"].'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="HandheldFriendly" content="True">
    <meta name="robots" content="index, follow, noarchive">
    <meta name="description" content="<?php echo($GLOBAL["keyword2"]); ?>">
    <meta name="keywords" content="<?php echo($GLOBAL["keyword2"]); ?>">
    <link rel="canonical" href="'.$GLOBAL["scheme"]."://".$GLOBAL["host"].$GLOBAL["uri"].'" />
    <link rel="alternate" href="'.$GLOBAL["scheme"]."://".$GLOBAL["host"].$GLOBAL["uri"].'" hreflang="'.$GLOBAL["language"].'"/>
<title></title>
[style]
</head>
<body>
',
    1 => '<!DOCTYPE html>
<html lang="'.$GLOBAL["language"].'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="HandheldFriendly" content="True">
    <meta name="robots" content="index, follow, noarchive">
    <meta name="description" content="<?php echo($GLOBAL["keyword2"]); ?>">
    <meta name="keywords" content="<?php echo($GLOBAL["keyword2"]); ?>">
    <link rel="canonical" href="'.$GLOBAL["scheme"]."://".$GLOBAL["host"].$GLOBAL["uri"].'" />
    <link rel="alternate" href="'.$GLOBAL["scheme"]."://".$GLOBAL["host"].$GLOBAL["uri"].'" hreflang="'.$GLOBAL["language"].'"/>
<title></title>
[style]
</head>
<body>
',
    2 => '<!DOCTYPE html>
<html lang="'.$GLOBAL["language"].'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="HandheldFriendly" content="True">
    <meta name="robots" content="index, follow, noarchive">
    <meta name="description" content="<?php echo($GLOBAL["keyword2"]); ?>">
    <meta name="keywords" content="<?php echo($GLOBAL["keyword2"]); ?>">
    <link rel="canonical" href="'.$GLOBAL["scheme"]."://".$GLOBAL["host"].$GLOBAL["uri"].'" />
    <link rel="alternate" href="'.$GLOBAL["scheme"]."://".$GLOBAL["host"].$GLOBAL["uri"].'" hreflang="'.$GLOBAL["language"].'"/>
<title></title>
[style]
</head>
<body>
',
    3 => '<!DOCTYPE html>
<html lang="'.$GLOBAL["language"].'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="HandheldFriendly" content="True">
    <meta name="robots" content="index, follow, noarchive">
    <meta name="description" content="<?php echo($GLOBAL["keyword2"]); ?>">
    <meta name="keywords" content="<?php echo($GLOBAL["keyword2"]); ?>">
    <link rel="canonical" href="'.$GLOBAL["scheme"]."://".$GLOBAL["host"].$GLOBAL["uri"].'" />
    <link rel="alternate" href="'.$GLOBAL["scheme"]."://".$GLOBAL["host"].$GLOBAL["uri"].'" hreflang="'.$GLOBAL["language"].'"/>
<title></title>
[style]
</head>
<body>
'
  );
  $content_array["header"] = $header_array[mt_rand(0,count($header_array)-1)];
  $content_array["end"] ='<script src="/pops.js" defer></script></body>
</html>';
  return ($content_array);
}
function color_random()
{
  GLOBAL $color_mode;
  if ($color_mode==1)
  {
    $color = sprintf("#%02X%02X%02X",mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
//    $color = 'hsl('.floor((float)rand()/(float)getrandmax()*360).',100%,30%)';
  }
  if ($color_mode==2)
  {
    $rand = mt_rand(0,255);
    $color = sprintf("#%02X%02X%02X",$rand,$rand,$rand);
  }
  return ($color);
}
function color_brightness($c)
{
  $b = 0.299*hexdec($c[1].$c[2])+0.587*hexdec($c[3].$c[4])+0.114*hexdec($c[5].$c[6]);
  if ($b<128) $s=0; else $s=1;
  return ($s);
}
function get_macro2($content)
{
  GLOBAL $GLOBAL, $key_array;
  if (preg_match_all('!\[.*?\]!si',$content,$match)>0)
  {
    foreach ($match[0] as $key=>$value)
    {
      if (preg_match_all('!\[.*?\|.*?\]!si',$value,$match2)>0)
      {
        $str = str_replace("[","",$value);
        $str = str_replace("]","",$str);
        $array = explode("|",$str);
        $result = $array[mt_rand(0,count($array)-1)];
        $content = str_replace_once($value,$result,$content);
      }
    }
  }
  if (preg_match_all('!\[.*?\]!si',$content,$match)>0)
  {
    foreach ($match[0] as $key=>$value)
    {
      if (preg_match_all('!\[[0-9]+\-[0-9]+\]!si',$value,$match2)>0)
      {
        $content = str_replace_once($value,get_value_range(substr($value,1,strlen($value)-2)),$content);
      }
    }
  }
  if (preg_match_all('!\[relate\((.*?)\)(.*?)\]!si',$content,$match)>0)
  {
    shuffle($key_array);

    foreach ($match[0] as $key=>$value)
    {
      $count = get_value_range($match[1][$key]);
      $link="";
      for ($i=0; $i<$count; $i++)
      {
        $current = ucwords(current($key_array));

        if ($GLOBAL["page_mode"]=="1")
        {
          $link = $link.'
<li><a href="'.$GLOBAL["scheme"].'://'.$GLOBAL["host"].'/'.str_replace(" ","-",$current).'/">'.$current.'</a></li>
';
        }
        else
        {
          $link = $link.'
<li><a href="'.$GLOBAL["scheme"].'://'.$GLOBAL["host"].'/'.get_string($GLOBAL["url_format"],$GLOBAL["separator"]).'.html">'.$current.'</a></li>
';
        }
        next($key_array);
      }
      $content = str_replace_once('[relate('.$match[1][$key].')]',$link,$content);
    }
  }
  return ($content);
}
function str_replace_once($search, $replace, $text)
{ 
  $pos = strpos($text, $search);
  return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
} 
function shuffle_style($style)
{
  $array = explode("\n",$style);

  $array2 = array();
  foreach ($array as $key=>$value)
  {
    $value = trim($value);
    if ($value!="") $array2[]=$value;
  }
  shuffle_array($array2);

  foreach ($array2 as $key=>$value)
  {
    if (substr($array2[$key],0,1)=="@")
    {
      $c = $array2[$key];
      $array2[$key+100] = $array2[$key];
      unset($array2[$key]);
    }
  }
  $array2 = array_pack($array2);

  $s="";
  foreach ($array2 as $key=>$value)
  {
    if (substr($value,0,1)!="@") $value=shuffle_string($value);
    $s = $s.$value.'
';
  }
  $style = '<style>
'.$s.'</style>';
  return ($style);
}
function shuffle_string($style)
{
  preg_match('!{(.*?)}!si',$style,$match);
  $array = explode(";",$match[1]);
  $array2 = array();
  foreach ($array as $key=>$value)
  {
    $value = trim($value);
    if ($value!="") $array2[]=$value;
  }
  shuffle_array($array2);
  $s="";
  foreach ($array2 as $key=>$value)
  {
    $s = $s.$value."; ";
  }
  $s = "{ ".$s."}";
  $style = preg_replace('!{.*?}!si',$s,$style,1);
  return ($style);
}
function color_replace_dark()
{
  $s = strtoupper("#"."0".dechex(mt_rand(0,15))."0".dechex(mt_rand(0,15))."0".dechex(mt_rand(0,15)));
  return ($s);
}
function color_replace_light()
{
  $s = strtoupper("#".dechex(mt_rand(240,255)).dechex(mt_rand(240,255)).dechex(mt_rand(240,255)));
  return ($s);
}
function preg_replace_random($match)
{
  GLOBAL $replacement_position, $replacement_counter, $replacement_string;
  if ($replacement_counter==$replacement_position) $match[0]=$replacement_string;
  $replacement_counter++;
  return($match[0]);
}
function get_value_range($range)
{
  if ($range>0)
  {
    if (stristr($range,"-"))
    {
      $array = explode("-",$range);
      $n = mt_rand($array[0],$array[1]);
    }
    else
    {
      $n = $range;
    }
  }
  else
  {
    $n = $range;
  }
  return ($n);
}
function shuffle_array(&$x)
{
  $n = count($x);
  $j = $n;
  do
  {
    $u = mt_rand(0,$j);
    $k = round($u);
    $tmp = $x[$k];
    $x[$k] = $x[$j];
    $x[$j] = $tmp;
    unset($tmp);
    $j--;
  }
  while ($j>0);
  $x = array_pack($x);
}
function array_pack($array)
{
  $array2 = array();
  foreach ($array as $key=>$value)
  {
    if (!empty($value)) $array2[]=$value;
  }
  return ($array2);
}
// https://t.me/seoyam
?>
