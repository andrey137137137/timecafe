<?php
use frontend\modules\constants\models\Constants;
use frontend\components\Action;
use common\components\Help;
use common\components\TagsClasses;
use yii\db\Query;



function _hyphen_words_wbr(array &$m)
{
  return _hyphen_words($m, true);
}


function _hyphen_words(array &$m, $wbr = false)
{
  if (!array_key_exists(3, $m)) return $m[0];
  $s =& $m[0];

  #буква (letter)
  $l = '(?: \xd0[\x90-\xbf\x81]|\xd1[\x80-\x8f\x91]  #А-я (все)
            | [a-zA-Z]
          )';

  #буква (letter)
  $l_en = '[a-zA-Z]';
  #буква (letter)
  $l_ru = '(?: \xd0[\x90-\xbf\x81]|\xd1[\x80-\x8f\x91]  #А-я (все)
             )';

  #гласная (vowel)
  $v = '(?: \xd0[\xb0\xb5\xb8\xbe]|\xd1[\x83\x8b\x8d\x8e\x8f\x91]  #аеиоуыэюяё (гласные)
            | \xd0[\x90\x95\x98\x9e\xa3\xab\xad\xae\xaf\x81]         #АЕИОУЫЭЮЯЁ (гласные)
            | (?i:[aeiouy])
          )';

  #согласная (consonant)
  $c = '(?: \xd0[\xb1-\xb4\xb6\xb7\xba-\xbd\xbf]|\xd1[\x80\x81\x82\x84-\x89]  #бвгджзклмнпрстфхцчшщ (согласные)
            | \xd0[\x91-\x94\x96\x97\x9a-\x9d\x9f-\xa2\xa4-\xa9]                #БВГДЖЗКЛМНПРСТФХЦЧШЩ (согласные)
            | (?i:sh|ch|qu|[bcdfghjklmnpqrstvwxz])
          )';

  #специальные
  $x = '(?:\xd0[\x99\xaa\xac\xb9]|\xd1[\x8a\x8c])';   #ЙЪЬйъь (специальные)

  /*
  #алгоритм П.Христова в модификации Дымченко и Варсанофьева
  $rules = array(
      # $1       $2
      "/($x)     ($l$l)/sx",
      "/($v)     ($v$l)/sx",
      "/($v$c)   ($c$v)/sx",
      "/($c$v)   ($c$v)/sx",
      "/($v$c)   ($c$c$v)/sx",
      "/($v$c$c) ($c$c$v)/sx"
  );

  #improved rules by Dmitry Koteroff
  $rules = array(
      # $1                      $2
      "/($x)                    ($l (?:\xcc\x81)? $l)/sx",
      "/($v (?:\xcc\x81)? $c$c) ($c$c$v)/sx",
      "/($v (?:\xcc\x81)? $c$c) ($c$v)/sx",
      "/($v (?:\xcc\x81)? $c)   ($c$c$v)/sx",
      "/($c$v (?:\xcc\x81)? )   ($c$v)/sx",
      "/($v (?:\xcc\x81)? $c)   ($c$v)/sx",
      "/($c$v (?:\xcc\x81)? )   ($v (?:\xcc\x81)? $l)/sx",
  );
  */

  #improved rules by Dmitry Koteroff and Rinat Nasibullin
  $rules = array(
    # $1                      $2
      "/($x)                    ($c (?:\xcc\x81)? $l)/sx",
      "/($v (?:\xcc\x81)? $c$c) ($c$c$v)/sx",
      "/($v (?:\xcc\x81)? $c$c) ($c$v)/sx",
      "/($v (?:\xcc\x81)? $c)   ($c$c$v)/sx",
      "/($c$v (?:\xcc\x81)? )   ($c$v)/sx",
      "/($v (?:\xcc\x81)? $c)   ($c$v)/sx",
      "/($c$v (?:\xcc\x81)? )   ($v (?:\xcc\x81)? $l)/sx",
  );

  if ($wbr) {
    $s = preg_replace($rules, "$1<wbr>$2", $s);
  } else {
    #\xc2\xad = &shy;  U+00AD SOFT HYPHEN
    $s = preg_replace($rules, "$1\xc2\xad$2", $s);
  }
  return $s;
}



$functionsList = [
//вывод одного элемента меню врутри <li> ... </li>
//ссылка на внутренний ресурс с учётом языка
   '_href' => function($href, $basePath = ''){
       return Help::href($href, $basePath);
   },
//функция or - вывод первого непустого аргумента
  '_or' => function () {
    if (!func_num_args()) {
      return null;
    }
    foreach (func_get_args() as $arg) {
      if (!empty($arg)) {
        return $arg;
      }
    }
    return null;
  },
//функция убрать <br> из контента
  '_no_br' => function ($content) {
    return str_replace('<br>', '', $content);
  },
  'json_decode' => function ($content) {
    return json_decode($content, true);
  },
  'json_encode' => json_encode,
//проверка, что значение null или 0 или цифровая часть 0 0.0
  '_is_empty' => function ($value) {
    if (empty($value)) {
      return true;
    }
    $value = floatval(preg_replace('/[^0-9\.]/', '', $value));
    if (empty($value)) {
      return true;
    }
    return false;
  },

  '_hyphen_words' => function ($s, $wbr = true) {
    #регулярное выражение для атрибутов тагов
    #корректно обрабатывает грязный и битый HTML в однобайтовой или UTF-8 кодировке!
    $re_attrs_fast_safe = '(?> (?>[\x20\r\n\t]+|\xc2\xa0)+  #пробельные символы (д.б. обязательно)
                                (?>
                                  #правильные атрибуты
                                                                 [^>"\']+
                                  | (?<=[\=\x20\r\n\t]|\xc2\xa0) "[^"]*"
                                  | (?<=[\=\x20\r\n\t]|\xc2\xa0) \'[^\']*\'
                                  #разбитые атрибуты
                                  |                              [^>]+
                                )*
                            )?';
    $regexp = '/(?: #встроенный PHP, Perl, ASP код
                    <([\?\%]) .*? \\1>  #1

                    #блоки CDATA
                  | <\!\[CDATA\[ .*? \]\]>

                    #MS Word таги типа "<![if! vml]>...<![endif]>",
                    #условное выполнение кода для IE типа "<!--[if lt IE 7]>...<![endif]-->"
                  | <\! (?>--)?
                        \[
                        (?> [^\]"\']+ | "[^"]*" | \'[^\']*\' )*
                        \]
                        (?>--)?
                    >

                    #комментарии
                  | <\!-- .*? -->
                  | {.*?}
                    #парные таги вместе с содержимым
                  | <((?i:noindex|script|style|comment|button|map|iframe|frameset|object|applet))' . $re_attrs_fast_safe . '> .*? <\/(?i:\\2)>  #2

                    #парные и непарные таги
                  | <[\/\!]?[a-zA-Z][a-zA-Z\d]*' . $re_attrs_fast_safe . '\/?>

                    #html сущности (&lt; &gt; &amp;) (+ корректно обрабатываем код типа &amp;amp;nbsp;)
                  | &(?>
                        (?> [a-zA-Z][a-zA-Z\d]+
                          | \#(?> \d{1,4}
                                | x[\da-fA-F]{2,4}
                              )
                        );
                     )+

                    #не html таги и не сущности
                  | ([^<&]{2,})  #3
                )
               /sx';

    if ($wbr) {
      $txt = preg_replace_callback($regexp, '_hyphen_words_wbr', $s);
    } else {
      $txt = preg_replace_callback($regexp, '_hyphen_words', $s);
    }

    return $txt;
  },
  '_hyphen_email' => function ($s) {
    $s = explode("@", $s);
    $s = implode('@<wbr>', $s);
    return $s;
  },
  '_nf' => function ($s, $k = 2, $minus_test = true, $separate = "&nbsp;", $wrap = false) {
    if ($minus_test && $s < 0) {
      $s = 0;
    }
    $s = (float)$s;
    $out = number_format($s, $k, '.', "&nbsp;");

    if ($separate != "&nbsp;") {
      $out = str_replace("&nbsp;", $separate, $out);
    }
    if ($wrap == 1) {
      for ($i = 0; $i < 10; $i++) {
        $out = str_replace($i, '<span>' . $i . '</span>', $out);
      }
    }
    return $out;
  },
  '_if' => function ($is, $then = false, $else = false) {
    if ($is) {
      return ($then ? $then : '');
    } else {
      return ($else ? $else : '');
    }
  },
  '_date' => function ($date, $format_time = "%H:%M:%S", $locate_month = true) use ($month) {
    if (!$date) {
      return false;
    };
    $d = explode(" ", $date)[0];
    $m = explode("-", $d);
    if ($locate_month) {
      $month = $month[0];
      $currMonth = (isset($month[$m[1]])) ? $month[$m[1]] : strftime('%B', strtotime($date));
      $sep = ' ';
    } else {
      $currMonth = $m[1];
      $sep = '/';
    }
    if ($format_time) {
      return strftime("%e " . $currMonth . " %G в " . $format_time, strtotime($date));
    } else {
      return strftime("%e " . $currMonth . " %G", strtotime($date));
    }
  },
  '_local_date' => function ($date = '', $format = "%G %B %e %H:%I:%S", $nominative = false) use ($month) {
    $month = $nominative ? $month[1] : $month[0];
    $date = $date == '' ? date('Y-m-d H:i:s', time()) : $date;
    $monthRus = strpos($format, '%BRUS');
    $date = strtotime($date);
    if ($monthRus === false) {
      return strftime($format, $date);
    }
    $m = date('m', $date);
    $currMonth = (isset($month[$m])) ? $month[$m] : strftime('%B', $date);
    return strftime(substr($format, 0, $monthRus), $date) . $currMonth . strftime(substr($format, $monthRus + 5), $date);
  },
  'date' => function ($date, $format_time = false, $locate_month = true) use ($month) {
    if ($date == 0) {
      return '';
    }
    $d = date('d', $date);
    $m = date('m', $date);

    if ($locate_month) {
      $month = $month[0];
      $currMonth = (isset($month[$m])) ? $month[$m] : date('F', $date);
      $sep = ' ';
    } else {
      $currMonth = $m;
      $sep = '/';
    }
    return $d . $sep . $currMonth . $sep . date('Y', $date) . ($format_time ? ' ' . date($format_time, $date) : '');
  },
  '_can' => function ($do) {
    return !Yii::$app->user->isGuest && Yii::$app->user->can($do);
  },
  '_ddd' => function ($params) {
    ddd($params);
  },
  't' => 'Yii::t',
  'max' => 'max',
  'implode' => 'implode',
  'sin' => 'sin',
  'str_replace' => 'str_replace',
  'in_array' => 'in_array',
  'params' => function ($name) {
    if (isset(Yii::$app->params[$name])) {
      return Yii::$app->params[$name];
    } else {
      return null;
    }
  },
  'year' => function () {
    return date('Y');
  },
  '_ucfirst' => function($value) {
        return ucfirst($value);
  },

  '_strtolower' => function($value) {
      return strtolower($value);
  },

];

return $functionsList;
