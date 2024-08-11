<?php

use function PHPSTORM_META\type;

require_once("../../../Class/Square.class.php");
require_once("../../../Class/Database.class.php");

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$msg = isset($_GET['msg']) ? $_GET['msg'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 0;

if ($id > 0) {
    $quadrado = Square::show($id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $height = isset($_POST['height']) ? $_POST['height'] : 0;
    $backgroundType = isset($_POST['backgroundType']) ? $_POST['backgroundType'] = 1 : 0;
    $idMeasurement = isset($_POST['idMeasurement']) ? $_POST['idMeasurement'] : 0;
    if ($backgroundType) {
        $background = isset($_FILES['background']) ? $_FILES['background'] : '';
        $color = 0;
    } else {
        $color = isset($_POST['color']) ? $_POST['color'] : '';
        $background = 0;
    }

    $acao = isset($_POST['acao']) ? $_POST['acao'] : 0;

    try {
        $measure = Measure::show($idMeasurement);
        $quadrado = new Square($id, $height, $backgroundType, $background, $color, $measure);
    } catch (Exception $e) {
        header('Location: index.php?msg=ERROR:' . $e->getMessage());
    }

    $resultado = "";
    switch ($acao) {
        case 'salvar':
            $msg = 'Adicionado com sucesso';
            $resultado = $quadrado->store();
            break;

        case 'alterar':
            $msg = 'Alterado com sucesso';
            $resultado = $quadrado->update();
            break;

        case 'excluir':
            $msg = 'Excluído com sucesso';
            $resultado = $quadrado->destroy();
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
    $quadrados = Square::index($tipo, $busca);
    $measures = Measure::index(0, '');
}
