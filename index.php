<a href="../">Retour</a> - <a href="ex1.php">Exercice 1</a> - <a href="ex2.php">Exercice 2</a> - <a href="https://github.com/TheDoudou/poo">Source</a>



<?php

//$id_view = intval($_GET['id']);

try {
    $pdo = new PDO('mysql:host=192.168.0.10;dbname=analyse;charset=utf8', 'analyse', '9BxrQUuS7L63wWRm');

    $data = $pdo->query("SELECT `ip`, `count` FROM `log_poo` WHERE `ip` LIKE '".$_SERVER['REMOTE_ADDR']."'")->fetch();

    if ($data)
        $pdo->query("UPDATE `log_poo` SET `last_connect` = '".date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])."', `agent` = '".$_SERVER['HTTP_USER_AGENT']."', `count` = '".($data['count']+1)."' WHERE `ip` LIKE '".$_SERVER['REMOTE_ADDR']."'");
    else
        $pdo->query("INSERT INTO `log_poo`
                        (`id`, `ip`, `agent`, `first_connect`, `last_connect`, `count`, `id_view`) VALUES
                        (NULL, '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '".date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])."', '".date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])."', '1', '".$id_view."')");
    $pdo = null;
}

catch (Exception $e)
{
        //die('Erreur : ' . $e->getMessage());
}