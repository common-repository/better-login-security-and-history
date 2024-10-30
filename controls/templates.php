<?php

$template['link']['content'] = " we can see {param1} here {param2} my {db_OS}";

$template['edit']['content'] = "<a href='{param}'><img src='../cframework/style/icons/edit.gif' /></a>";
$template['edit']['options']['width']= '20px';
$template['edit']['options']['align']= 'center';


$template['del']['content'] = "<a href='#' onclick=\"if(confirm('هل تريد بالتأكيد اجراء عملية الحذف ؟'))window.location='{param}';\"><img src='../cframework/style/icons/del.gif' /></a>";
$template['del']['options']['width']= '20px';
$template['del']['options']['align']= 'center';


$template['view']['content'] = "<a href='{param}'><img src='../cframework/style/icons/view.gif' /></a>";
$template['view']['options']['width']= '20px';
$template['view']['options']['align']= 'center';


$template['order']['content'] = "<a href='{param}&do=last'><img src='../cframework/style/icons/ord_last.gif' /></a><a href='{param}&do=up'><img src='../cframework/style/icons/ord_up.gif' /></a><a href='{param}&do=down'><img src='../cframework/style/icons/ord_down.gif' /></a><a href='{param}&do=first'><img src='../cframework/style/icons/ord_first.gif' /></a>";
$template['order']['options']['width']= '65px';
$template['order']['options']['align']= 'center';


$template['status']['content'] = "<img src='../adminx0097/images/{param}.gif' />";
$template['status']['options']['width']= '20px';
$template['status']['options']['align']= 'center';




?>