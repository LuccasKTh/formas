<?php

require_once("../../../Class/Square.class.php");
require_once("../../../Class/Database.class.php");

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$msg = isset($_GET['msg']) ? $_GET['msg'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 0;

if ($id > 0) {
    $square = Square::show($id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $height = isset($_POST['height']) ? $_POST['height'] : 0;
    $backgroundType = isset($_POST['backgroundType']) ? $_POST['backgroundType'] = 1 : 0;
    $idMeasurement = isset($_POST['idMeasurement']) ? $_POST['idMeasurement'] : 0;
    if ($backgroundType) {
        $background = isset($_FILES['background']) ? $_FILES['background'] : 0;
        $color = 0;
    } else {
        $background = 0;
        $color = isset($_POST['color']) ? $_POST['color'] : 0;
    }

    $acao = isset($_POST['acao']) ? $_POST['acao'] : 0;

    try {
        $measure = Measure::show($idMeasurement);
        if ($acao != 'excluir') {
            $square = new Square($id, $height, $backgroundType, $background, $color, $measure);
        }
    } catch (Exception $e) {
        header('Location: index.php?msg=ERROR:' . $e->getMessage());
    }

    $resultado = "";
    switch ($acao) {
        case 'salvar':
            $msg = 'Adicionado com sucesso';
            $resultado = $square->store();
            break;

        case 'alterar':
            $msg = 'Alterado com sucesso';
            $resultado = $square->update();
            break;

        case 'excluir':
            $msg = 'Excluído com sucesso';
            $resultado = $square->destroy();
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
    $squares = Square::index($tipo, $busca);
    $measures = Measure::index(0, '');
}
