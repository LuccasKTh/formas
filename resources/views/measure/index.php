<?php
require_once "../../../Class/autoload.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $msg = isset($_GET['msg']) ? $_GET['msg'] : 0;
    $type = isset($_GET['type']) ? $_GET['type'] : 0;

    $app = Template::App();

    $table = file_get_contents('templates/table.html');
    $form = file_get_contents('templates/form.html');
    $search = file_get_contents('templates/search.html');

    $toast = file_get_contents('../components/toast.html');
    $toast = str_replace(':message', 'Sucesso', $toast);
    $app = str_replace(':toast', $toast, $app);
    
    $btnSave = file_get_contents('../components/save-button.html');
    $btnDelete = file_get_contents('../components/delete-button.html');
    $btnReturn = file_get_contents('../components/return-button.html');
    $btnClean = file_get_contents('../components/clean-button.html');
    
    if ($id > 0) {
        $quadrado = Measure::show($id);
        $form = str_replace(':id', $quadrado->getId(), $form);
        $form = str_replace(':measurement', $quadrado->getMeasurement(), $form);

        $form = str_replace(':save', $btnSave, $form);
        $form = str_replace(':delete', $btnDelete, $form);
        $form = str_replace(':return', $btnReturn, $form);
        $form = str_replace(':clean', '', $form);

        $app = str_replace(':form', $form, $app);       
        $app = str_replace(':search', '', $app);        
        $app = str_replace(':table', '', $app);     
    } else {
        $busca = isset($_GET['busca']) ? $_GET['busca'] : 0;
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
        $measures = Measure::index($tipo, $busca);

        $form = str_replace(':id', 0, $form);
        $form = str_replace(':measurement', '', $form);
        $form = str_replace(':save', $btnSave, $form);
        $form = str_replace(':delete', '', $form);
        $form = str_replace(':return', '', $form);
        $form = str_replace(':clean', $btnClean, $form);

        $list = null;
        foreach ($measures as $measure) {
            $values = file_get_contents('templates/list.html');
            $values = str_replace(':id', $measure->getId(), $values);
            $values = str_replace(':measurement', $measure->getMeasurement(), $values);
            $list .= $values;
        }
        $table = str_replace(':list', $list, $table);

        $app = str_replace(':table', $table, $app);
        $app = str_replace(':form', $form, $app);
        $app = str_replace(':search', $search, $app);
    }

    print $app;

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $color = isset($_POST['color']) ? $_POST['color'] : '';
    $height = isset($_POST['height']) ? $_POST['height'] : 0;
    $measurement = isset($_POST['measurement']) ? $_POST['measurement'] : "";

    $action = isset($_POST['action']) ? $_POST['action'] : 0;

    try {
        $measure = new Measure($id, $measurement);
        if ($action == 'save') {
            if ($id > 0) {
                $msg = 'Alterado com sucesso';
                $result = $measure->update();
            } else {
                $msg = 'Adicionado com sucesso';
                $result = $measure->store();
            }
        } else {
            $msg = 'Excluído com sucesso';
            $result = $measure->destroy();
        }
    } catch (Exception $e) {
        return header('Location: index.php?msg=ERROR:' . $e->getMessage());
    }

    if ($result) {
        return header("Location: index.php?type=alert-success&msg=$msg");
    } else {
        return header("Location: index.php?type=alert-error&msg=Erro ao executar ação");
    }
}
