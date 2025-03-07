<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Les avis</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/avis.css">
</head>
<body>
    <?php
  
    require_once __DIR__ . '/../classes/autoloader/autoload.php';
    use classes\controller\ControllerAvis;

    $controller_avis = new ControllerAvis();
   
    $id_current_user = $_SESSION['user_id'];
    $nom_role = $_SESSION['user_role'] ?? null;
   
    
    if (isset($_GET['id_res'])) {
        $id_res = $_GET['id_res'];
    }
    else {
        $id_res = null;
    }

    $perso = false;
    if (isset($id_res)) {
        $avis = $controller_avis->get_avis($id_res);
    }

    else {
        $avis = $_SESSION['avis_persos'] ? $_SESSION['avis_persos'] : [];
        $perso = true;
    }
   
    $nom_resto = isset($_GET['nomRes']) ? $_GET['nomRes'] : 'Inconnu';
    
    if (empty($avis)){
        echo "<h1>Aucun avis pour ce restaurant</h1>";
    }
    
    else {
        if ($perso) {
            echo "<h1>Mes avis</h1>";
        }
        else{
            echo "<h1>Les avis du restaurant $nom_resto</h1>";
        }
        
        foreach ($avis as $a) {
            $id_user_commu = $a['id_u'];
            $userName = $controller_avis->getNameUser($id_user_commu);
          
            echo "<div id='avis'>";
            echo "<strong>" . htmlspecialchars($userName) . " #" . htmlspecialchars($a['id_u']) . "</strong> - " . date("d/m/Y", strtotime($a['date_creation'])) . "<br>";
            echo "Réception : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_r']) . "<br>";
            echo "Plats : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_p']) . "<br>";
            echo "Service : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_s']) . "<br>";
            echo "<p>" . htmlspecialchars($a['commentaire']) . "</p>";
            if (($id_user_commu ===  $id_current_user) || $nom_role ===  'admin') {
               echo "<button class='btn' onclick=\"location.href='/index.php?action=remove_avis&id_c={$a['id_c']}&id_res={$a['id_res']}'\">Supprimer</button>";
            }

            echo "<hr></div>";
        }
        
        
    }
    ?>


    

    

</body>
</html>
