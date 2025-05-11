<?php // https://t.me/seoyam
define("BOTSTAT",true);
require_once(__DIR__."/botstat.php");
$s_array = array();
// Получаем генерированный контент
if ($GLOBAL["generate"]=="1")
{
  if (file_exists(__DIR__."/".$GLOBAL["language"].".txt"))
  {
    $ucfirst = 300;
    $word_array = file(__DIR__."/".$GLOBAL["language"].".txt",FILE_IGNORE_NEW_LINES);

    require_once(__DIR__."/dic.php");

    $lipsum = new joshtronic\LoremIpsum($word_array,$ucfirst);

    $count = 100-count($parser_array);
    $s = $lipsum->sentences($count);
    $s = substr($s,0,strlen($s)-1);
    $g_array = explode(". ",$s);

    $s_array = array_merge($s_array,$g_array);
  }
}
// Формируем абзацы
$a_count = mt_rand(20,30); // Количество абзацев
$c_array = array();
$count = mt_rand(2,6); // Размер абзаца
$i=0;
$k=1;
while (list($s_key,$s_value)=each($s_array))
{
  $c_array[$i][] = $s_value;
  $k++;
  if ($k>$count)
  {
    $count = mt_rand(2,6); // Размер абзаца
    $k=1;
    $i++;
  }
  if ($i>=$a_count) break;
}
$content_array = array();
foreach ($c_array as $c_key=>$c_value)
{
  $s="";
  foreach ($c_value as $key=>$value)
  {
    $s = $s.$value;
    if ($key<count($c_value)-1)
    {
      if (mt_rand(0,100)<40) $s=$s.", "; else $s=$s.". ";
    }
  }
  $content_array[] = "<p>".$s.".</p>";
}
$content_count = count($content_array);
// Добавляем внутренние ссылки
if ($GLOBAL["internal_link"]=="1")
{
  $count = mt_rand(6,10);
  $l_array = range(0,count($content_array)-1);
  shuffle($l_array);
  $l_array = array_slice($l_array,0,$count);
  foreach ($l_array as $key=>$value)
  {
    list($link_key,$link_value)=each($link_array);
    $array = explode(". ",$content_array[$value]);
    $rand = mt_rand(1,count($array)-2);
    $array[$rand] = $array[$rand].' <a href="'.$link_value[0].'">'.$link_value[1].'</a> ';
    $content_array[$value] = implode(" ",$array);
  }
}
// Добавляем внешние ссылки
if ($GLOBAL["external_link"]=="1")
{
  $count = mt_rand(4,8);
  $l_array = range(0,count($content_array)-1);
  shuffle($l_array);
  $l_array = array_slice($l_array,0,$count);
  foreach ($l_array as $key=>$value)
  {
    list($link_key2,$link_value2)=each($link_array2);
    $array = explode(" ",$content_array[$value]);
    $rand = mt_rand(1,count($array)-2);
    $array[$rand] = $array[$rand].' <a href="'.$link_value2[0].'">'.$link_value2[1].'</a> ';
    $content_array[$value] = implode(" ",$array);
  }
}
// Добавляем ul, ol, dl
if ($GLOBAL["list_on"]=="1")
{
  $list_count = mt_rand(2,4); // Общее количество ul, ol, dl
  $list_array = array();
  for ($i=0; $i<$list_count; $i++)
  {
    while (true)
    {
      $n = mt_rand(1,$content_count-2);
      if (empty(array_intersect(range($n-1,$n+1),$list_array))) break;
    }
    $list_array[] = $n;
  }
  rsort($list_array);
  foreach ($list_array as $key=>$value)
  {
    $list_mode = mt_rand(0,100);
    if ($list_mode<=50) $list_mode="ul"; elseif ($list_mode>75) $list_mode="dl"; else $list_mode="ol";
    if ($list_mode=="ul")
    {
      $insert = "<ul>\r\n";
      $li_count = mt_rand(2,6);
      for ($i=0; $i<$li_count; $i++)
      {
        list($s_key,$s_value)=each($s_array);
        $insert = $insert."  <li>".$s_value."</li>\r\n";
      }
      $insert = $insert."</ul>";
    }
    if ($list_mode=="ol")
    {
      $insert = "<ol>\r\n";
      $li_count = mt_rand(2,6);
      for ($i=0; $i<$li_count; $i++)
      {
        list($s_key,$s_value)=each($s_array);
        $insert = $insert."  <li>".$s_value."</li>\r\n";
      }
      $insert = $insert."</ol>";
    }
    if ($list_mode=="dl")
    {
      $insert = "<dl>\r\n";
      $li_count = mt_rand(2,4);
      for ($i=0; $i<$li_count; $i++)
      {
        list($s_key,$s_value)=each($s_array);
        $insert = $insert."  <dt>".$s_value.".</dt>\r\n";
        list($s_key,$s_value)=each($s_array);
        $insert = $insert."  <dd>".$s_value.".</dd>\r\n";
      }
      $insert = $insert."</dl>";
    }
    array_splice($content_array,$value,0,$insert);
  }
  $content_count = count($content_array);
}
// Добавляем blockquote, pre
if ($GLOBAL["blockquote_on"]=="1" or $GLOBAL["pre_on"]=="1")
{
  $in_count = mt_rand(1,2); // Общее количество blockquote и pre
  $in_array = array();
  for ($i=0; $i<$in_count; $i++)
  {
    while (true)
    {
      $n = mt_rand(1,$content_count-2);
      if (empty(array_intersect(range($n-1,$n+1),$in_array))) break;
    }
    $in_array[] = $n;
  }
  rsort($in_array);
  foreach ($in_array as $key=>$value)
  {
    if (mt_rand(0,100)<=50 and $GLOBAL["pre_on"]=="1") $in_mode="pre"; else $in_mode="blockquote";
    $rand = mt_rand(2,4);
    $temp_array = array();
    for ($i=0; $i<$rand; $i++)
    {
      list($s_key,$s_value)=each($s_array);
      $temp_array[] = $s_value;
      $insert = implode(". ",$temp_array);
      if ($in_mode=="blockquote")
        $insert = "<blockquote>".$insert.".</blockquote>";
      else
        $insert = "<pre>".$insert.".</pre>";
    }
    array_splice($content_array,$value,0,$insert);
  }
  $content_count = count($content_array);
}
// Добавляем изображения
if (!empty($GLOBAL["image_on"]) && $GLOBAL["image_on"] == "1") {
    $image_count = 2; // Уменьшено для ускорения
    $image_positions = [];
    while (count($image_positions) < $image_count) {
        $n = mt_rand(1, $content_count - 2);
        if (!in_array($n, $image_positions)) {
            $image_positions[] = $n;
        }
    }
    rsort($image_positions);
    // Читаем изображения из файла images.txt
    $filepath = __DIR__ . "/images.txt";
    if (file_exists($filepath)) {
        $image_array = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        shuffle($image_array);
    } else {
        $image_array = [];
    }
    // Проверяем, что у нас достаточно изображений
    $image_count = min($image_count, count($image_array));
    for ($i = 0; $i < $image_count; $i++) {
        if (!empty($image_array)) {
            $bing_value = array_pop($image_array); // Берем с конца, чтобы уменьшить сдвиг массива
            $s_value = !empty($s_array) ? array_shift($s_array) : ""; // Если $s_array пуст, даем дефолтное значение
            $insert = "<p style='text-align: center;'><img src=\"" . htmlspecialchars($bing_value, ENT_QUOTES) . "\" alt=\"" . htmlspecialchars($s_value, ENT_QUOTES) . "\" title=\"" . htmlspecialchars($s_value, ENT_QUOTES) . "\" loading=\"lazy\" style='max-width: 100%; height: auto; display: block; margin: 0 auto;'></p>";
            array_splice($content_array, $image_positions[$i], 0, $insert);
        }
    }
    $content_count = count($content_array);
}
// Добавляем таблицы
if ($GLOBAL["table_on"]=="1")
{
  $table_count = mt_rand(0,2); // Количество таблиц
  $table_array = array();
  for ($i=0; $i<$table_count; $i++)
  {
    while (true)
    {
      $n = mt_rand(1,$content_count-2);
      if (empty(array_intersect(range($n-1,$n+1),$table_array))) break;
    }
    $table_array[] = $n;
  }
  rsort($table_array);
  foreach ($table_array as $key=>$value)
  {
    $th = mt_rand(0,100);
    $tr_count = mt_rand(2,4);
    $td_count = mt_rand(2,4);
    $tr_content="";
    $tr_content2="";
    $rand = mt_rand(0,100);
    if ($rand<=50) $table_mode=1; elseif ($rand<=75) $table_mode=2; else $table_mode=3;
    if ($table_mode==3 and $GLOBAL["image_on"]!="1") $table_mode=1;
    if (mt_rand(0,100)<=50) $table_direction=1; else $table_direction=2;
    if ($table_mode==2)
    {
      if ($table_direction==1)
      {
        $table_location=$tr_count-1;
        $percent_array = get_percent($td_count);
      }
      else
      {
        $table_location=$td_count-1;
        $percent_array = get_percent($tr_count);
      }
    }
    else
    {
      if ($table_direction==1) $table_location=mt_rand(0,$tr_count-1); else $table_location=mt_rand(0,$td_count-1);
    }

    for ($i=0; $i<$tr_count; $i++)
    {
      $td_content="";
      for ($k=0; $k<$td_count; $k++)
      {
        if ($table_mode==1)
        {
          if (mt_rand(0,100)<=50)
            list($td_key,$td_value)=each($s_array);
          else
            list($td_key,$td_value)=each($s_array);
          $td_content = $td_content."    <td>".$td_value."</td>\r\n";
        }

        if ($table_mode==2)
        {
          if ($table_direction==1 and $i==$table_location)
          {
            list($percent_key,$percent_value)=each($percent_array);
            $td_value = $percent_value."%";
          }
          elseif ($table_direction==2 and $k==$table_location)
          {
            list($percent_key,$percent_value)=each($percent_array);
            $td_value = $percent_value."%";
          }
          else
          {
            if (mt_rand(0,100)<=50)
              list($td_key,$td_value)=each($s_array);
            else
              list($td_key,$td_value)=each($s_array);
          }
          $td_content = $td_content."    <td>".$td_value."</td>\r\n";
        }

        if ($table_mode==3)
        {
          if ($table_direction==1 and $i==$table_location)
          {
            list($bing_key,$bing_value)=each($bing_array);
            list($s_key,$s_value)=each($s_array);
            $td_value = "<img src=\"".$bing_value."\" alt=\"".$s_value."\" title=\"".$s_value."\" loading=\"lazy\" style=\"100%\">";
          }
          elseif ($table_direction==2 and $k==$table_location)
          {
            list($bing_key,$bing_value)=each($bing_array);
            list($s_key,$s_value)=each($s_array);
            $td_value = "<img src=\"".$bing_value."\" alt=\"".$s_value."\" title=\"".$s_value."\" loading=\"lazy\" style=\"100%\">";
          }
          else
          {
            if (mt_rand(0,100)<=50)
              list($td_key,$td_value)=each($s_array);
            else
              list($td_key,$td_value)=each($s_array);
          }
          $td_content = $td_content."    <td>".$td_value."</td>\r\n";
        }
      }
      $tr_content = $tr_content."  <tr>\r\n".$td_content."  </tr>\r\n";
    }
    if ($th<=50)
    {
      $td_content="";
      for ($k=0; $k<$td_count; $k++)
      {
        list($td_key,$td_value)=each($s_array);
        $td_content = $td_content."    <th>".$td_value."</th>\r\n";
      }
      $tr_content2 = $tr_content2."  <tr>\r\n".$td_content."  </tr>\r\n";
    }
    $insert = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n".$tr_content2.$tr_content."</table>";
    array_splice($content_array,$value,0,$insert);
  }
  $content_count = count($content_array);
}
// Добавляем подзаголовки h2, h3
if ($GLOBAL["header_on"]=="1")
{
  $h_count = mt_rand(3,5); // Количество подзаголовков
  $h_array = array();
  if (empty($title_array)) $title_array=$key_array;
  shuffle($title_array);
  for ($i=0; $i<$h_count; $i++)
  {
    while (true)
    {
      $n = mt_rand(1,$content_count-2);
      if (empty(array_intersect(range($n-1,$n+1),$h_array))) break;
    }
    $h_array[] = $n;
  }
  rsort($h_array);
  $n = $h_count;
  $header_list="";
  foreach ($h_array as $key=>$value)
  {
    list($title_key,$title_value)=each($title_array);
    if (mt_rand(0,100)>80)
      $insert = "<a name=\"".$n."\"></a>\r\n<h3>".$title_value."</h3>";
    else
      $insert = "<a name=\"".$n."\"></a>\r\n<h2>".$title_value."</h2>";
    array_splice($content_array,$value,0,$insert);
    $header_list = "  <li><a href=\"#".$n."\">".$title_value."</a></li>\r\n".$header_list;
    $n--;
  }
  $header_list = "<ol>\r\n".$header_list."</ol>\r\n";
}
$content = implode("\r\n",$content_array);
// Добавляем изображение с кейвордом
if (mt_rand(0,100)>50)
{
  $active=mt_rand(6,10);
  $occurrence=0;
  $content = preg_replace_callback
  (
    "/<\/p>\\r\\n<p>/",
    function ($matches) use (&$occurrence,$active)
    {
      return ++$occurrence != $active ? $matches[0] : "</p>\r\n".get_image()."\r\n<p>";
    },
    $content
  );
}
// Добавляем блок внешних ссылок
if ($GLOBAL["external_link"]=="1")
{
  $active=mt_rand(5,10);
  $occurrence=0;
  $content = preg_replace_callback
  (
    "/<\/p>\\r\\n<p>/",
    function ($matches) use (&$occurrence,$active)
    {
      return ++$occurrence != $active ? $matches[0] : "</p>\r\n".get_external_link()."\r\n<p>";
    },
    $content
  );
}
$time = round(microtime(true)-$start,4);
if ($GLOBAL["template"]=="1" or $GLOBAL["template"]=="2")
{
// Инициализируем генератор случайных чисел по хосту
  mt_srand(crc32($GLOBAL["host"]));
  require_once(__DIR__."/template.php");
  $color_mode = $GLOBAL["template"];
  $template = get_template();
  eval("?>".$template);
}
if ($GLOBAL["template"]=="0")
{
  $content = file_get_contents($GLOBAL["template_dir"]."simple1.php");
  $content = str_replace("{keyword2}", $product, $content);
  $current_url = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $content = str_replace("{current_url}",$current_url,$content);
  $content = str_replace("{host}",$GLOBAL["host"],$content);
  preg_match_all('!{separator}!si',$content,$match);
  foreach ($match[0] as $key=>$value)
  {
    $content = preg_replace('!{separator}!si',html_entity_decode($separator_array[mt_rand(0,count($separator_array)-1)]),$content,1);
  }
  $content = str_replace("{color-background}",$GLOBAL["color_background"],$content);
  $content = str_replace("{color-text}",$GLOBAL["color_text"],$content);
  preg_match_all('!{keyword:(.*?)}!si',$content,$match,PREG_SET_ORDER);
  foreach ($match as $key=>$value)
  {
    $param_array = explode(":",$value[1]);
    $n = get_rand($param_array[0]);
    $url = str_replace("&amp;","&",$url);
    $c = file_get_contents($url);
    if (empty($param_array[1])) $param_array[1]=" "; else $param_array[1]=str_replace('"','',$param_array[1]);
    $c = str_replace("\n",$param_array[1],$c);
    $value[0] = preg_quote($value[0]);
    $content = preg_replace('!'.$value[0].'!si',$c,$content,1);
  } 
  preg_match_all('!{random[\-_]number:(.*?)}!si',$content,$match,PREG_SET_ORDER);
  foreach ($match as $key=>$value)
  {
    $c = get_rand($value[1]);
    $content = preg_replace('!'.$value[0].'!si',$c,$content,1);
  }
  preg_match_all('!{random:(.*?)}!si',$content,$match);
  foreach ($match[1] as $key=>$value)
  {
    $random_array = file($GLOBAL["template_dir"].$value,FILE_IGNORE_NEW_LINES);
    $content = preg_replace('!{random:(.*?)}!si',$random_array[mt_rand(0,count($random_array)-1)],$content,1);
  }
// Дополнительный макрос для текущей даты в формате 2024-02-08T13:04:30+07:00
preg_match_all('!{iso_date}!si',$content,$match,PREG_SET_ORDER);
foreach ($match as $key=>$value)
{
    $date = time();
    $c = date("Y-m-d\TH:i:sP",$date); // Формат даты и времени: "2024-02-08T13:04:30+07:00"
    $content = preg_replace('!'.$value[0].'!si',$c,$content,1);
}
// Часть 2: Замена {date:(...)} на дату и время, сдвинутые на определенный интервал
preg_match_all('!{date:(.*?)}!si',$content,$match,PREG_SET_ORDER);
foreach ($match as $key=>$value)
{
    $date = time();
    $date = $date-10000+$key*1000; // Сдвиг на определенный интервал
    $c = date("M j, Y g:i A",$date); // Измененный формат вывода даты и времени
    $content = str_replace($value[0],$c,$content);
}
// Дополнительный макрос для вывода даты в формате "11/26/23"
preg_match_all('!{date_short}!si', $content, $match, PREG_SET_ORDER);
foreach ($match as $key => $value) {
    $date = time();
    $c = date("m/d/y", $date); // Формат: 11/26/23
    $content = preg_replace('!' . $value[0] . '!si', $c, $content, 1);
}
  preg_match_all('!{person:(.*?)}!si',$content,$match,PREG_SET_ORDER);
  foreach ($match as $key=>$value)
  {
    $c = get_person();
    $content = str_replace($value[0],$c,$content);
  }
 // Функция для получения текущего главного домена
function getCurrentMainDomain() {
    $currentDomain = $_SERVER['HTTP_HOST'];
    $hostParts = explode('.', $currentDomain);
    $mainDomain = $hostParts[count($hostParts)-2] . '.' . $hostParts[count($hostParts)-1];
    return $mainDomain;
}
// Дополнительный макрос для вывода даты в формате "11/26/23"
preg_match_all('!{date_short}!si', $content, $match, PREG_SET_ORDER);
foreach ($match as $key => $value) {
    $date = time();
    $c = date("m/d/y", $date); // Формат: 11/26/23
    $content = preg_replace('!' . $value[0] . '!si', $c, $content, 1);
}

  preg_match_all('!{person:(.*?)}!si',$content,$match,PREG_SET_ORDER);
  foreach ($match as $key=>$value)
  {
    $c = get_person();
    $content = str_replace($value[0],$c,$content);
  }
preg_match_all('!{link:(.*?)}!si', $content, $match, PREG_SET_ORDER);
if (!empty($match[0])) {
    $domain_array = file("domain.txt", FILE_IGNORE_NEW_LINES);
    foreach ($match as $key => $value) {
        $array = file("domain.txt", FILE_IGNORE_NEW_LINES);
        $domain = $array[mt_rand(0, count($array) - 1)];
        $product_array = file(__DIR__ . "/../product.txt", FILE_IGNORE_NEW_LINES);
        $number_array = file(__DIR__ . "/../number.txt", FILE_IGNORE_NEW_LINES);

        $separator_array = array("", "-");
        $separator = $separator_array[mt_rand(0, count($separator_array) - 1)];

        $product = $product_array[mt_rand(0, count($product_array) - 1)];
        $product = str_replace(" ", "-", $product);

        $sub_number = $number_array[mt_rand(0, count($number_array) - 1)];

        if (stristr($product, ";")) {
            $array = explode(";", $product);
            $product = $array[0];
        }

        if (stristr($product, "{n}")) {
            $sub = str_replace("{n}", $separator . $sub_number . $separator, $product);
        } else {
            $sub = $product . $separator . $sub_number;
        }

        $sub = trim($sub, "\- \n\r\t\v\x00");

        $c = $GLOBAL["pack_scheme"] . "://" . $sub . "." . $domain;
        $content = str_replace($value[0], $c, $content);
    }
}
  eval("?>".$content);
}
// https://t.me/seoyam
?>
