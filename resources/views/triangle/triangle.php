<?php

require_once "../../../Class/Triangle.class.php";
require_once "../../../Class/TriangleEquilateral.class.php";
require_once "../../../Class/TriangleIsosceles.class.php";
require_once "../../../Class/TriangleScalene.class.php";
require_once "../../../Class/Database.class.php";

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$msg = isset($_GET['msg']) ? $_GET['msg'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 0;

if ($id > 0) {
    $triangle = Triangle::show($id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $color = isset($_POST['color']) ? $_POST['color'] : 0;
    $image = isset($_FILES['image']) ? $_FILES['image'] : 0;
    $idMeasurement = isset($_POST['idMeasurement']) ? $_POST['idMeasurement'] : 0;
    $leftSide = isset($_POST['leftSide']) ? $_POST['leftSide'] : 0;
    $rightSide = isset($_POST['rightSide']) ? $_POST['rightSide'] : 0;
    $bottomSide = isset($_POST['bottomSide']) ? $_POST['bottomSide'] : 0;

    $acao = isset($_POST['acao']) ? $_POST['acao'] : 0;

    try {
        $measure = Measure::show($idMeasurement);
        switch (Triangle::verifyTriangleType($leftSide, $rightSide, $bottomSide)) {
            case 'Equ':
                $triangle = new TriangleEquilateral($id, $color, $image, $measure, $leftSide, $rightSide, $bottomSide);
                break;

            case 'Iso':
                $triangle = new TriangleIsoscoles($id, $color, $image, $measure, $leftSide, $rightSide, $bottomSide);
                break;

            case 'Esc':
                $triangle = new TriangleScalene($id, $color, $image, $measure, $leftSide, $rightSide, $bottomSide);
                break;
        }
    } catch (Exception $e) {
        header('Location: index.php?msg=ERROR:' . $e->getMessage());
    }

    $resultado = "";
    switch ($acao) {
        case 'salvar':
            $msg = 'Adicionado com sucesso';
            $resultado = $triangle->store();
            break;

        case 'alterar':
            $msg = 'Alterado com sucesso';
            $resultado = $triangle->update();
            break;

        case 'excluir':
            $msg = 'Excluído com sucesso';
            $resultado = $triangle->destroy();
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
    $triangles = Triangle::index($tipo, $busca);
    $measures = Measure::index(0, '');
}
