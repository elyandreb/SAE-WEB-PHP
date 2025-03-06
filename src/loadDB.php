<?php
require_once __DIR__ . '/classes/autoloader/autoload.php';
use classes\model\Model_bd;
try {
    $db = new Model_bd();
    $db->init_resto_json();
} catch (Exception $e) {
    echo 'Exception reçue : ',  $e->getMessage(), "\n";
}
?>