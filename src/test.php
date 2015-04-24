<?php
include "Strings.php";
$order_str =  Entere\Utils\Strings::buildOrder();
var_dump($order_str);

$random_str = Entere\Utils\Strings::buildRandom(16);
var_dump($random_str );
?>