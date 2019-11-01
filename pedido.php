<?php
header("Content-type: text/html; charset=utf-8");
include_once 'config.php';
    
// Inicialização de variáveis
    $listaProdutos  = [];
    $listaEscolas  = [];
    $inEscolas = "";
    $inProdutos = "";
    $msg = [];
    $dados = [];
    $numPedido = "";

    $daProdutos = new DaoProduto();
    $daoEscolas = new DaoEscola();

    $modalidade = (isset($_GET["modalidade"])) ? $_GET["modalidade"] : 1;
    $categoria = (isset($_GET["categoria"])) ? $_GET["categoria"] : 1;

    // Verificar validade de modalidade e categoria da requisição
    if( ($modalidade < 1 || $modalidade > 2 ) || ($categoria < 1 || $categoria > 4)){
        $msg[] = "Categoria/modalidade inválida!";
    }

    // Recebendo escolas e produtos selecionados na página anterior
    if($_GET){
        
        // Capturando id's das escolas e dos produtos selecionados
        $escolasId = (isset($_GET["escolas"]) ? unserialize($_GET["escolas"]) : null);
        $produtosId = (isset($_GET["produtos"]) ? unserialize($_GET["produtos"]) : null);

        if(!$escolasId || !$produtosId){
            $msg[] = "Volte a página anterior e selecione no mínimo um produto e uma escola!";
        }else{
            
                try{
                    // Se tudo foi informado
                    // Montar cláusula in (do SQL) das escolas
                     $escolasId = implode(", ", $escolasId);
                     $inEscolas = "in (" . $escolasId . ")";
                
                     // Montar cláusula in (do SQL) dos produtos
                     $produtosId = implode(", ", $produtosId);
                     $inProdutos = "in (" . $produtosId . ")";

                     $rawQuery = "SELECT * FROM tbEscola WHERE id {$inEscolas}";

                     $listaEscolas = $daoEscolas->select($rawQuery);

                     $rawQuery = "SELECT * FROM tbProduto WHERE id {$inProdutos} ORDER BY descricao ASC";
                     $listaProdutos = $daProdutos->select($rawQuery);
                }catch(PDOException $e){
                     require_once 'error.php';
                }

        }

    }

    // Se o formaulário de inserção foi submetido
    if($_POST){
        // PEGANDO DADOS DO FORMULÁRIO
        $dados = $_POST["dados"];
        $numPedido = ( isset($_POST["numPedido"]) && ($_POST["numPedido"] != "") ) ? $_POST["numPedido"] : 0;
        
        if($numPedido === 0){
            $msg[] = "Informe o número do pedido!";
        }else{
            try{

                // Início de transação
                $sql = new Sql();
                $sql->startTransaction();
    
                $pedido = new Pedido();
                $daoPedido = new DaoPedido();
                $daoItemPedido = new DaoItemPedido();
    
                //SETANDO ATRIBUTOS DO PEDIDO
                $pedido->setIdModalidade($modalidade);
                $pedido->setNumero($numPedido);

                // SALVANDO E PEGANDO ID DO ÚLTIMO
                $idPedido = $daoPedido->insert($pedido);
                $buscaTeste = $daoPedido->select("SELECT * FROM tbPedido");
                
                // Variável para armazenar campos a serem inseridos
                $values = [];

                // For para montar String da inserção em massa

                // $j ids das escolas
                // $v as quantidades de cada item no pedido
                 // $ind id dos produtos

                foreach ($dados as $j => $k) {

                    // PERCORRENDO LINHAS PARA PEGAR COLUNAS
                    // $v tem valores das colunas
                    foreach ($dados[$j] as $ind => $v) {

                        if ((strlen($v) != 0) && ($v > 0)) {
                            $item = new StdClass;
                            $item->idProduto = $ind;
                            $item->idEscola = $j;
                            $item->quantidade = $v;
                            $item->idPedido = $idPedido;

                            $values[] = "($ind, $j, $v, $idPedido)";

                        }
                    }
                }

                if(!$values){
                    $sql->rollback();
                    $msg[] = "Informe a quantidade de ao menos um produto!";
                }else{

                    $values = " VALUES " . implode(", ", $values);

                    $query = "INSERT INTO tbItemPedido (idProduto, idEscola, quantidade, idPedido)" . $values;

                    $retorno = $sql->query($query);
                    $sql->commit();

                    $daoProdutos = new DaoProduto(); 
                    include_once 'relatorio.php';
                    exit;
                    header("location: relatorio.php?ipd=$idPedido&categoria=$categoria&modalidade=$modalidade");
                }

            }catch(PDOException $e){
                $sql->rollback();
                /*echo "<b>Código: </b>" . $e->getCode();
                echo "<br> <b>Mensagem: </b>" . $e->getMessage();
                echo "<br> <b>Arquivo: </b>" . $e->getFile();
                echo "<br> <b>Linha: </b>" . $e->getLine();
                echo "<br> ============ <br> <br>";
                $e->getTrace();*/
                require_once 'error.php';
            }
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
        <link rel="stylesheet" href="estilo/pedido.css">

    </head>

<body>

    <div class="container-fluid">

    <?php

                if($msg){
                    foreach($msg as $v){

                        ?>
                        <span class="alert alert-danger"><?= $v ?></span>
                        <?php
                    }
                }
                ?>

    <form action="" target="_blank" method="post" name="ok">
            <header class="row d-flex justify-content-center align-items-center">

                <div class="col-12">
                    <label for="numeroPedido">Informe o número do pedido: </label>&nbsp;<input id="numeroPedido" type="number" min="1" name="numPedido" value="<?= $numPedido ?>">
                </div>
            </header>

            <section class="">

                <article class="">

                    <br>

                    <table id="tbl" class="table">

                        <thead class="thead-dark">

                        <tr class="cmb-prod">

                            <th scope="col">UNIDADE ESCOLAR</th>
                            <th scope="col">Nº ALUNOS</th>

                            <?php

                            if ($listaProdutos) {

                                foreach ($listaProdutos as $v) {

                             ?>

                                    <th><?= $v->getDescricao() ?></th>

                                    <?php

                                }

                            }

                            ?>

                        </tr>

                        </thead>

                        <?php

                        $totalAlunos = 0;
                        foreach($listaEscolas as $escola){

                         $totalAlunos += $escola->getAlunos();

                        ?>


                        <tr>

                            <td><b><?= $escola->getNome() ?></td>
                            <td><?= $escola->getAlunos() ?></td>
                            <?php

                            if($listaProdutos){

                                foreach($listaProdutos as $produto){

                            ?>
                            <td><input type="number" name="dados[<?= $escola->getId() ?>][<?= $produto->getId() ?>]" value="<?= (isset($dados[$escola->getId()][$produto->getId()])) ? $dados[$escola->getId()][$produto->getId()] : "" ?>"></td>

                            <?php
                                }

                            }

                            ?>
                        </tr>

                        <?php

                        }

                        ?>

                    </table>

                    <input type="hidden" name="modalidade" value="<?= $modalidade ?>">
                    <input type="hidden" name="categoria" value="<?= $categoria ?>">
                    <button class="btn-ok">SALVAR</button>

                </article>

            </section>

        </form>

    </div> <!-- CONTAINER PRINCIPAL -->

    <script src="./lib/jquery/jquery191.js"></script>
    <script src="./js/eventos.js"></script>

</body>

</html>