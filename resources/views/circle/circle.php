<?php

require_once "../../../Class/Circle.class.php";
require_once "../../../Class/Database.class.php";

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$msg = isset($_GET['msg']) ? $_GET['msg'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 0;

if ($id > 0) {
    $circle = Circle::show($id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $color = isset($_POST['color']) ? $_POST['color'] : 0;
    $image = isset($_FILES['image']) ? $_FILES['image'] : 0;
    $idMeasurement = isset($_POST['idMeasurement']) ? $_POST['idMeasurement'] : 0;
    $radius = isset($_POST['radius']) ? $_POST['radius'] : 0;

    $acao = isset($_POST['acao']) ? $_POST['acao'] : 0;

    try {
        $measure = Measure::show($idMeasurement);
        $circle = new Circle($id, $color, $image, $measure, $radius);
    } catch (Exception $e) {
        header('Location: index.php?msg=ERROR:' . $e->getMessage());
    }

    $resultado = "";
    switch ($acao) {
        case 'salvar':
            $msg = 'Adicionado com sucesso';
            $resultado = $circle->store();
            break;

        case 'alterar':
            $msg = 'Alterado com sucesso';
            $resultado = $circle->update();
            break;

        case 'excluir':
            $msg = 'Excluído com sucesso';
            $resultado = $circle->destroy();
            break;
    }

    if ($resultado) {
        header("Location: index.php?type=alert-success&msg=$msg");
    } else {
        header("Location: index.php?type=alert-error&msg=Erro ao executar ação");
    }

} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $busca = isset($_GET['busca']) ? $_GET['busca'] : 0;
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
    $circles = Circle::index($tipo, $busca);
    $measures = Measure::index(0, '');
}
