<?php // https://t.me/seoyam
function get_person()
{
  $s="";
  $s1="bcdfghjklmnpqrstvwxyz";
  $s2="aeiou";
  $count = mt_rand(1,2);
  for ($i=1; $i<=$count; $i++)
  {
    $s1 = str_shuffle($s1);
    $s2 = str_shuffle($s2);
    $n = mt_rand(2,4);
    for ($k=1; $k<=$n; $k++)
    {
      $s = $s.$s1[$k].$s2[$k];
    }
    $s = $s." ";
  }
  $result = trim(ucwords($s));
  return ($result);
}
function get_rand($value)
{
  if (stristr($value,"-"))
  {
    $array= explode("-",$value);
    $result = mt_rand($array[0],$array[1]);
  }
  else
  {
    $result = $value;
  }
  return ($result);
}
function get_image()
{
  GLOBAL $GLOBAL;
  $result = '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="80" viewBox="0 0 100% 80px">
  <style>
    div.cell { display: table; width: 100%; height: 80px; overflow: auto; font-family: Arial; font-size: 1.4em; font-weight: bold; text-align: center; line-height: 1; }
    div.cell span { width: 100%; height: 80px; display: table-cell; vertical-align: middle; }
  </style>
  <rect width="100%" height="80" fill="'.$GLOBAL["color_background"].'" />
  <foreignObject x="0" y="0" width="100%" height="80">
    <div xmlns="http://www.w3.org/1999/xhtml" class="cell"><span style="color:'.$GLOBAL["color_text"].';">'.$GLOBAL["keyword"].'</span></div>
  </foreignObject>
</svg>
';
  return ($result);
}
function get_external_link()
{
  GLOBAL $link_array2;
  $s = "<ul>\r\n";
  $count = mt_rand(2,6);
  foreach ($link_array2 as $link_value2) {
    // Экранируем текст ссылки и URL
    $link_text = htmlspecialchars($link_value2[1], ENT_QUOTES, 'UTF-8');
    $link_url = htmlspecialchars($link_value2[0], ENT_QUOTES, 'UTF-8');
    $s .= "  <li><a href=\"" . $link_url . "\">" . $link_text . "</a></li>\r\n";
  }
  $s .= "</ul>\r\n";
  return $s;
}
function get_percent($count)
{
  $array = array();
  $n = floor(100/$count);
  for ($i=0; $i<$count-1; $i++)
  {
    $array[] = mt_rand($n/2,$n);
  }
  $array[] = 100-array_sum($array);
  return ($array);
}
function get_page($keyword2, $url_format, $separator)
{
  GLOBAL $GLOBAL;
  if ($GLOBAL["page_mode"]=="1")
    $result = $GLOBAL["scheme"]."://".$GLOBAL["host"]."/".str_replace(" ","-",$keyword2)."/";
  else
    $result = $GLOBAL["scheme"]."://".$GLOBAL["host"]."/".get_string($GLOBAL["url_format"],$GLOBAL["separator"]).".html";
  return $result;
}
function get_string($url_format,$separator)
{
  $array = array();
  $count = strlen($url_format);
  for ($i=0; $i<$count; $i++)
  {
    if ($url_format[$i]=="0") $array[]=substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"),0,mt_rand(4,6));
    if ($url_format[$i]=="1") $array[]=substr(str_shuffle("102345689"),0,mt_rand(2,3));
  }
  $result = implode($separator,$array);
  return ($result);
}
function list_files($from=".")
{
  if (!is_dir($from))
    return false;
  $files = array();
  $dirs = array($from);
  while (NULL!==($dir=array_pop($dirs)))
  {
    if ($dh=opendir($dir))
    {
      while (false!==($file=readdir($dh)))
      {
        if ($file=="." || $file=="..")
          continue;
        $path = $dir."/".$file;
        if (is_dir($path))
          $dirs[]=$path;
        else
          $files[]=$path;
      }
      closedir($dh);
    }
  }
  return $files;
}
function create_dir($dir)
{
  if (!file_exists($dir))
    mkdir($dir,0777);
}
function create_file($file,$content)
{
  $fp = fopen($file,"w");
  if (flock($fp,LOCK_EX))
  {
    fwrite($fp,$content);
    flock($fp,LOCK_UN);
  }
  fclose($fp);
}
function add_file($file,$content)
{
  $fp = fopen($file,"a");
  if (flock($fp,LOCK_EX))
  {
    fwrite($fp,$content);
    flock($fp,LOCK_UN);
  }
  fclose($fp);
}
// https://t.me/seoyam
?>
