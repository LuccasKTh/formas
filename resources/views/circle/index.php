<?php
    include_once 'circle.php';
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
            <div class="max-w-7xl mx-auto px-20 2xl:px-12">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <?php include('../components/message.php') ?>

                        <div class="collapse <?= $circle ? 'collapse-open' : null ?> collapse-arrow bg-base-200 border border-primary mb-4">
                            <input type="checkbox" />
                            <div class="collapse-title text-xl font-medium">Novo circulo</div>
                            <div class="collapse-content">
                                <form method='post' action="circle.php" enctype="multipart/form-data">
                                    <input type="hidden" name="id" id="id" value="<?= $id ? $circle->getId() : 0 ?>" readonly>

                                    <div class="grid grid-cols-12 gap-x-4">

                                        <div class="col-span-12 flex w-full flex-col">
                                            <div class="divider divider-neutral">circulo</div>
                                        </div>

                                        <label for="radius" class="col-span-2 form-control w-full">
                                            <div class="label">
                                                <span class="label-text">Qual o raio?</span>
                                            </div>
                                            <input type="number" name="radius" id="radius" value="<?= $id ? $circle->getRadius() : 0 ?>" class="input input-bordered input-sm w-full" />
                                        </label>

                                        <label for="color" class="col-span-4 form-control w-full">
                                            <div class="label">
                                                <span class="label-text">Qual é a cor?</span>
                                            </div>
                                            <input 
                                                type="color" 
                                                name="color" 
                                                id="color" 
                                                value="<?= $id ? $circle->getColor() : '' ?>" 
                                                class="input input-bordered input-sm w-full"
                                            />
                                        </label>
                                       
                                        <label for="image" class="col-span-4 form-control w-full">
                                            <div class="label">
                                                <span class="label-text">Qual é a imagem?</span>
                                            </div>
                                            <input type="file" name="image" id="image" class="file-input file-input-bordered file-input-sm w-full" accept="image/*">
                                        </label>

                                        <label for="idMeasurement" class="col-span-2 form-control w-full">
                                            <div class="label">
                                                <span class="label-text">Qual é a medida?</span>
                                            </div>
                                            <select name="idMeasurement" id="idMeasurement" class="select select-bordered select-sm">
                                                <?php
                                                foreach ($measures as $measure) {
                                                    ?>
                                                        <option 
                                                            value="<?= $measure->getId() ?>"
                                                            <?= isset($circle) && $circle->getMeasure()->getId() == $measure->getId() ? 'selected' : null ?>
                                                        ><?= $measure->getMeasurement() ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </label>

                                    </div>
                                    <div class="flex gap-2 flex-row-reverse mt-4">

                                        <button value="<?= isset($circle) ? 'alterar' : 'salvar' ?>" name="acao" class="btn btn-outline btn-primary">
                                            <?= isset($circle) ? 'Alterar' : 'Salvar' ?>
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

                                    <?php if (isset($circle)) { ?>
                                    <div class="col-span-12 flex w-full flex-col">
                                        <div class="divider divider-neutral">informações</div>
                                    </div>
                                    <div class="flex gap-2 mt-4 justify-around">

                                        <p>Área: <?= "{$circle->calculateArea()}{$circle->getMeasure()->getMeasurement()}²" ?></p>
                                        <p>Perímetro: <?= "{$circle->calculatePerimeter()}{$circle->getMeasure()->getMeasurement()}" ?></p>
                                        
                                    </div>
                                    <div class="flex w-full justify-center">
                                        <?= $circle->draw() ?>
                                    </div>
                                    <?php } ?>
                                </form>
                            </div>
                        </div>


                        <?php if (!isset($circle)) { ?>
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

                                    <select name="tipo" id="tipo" class="select select-bordered select-sm">
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
                                <?php if (empty($circles)) { ?>
                                <h1 class="text-xl text-center my-5">Nenhum quadrado adicionado</h1>
                                <?php } else { ?>
                                <table class="table table-zebra">
                                    <tr>
                                        <th>#</th>
                                        <th>Cor</th>
                                        <th>Raio</th>
                                        <th>Unidade de Medida</th>
                                    </tr>

                                    <?php foreach ($circles as $circle) { ?>
                                        <tr>
                                            <td><a href='index.php?id=<?= $circle->getId() ?>' class="link"><?= $circle->getId() ?></a></td>
                                            <td><?= $circle->getImage() ? $circle->getImage() : $circle->getColor() ?></td>
                                            <td><?= $circle->getRadius() ?></td>
                                            <td><?= $circle->getMeasure()->getMeasurement() ?></a></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>