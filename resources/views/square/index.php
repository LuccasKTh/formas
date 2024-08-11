<?php
    include_once('square.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Formas</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen">
        <div class="py-2">
            <div class="absolute p-6">
                <?php include('../components/menu.php') ?>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-6">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <?php include('../components/message.php') ?>
                        
                        <form method='post' action="square.php" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="id" value="<?= $id ? $quadrado->getId() : 0 ?>" readonly>

                            <div class="collapse <?= $quadrado ? 'collapse-open' : null ?> collapse-arrow bg-base-200 border border-primary mb-4">
                                <input type="checkbox" />
                                <div class="collapse-title text-xl font-medium">Novo quadrado</div>
                                <div class="collapse-content">
                                    <div class="grid grid-cols-11 gap-x-4">

                                        <div class="col-span-12 flex w-full flex-col">
                                            <div class="divider divider-neutral">quadrado</div>
                                        </div>

                                        <label for="height" class="col-span-2 form-control w-full max-w-xs">
                                            <div class="label">
                                                <span class="label-text">Qual é a altura?</span>
                                            </div>
                                            <input type="number" name="height" id="height" value="<?= $id ? $quadrado->getHeight() : 0 ?>" class="input input-bordered input-sm w-full max-w-xs" />
                                        </label>

                                        <label for="color" class="col-span-3 form-control w-full max-w-xs">
                                            <div class="label">
                                                <span class="label-text">Qual é a cor?</span>
                                            </div>
                                            <input 
                                                type="color" 
                                                name="color" 
                                                id="color" 
                                                value="<?= $id ? $quadrado->getColor() : '' ?>" 
                                                class="input input-bordered input-sm w-full max-w-xs"
                                            />
                                        </label>
                                        
                                        <label for="backgroundType" class="col-span-1 form-control w-full max-w-xs">
                                            <div class="label">
                                                <span class="label-text">Fundo</span>
                                            </div>
                                            <input type="checkbox" name="backgroundType" id="backgroundType" class="toggle toggle-lg" />
                                        </label>

                                        <label for="background" class="col-span-3 form-control w-full max-w-xs">
                                            <div class="label">
                                                <span class="label-text">Qual é a imagem?</span>
                                            </div>
                                            <input type="file" name="background" id="background" class="file-input file-input-bordered file-input-sm w-full max-w-xs" accept="image/*">
                                        </label>

                                        <label for="idMeasurement" class="col-span-2 form-control w-full max-w-xs">
                                            <div class="label">
                                                <span class="label-text">Qual é a medida?</span>
                                            </div>
                                            <select name="idMeasurement" id="idMeasurement" class="select select-bordered select-sm max-w-xs">
                                                <?php
                                                foreach ($measures as $measure) {
                                                    ?>
                                                        <option 
                                                            value="<?= $measure->getId() ?>"
                                                            <?= isset($quadrado) && $quadrado->getMeasure()->getId() == $measure->getId() ? 'selected' : null ?>
                                                        ><?= $measure->getMeasurement() ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </label>

                                    </div>
                                    <div class="flex gap-2 flex-row-reverse mt-4">

                                        <button value="<?= isset($quadrado) ? 'alterar' : 'salvar' ?>" name="acao" class="btn btn-outline btn-primary">
                                            <?= isset($quadrado) ? 'Alterar' : 'Salvar' ?>
                                        </button>
                                        
                                        <?php if ($id) { ?>
                                            <button value="excluir" name="acao" class="btn btn-outline btn-error">Excluir</button>
                                            <a href="index.php" class="btn btn-outline">
                                                Voltar
                                            </a>
                                        <?php } else { ?>
                                            <button type="reset" class="btn btn-outline">Apagar</button>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>

                        </form>

                        <?php if (isset($quadrado)) { ?>
                            <div class="flex w-full justify-center">
                                <?= $quadrado->draw() ?>
                            </div>
                        <?php } ?>

                        <div class="flex w-full flex-col">
                            <div class="divider">Lista de quadrados</div>
            
                            <!-- Formulário de pesquisa -->
                            <form action="" method="get">
                                <div class="flex gap-2">

                                    <label for="busca" class="input input-bordered btn-sm flex items-center w-1/4 gap-2">
                                        <input type="text" name="busca" id="busca" class="grow" placeholder="Buscar" />
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
                                            <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" />
                                        </svg>
                                    </label>

                                    <select name="tipo" id="tipo" class="select select-bordered select-sm max-w-xs">
                                        <option disabled selected>Tipo da busca</option>
                                        <option value="1">Id</option>
                                        <option value="2">Cor</option>
                                        <option value="3">Altura</option>
                                        <option value="4">Unidade de medida</option>
                                    </select>

                                    <button type='submit' class="btn btn-sm btn-active btn-ghost">Buscar</button>

                                </div>
                            </form>

                            <div class="overflow-x-auto my-5 border border-neutral rounded-2xl">
                                <?php
                                    if (empty($quadrados)) {
                                    ?>
                                        <h1 class="text-xl text-center my-5">Nenhum quadrado adicionado</h1>
                                    <?php
                                    } else {
                                    ?>
                                        <table class="table table-zebra">
                                            <tr>
                                                <th>#</th>
                                                <th>Cor</th>
                                                <th>Altura</th>
                                                <th>Unidade de Medida</th>
                                            </tr>

                                            <?php
                                                foreach ($quadrados as $quadrado) { ?>
                                                    <tr>
                                                        <td><a href='index.php?id=<?= $quadrado->getId() ?>' class="link"><?= $quadrado->getId() ?></a></td>
                                                        <td><?= $quadrado->getColor() ?></td>
                                                        <td><?= $quadrado->getHeight() ?></td>
                                                        <td><?= $quadrado->getMeasure()->getMeasurement() ?></a></td>
                                                    </tr>
                                            <?php } ?>
                                        </table>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../js/toggleBackgroundType.js"></script>

</body>
</html>