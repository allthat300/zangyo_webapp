<?php
function change_to_Apr1($someday)
{
  $check_year = intval(substr($someday,0,4));
  $check_month = substr($someday,5,2);
  if($check_month == "01" || $check_month == "02" || $check_month == "03" )
  {
    return strval($check_year - 1) . "-04-01 00:00:00";
  }else{
    return strval($check_year) . "-04-01 00:00:00";
  }
}
