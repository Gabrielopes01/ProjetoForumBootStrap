<?php

Use \Classes\Favorite;
Use \Classes\Page;
Use \Classes\User;
require_once("function.php");

$app->get("/:pag/myFavorites", function($pag){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $favoritos = Favorite::getALLFavorites();

    $resultado = [];
    $numPags = (int) ceil(count($favoritos)/16);
    $paginas = generatePages($numPags);

    for ($i = 16 * $pag; $i < 16 * ($pag + 1); $i++) {
        if($i == count($favoritos) || $i > count($favoritos)){
            break;
        } else {
            array_push($resultado, $favoritos[$i]);
        }
    }

    $page->setTpl("favorites", [
        "favoritos"=>$resultado,
        "pagina"=>$pag,
        "paginas"=>$paginas,
        "numPags"=>$numPags
    ]);

});

$app->post("/0/favorite/:id", function($id){

    $ver = Favorite::verifyFavorite($id);

    if($ver == 1){
        Favorite::removeFavorite($id);
        echo "<a class='btn btn-warning favButton' onclick='favButtonC(". $id .")' style='color: white;'><i class='fas fa-star'></i></a>";
    } else {
        Favorite::addFavorites($id);
        echo "<a class='btn btn-warning favButton' onclick='favButtonC(". $id .")' style='color: black;'><i class='fas fa-star'></i></a>";
    }

});