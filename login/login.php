<?php


$swift = $_POST['s'] ;

if(!empty($swift)){
    include "../static/php/login.php" ;

    $login = new Login() ;
    switch($swift){
        case 1:
            echo $login->login($_POST['u'], $_POST['p']) ;
        break ;

        case 2:
            $userId = $login->userId() ;
            if($login->userId != "no"){
                echo $login->out("rh") ;
            }
        break ;
    }
} else {
    echo $login->out("e") ;
}





?>