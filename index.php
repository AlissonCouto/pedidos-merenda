<?php
    
    header("Content-type: text/html; charset=utf-8");
    include_once 'config.php';
    // Inicialização de variáveis
    $prodSelect = [];
    $escSelect = [];
    $modalidade = 1;
    $categoria = 1;
    $msg = [];

    // Buscando categorias e modalidades para carregar combobox
    $daoModalidade = new DaoModalidade();
    $daoCategoria = new DaoCategoria();

    
    try{
        $listaCategorias = $daoCategoria->getAll();
        $listaModalidade = $daoModalidade->getAll();
    }catch(PDOException $e){
        require_once 'error.php';
    }
    
    $daoEscola = new DaoEscola();
    $daoProduto = new DaoProduto();

    // Se houve submissão do formulário de modalidade/categoria
    if($_GET){
        
        // Capturando categoria e modalidade da requisição
        $modalidade = (isset($_GET["modalidade"])) ? $_GET["modalidade"] : 1;
        $categoria = (isset($_GET["categoria"])) ? $_GET["categoria"] : 1;

        // Verificar validade de modalidade e categoria da requisição
        if( ($modalidade < 1 || $modalidade > 2 ) || ($categoria < 1 || $categoria > 4)){
            $msg[] = "Categoria/modalidade inválida!";
        }

    }

    // Buscar escolas e produtos por modalidade e categoria
    try{

        $listaEscolas = $daoEscola->select(
            "SELECT * FROM tbEscola WHERE modalidade = :modalidade",
            [":modalidade" => $modalidade]
        );

        $listaProdutos = $daoProduto->select(
            "SELECT * FROM tbProduto
                WHERE idCategoria = :idcategoria ORDER BY descricao ASC", 
            [":idcategoria" => $categoria]
        );

    }catch(PDOException $e){
        require_once 'error.php';
    }

    // Tratando escolas e produtos enviadas via POST
    if($_POST){
        
        $prodSelect = (isset($_POST["prod"])) ? $_POST["prod"] : [];
        $escSelect =  (isset($_POST["esc"])) ? $_POST["esc"] : [];
        
        if(!$prodSelect || !$escSelect){
            $msg[] = "Selecione no mínimo um produto e uma escola!";
        }else{
            $prodSelect = serialize($prodSelect);
            $escSelect = serialize($escSelect);
            header("location: pedido.php?modalidade=$modalidade&categoria=$categoria&escolas=$escSelect&produtos=$prodSelect");
        }

    }

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf8">
    <meta name="author" content="Alisson Couto">
    <title>Pedidos de merenda</title>

    <!-- IMPORTAÇÕES DE ESTILO -->
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilo/index.css">

</head>
<body id="body">
<div id="main" class="container-fluid">

                <?php

                if($msg){
                    foreach($msg as $v){

                        ?>
                        <span class="alert alert-danger"><?= $v ?></span>
                        <?php
                    }
                }
                ?>

    <form action="" method="get" id="form1" name="proximo">

        <header class="row d-flex justify-content-center align-items-center">

            <div class="col-3">

                <div class="row">
                    <span class="selects">SELECIONE A CATEGORIA:</span>
                </div>

                <div class="row col3">
                    <select id="sl-cat" name="categoria">
                        <?php

                        if ($listaCategorias) {

                            foreach ($listaCategorias as $v) {

                                ?>

                                <option value="<?= $v->id ?>" <?php if($v->id === $categoria){ ?> selected <?php } ?> ><?= $v->categoria ?></option>

                                <?php

                            }

                        }

                        ?>
                    </select>
                </div>

            </div>

            <div class="col-3">

                <div class="row">
                    <span class="selects">SELECIONE A MODALIDADE:</span>
                </div>

                <div class="row">
                    <select id="sl-mol" name="modalidade">

                        <?php


                        foreach ($listaModalidade as $v) {

                        ?>

                            <option value="<?= $v->id ?>" <?php if($v->id === $modalidade){ ?> selected <?php } ?> ><?= $v->modalidade ?></option>

                        <?php
                        }
                        ?>

                    </select>
                </div>

            </div>
            <div class="col-3">

                <div class="row">
                    <button class="btn-buscar" href="relatorio.php">PRÓXIMO</button>
                </div>

            </div>

        </header>
    </form>

    <!-- FORMULÁRIO PARA SELEÇÃO DE PRODUTOS QUE SERÃO SOLICITADOS -->
    <form method="post" action="">

        <section class="row container-selects">
            <article class="col-prod">
                <h5>SELECIONE OS PRODUTOS</h5>
                <table class="table">

                    <thead>
                    <tr>
                        <th>
                            <b>PRODUTO</b>
                        </th>

                        <th>
                            <input id="selectAllProd" onClick="toggle(this)" type="checkbox"><label class="lblSelectAll" for="selectAllProd">TODOS</label>
                        </th>

                    </tr>
                    </thead>

                    <!-- LISTAGEM DOS PRODUTOS PARA SELEÇÃO -->
                    <tbody>
                    <?php
                    // SE LISTA DE PRODUTOS FOI DEFINIDA
                    if(isset($listaProdutos)){
                        foreach($listaProdutos as $produto){
                            ?>
                            <tr>
                                <td><?= $produto->getDescricao() ?></td>
                                <td>
                                    <input class="chkProd" type="checkbox" name="prod[]"
                                           value="<?= $produto->getId() ?>" <?php if(in_array($produto->getId(), $prodSelect)){ ?> checked <?php } ?>>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>

                </table>

            </article>

            <article class="col-for">
                <h5>SELECIONE AS ESCOLAS</h5>
                <table class="table">

                    <thead>
                    <tr>
                        <th>
                            <b>ESCOLA</b>
                        </th>

                        <th>
                            <input id="selectAllEsc" onClick="toggle(this)" type="checkbox"><label class="lblSelectAll" for="selectAllEsc">TODAS</label>
                        </th>
                    </tr>
                    </thead>

                    <!-- LISTAGEM DOS PRODUTOS PARA SELEÇÃO -->
                    <tbody>
                    <!-- INSERINDO LINHAS - ESCOLAS -->
                    <?php
                       // SE LISTA DE ESCOLAS FOI DEFINIDA
                    if(isset($listaEscolas)){
                        foreach ($listaEscolas as $escola) {
                            ?>

                            <tr scope="row" class="tbRows">

                                <td class="colEscolas">
                                    <?php echo "{$escola->getNome()}" ?>
                                </td>

                                <td>
                                    <input class="chkEsc" type="checkbox" name="esc[]"
                                           value="<?= $escola->getId() ?>" <?php if(in_array($escola->getId(), $escSelect)){ ?> checked <?php } ?>>
                                </td>

                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>

                </table>
            </article>

        </section>

          <button class="btn-buscar">BUSCAR</button>
        <input type="hidden" name="modalidade" value="<?= $modalidade ?>">
        <input type="hidden" name="categoria" value="<?= $categoria ?>">
    </form>

</div>

    <script src="./lib/jquery/jquery191.js"></script>
    <script src="./js/eventos.js"></script>
</body>
</html>				