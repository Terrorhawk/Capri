<?php 

function CMD_ALL_USER_SHOW($tabla){
  $tabla = str_replace(' / unlimited', '', $tabla);
  $tabla = preg_replace("/<tr\s*><td align=right colspan=(.+)><a class=toptext href=\'\?view=advanced\'>(.+)<\/a><\/td ><\/tr >/", '', $tabla);
  $tabla = preg_replace("/<tr\s*><td align=right colspan=(.+)><a class=toptext href=\'\?\'>Clear Search Filter<\/a><\/td ><\/tr >/", '', $tabla);
  $tabla = preg_replace("/<(script|SCRIPT)[\s\S]*?>[\s|\S]*?<\/(script|SCRIPT)>/", "", $tabla);
  //$tabla = preg_replace("/(.*)<a(.+)href=\"javascript:selectAll\(\'select\'\);\">Select<\/a>(.*)/", "$1<input type=\"checkbox\" class=\"checkAll\">$3", $tabla);
  $tabla = str_replace("<a class=listtitle href=\"javascript:selectAll('select');\">Select</a>", "<input type=\"checkbox\" class=\"checkAll\">", $tabla);
  return $tabla;
}

function CMD_RESELLER_SHOW($tabla){
  $tabla = str_replace(' / unlimited', '', $tabla);
  $tabla = preg_replace("/<tr\s*><td align=right colspan=(.+)><a class=toptext href=\'\?view=advanced\'>(.+)<\/a><\/td ><\/tr >/", '', $tabla);
  $tabla = preg_replace("/<tr\s*><td align=right colspan=(.+)><a class=toptext href=\'\?\'>Clear Search Filter<\/a><\/td ><\/tr >/", '', $tabla);
  $tabla = preg_replace("/<(script|SCRIPT)[\s\S]*?>[\s|\S]*?<\/(script|SCRIPT)>/", "", $tabla);
  //$tabla = preg_replace("/(.*)<a(.+)href=\"javascript:selectAll\(\'select\'\);\">Select<\/a>(.*)/", "$1<input type=\"checkbox\" class=\"checkAll\">$3", $tabla);
  $tabla = str_replace("<a class=listtitle href=\"javascript:selectAll('select');\">Select</a>", "<input type=\"checkbox\" class=\"checkAll\">", $tabla);
  return $tabla;
}


?>