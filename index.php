<?php
error_reporting(E_ALL);

require_once('GameQ3/Autoloader.php');

$servers1 = [
  [
    'type' => 'warsow',
    'host' => 'turn-guild.ru:44400',
  ],
  [
    'type' => 'warsow',
    'host' => 'turn-guild.ru:44401',
  ]
];

$GameQ = new \GameQ\GameQ();
$GameQ->addServers($servers1);
$results1 = $GameQ->process();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>Warfork Server Status</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <style type="text/css">
  * {
    font-size: 9pt;
    font-family: Verdana, sans-serif;
  }
  h1 {
    font-size: 12pt;
  }
  h2 {
    margin-top:2em;
    font-size: 10pt;
  }
  table {
    border: 1px solid #000;
    background-color: #DDD;
    border-spacing:1px 1px;
  }
  table thead {
    font-weight: bold;
    background-color: #CCC;
  }
  table tr.uneven td {
    background-color:#FFF;
  }
  table td {
    padding: 5px 8px;
  }
  table tbody {
    background-color: #F9F9F9;
  }
  .note {
    color: #333;
    font-style:italic;
  }
  .key-always {
    color:red;
    font-weight:bold;
  }
  .key-normalise {
    color:red;
  }
  .quake-colour-1 {
    color: red;
}
.quake-colour-2 {
    color: lime;
}
.quake-colour-3 {
    color: yellow;
}
.quake-colour-4 {
    color: blue;
}
.quake-colour-5 {
    color: cyan;
}
.quake-colour-6 {
    color: magenta;
}
.quake-colour-7 {
    color: white;
}
.quake-colour-8 {
    color: Orange;
}
.quake-colour-9 {
    color: Grey;
}
.quake-colour-0 {
    color: Black;
}
  </style>
</head>
<body>
  <?php
  function array_msort($array, $cols){
    $colarr = array();
    foreach ($cols as $col => $order) {
      $colarr[$col] = array();
      foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
      $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
      foreach ($arr as $k => $v) {
        $k = substr($k,1);
        if (!isset($ret[$k])) $ret[$k] = $array[$k];
        $ret[$k][$col] = $array[$k][$col];
      }
    }
    return $ret;
  }
  function quakeStyle($text){
    $html = preg_replace('/\^([0-9])([^\^]+)/', '<span class="quake-colour-$1">$2</span>', $text);

    return $html;
  }
foreach ($results1 as $valu) {
  echo "<div style=\"display: table-row;float:left;margin: 0 15px 15px 0;text-align:center;padding: 10px;width: 250px;\">
  <table style=\"width: 260px;\"><caption>".quakeStyle($valu['gq_hostname'])."</caption>";
  echo "<tr><td>Online</td><td>".$valu['gq_numplayers']."/".$valu['sv_maxclients']."</td></tr>";
  echo "<tr><td colspan=\"2\" style=\"text-align:center;\">".$valu['mapname']."<br><img style=\"width: 200px;\" src=\"img\warfork\\".$valu['mapname'].".png\"</td></tr>";
  echo "</table>";
  echo "<table style=\"width: 260px;\">";
  echo "<tr><td></td><td>Frags</td><td>Name</td><td>Ping</td></tr>";
  $adff = array_msort($valu['players'], array('frags'=>SORT_DESC));
  $i = 1;
  foreach ($adff as $val) {
    echo "<tr><td>".$i++."</td><td>".$val['frags']."</td><td>".quakeStyle($val['name'])."</td><td>".$val['ping']."</td></tr>";
  }
  echo "<tr><td colspan=\"4\"><a href=\"steam://connect/".$valu['gq_address'].":".$valu['gq_port_client']."\">Подключиться</a></td></tr>";
  echo "</table>";
  echo "</div>";
}
  ?>
</body>
</html>
