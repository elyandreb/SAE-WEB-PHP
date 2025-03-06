<?php
require_once __DIR__ . '/classes/autoloader/autoload.php';
use classes\model\Model_bd;
try {
    $db = Model_bd::getInstance();
    $db->init_resto_json();
} catch (Exception $e) {
    echo 'Exception reçue : ',  $e->getMessage(), "\n";
}
?>