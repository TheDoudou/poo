<?php
header("Content-type: application/javascript charset: UTF-8");
$vars = ['test', 'test2', 'test3'];
?>

phpVars = new Array();

<?php
foreach($vars as $var) {
    echo 'phpVars.push("' . $var . '"); ';
};
?>

console.log(phpVars);