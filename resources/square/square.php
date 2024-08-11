<?php
require_once("../../classes/Square.class.php");
require_once("../../classes/Database.class.php");

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$msg = isset($_GET['msg']) ? $_GET['msg'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 0;

if ($id > 0) {
    $quadrado = Quadrado::show($id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $height = isset($_POST['height']) ? $_POST['height'] : 0;
    $backgroundType = isset($_POST['backgroundType']) ? $_POST['backgroundType'] : false;
    $background = isset($_POST['background']) ? $_POST['background'] : '';
    $color = isset($_POST['color']) ? $_POST['color'] : '';
    $idMeasurement = isset($_POST['idMeasurement']) ? $_POST['idMeasurement'] : 0;

    $acao = isset($_POST['acao']) ? $_POST['acao'] : 0;

    try {
        $measure = Measure::show($idMeasurement);
        $quadrado = new Quadrado($id,  $height, $backgroundType, $background, $color, $measure);
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
    $quadrados = Quadrado::index($tipo, $busca);
    $measures = Measure::index(0, '');
}
