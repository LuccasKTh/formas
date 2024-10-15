<?php
require_once "../../../Class/autoload.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') 
{
    // $busca = isset($_GET['busca']) ? $_GET['busca'] : 0;
    // $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
    // $quadrados = Measure::index($tipo, $busca);
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $msg = isset($_GET['msg']) ? $_GET['msg'] : 0;
    $type = isset($_GET['type']) ? $_GET['type'] : 0;

    $form = file_get_contents('form.html');

    if ($id > 0) {
        $quadrado = Measure::show($id);
        $form = str_replace(':id', $quadrado->getId(), $form);
        $form = str_replace(':measurement', $quadrado->getMeasurement(), $form);
    } else {
        $form = str_replace(':id', '', $form);
        $form = str_replace(':measurement', '', $form);
    }

    print $form;

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $color = isset($_POST['color']) ? $_POST['color'] : '';
    $height = isset($_POST['height']) ? $_POST['height'] : 0;
    $measurement = isset($_POST['measurement']) ? $_POST['measurement'] : "";

    $acao = isset($_POST['acao']) ? $_POST['acao'] : 0;

    try {
        $quadrado = new Measure($id, $measurement);
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
}
