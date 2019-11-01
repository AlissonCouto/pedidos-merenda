<?php

    require_once './model/dao/DaoFornecedor.php';

    // BUSCAR A QUANTIDADE DE FORNECEDORES PRESENTES NO PEDIDO COM DISTINCT
    $daoFornecedor = new DaoFornecedor();
    $daoEscola = new DaoEscola();
    // BUSCA ARRAY COM OS ID'S DOS FORNECEDORES INCLUÍDOS NO PEDIDO
    $fornecedores = $daoFornecedor->pegarFornecedoresPedido($idPedido);
    $listaEscolas = $daoEscola->listagemRelatorio($idPedido);
    // Percorrer os fornecedores
    foreach ($fornecedores as $fornecedor) {

        // ZERANDO VARIÁVEL PARA REINICIAR CONTAGEM DOS INDICES DO VETOR DE PRODUTOS DO INICIO
        $i = 0;

        // BUSCANDO PRODUTO DE CADA FORNECEDOR
        $produto = $daoProdutos->prodThTab($idPedido, $fornecedor->id);
        // PEGANDO QUANTAS VEZES REPETIR CABEÇALHO E TABELA INICIAL
        $repeticoes = (int)count($produto);

        $volta = 1;
        unset($auxVet);

        /* VARIÁVEL AUXILIAR PARA PEGAR PRODUTOS DE CADA FORNECEDOR E BUSCAR POR QUANTIDADES EM CADA CÉLULA POSTERIORMENTE */
        $auxVet[1][] = 0;
        $limitInit = 0;
        
        for ($c = 0; $c < (ceil($repeticoes / 6)); $c++) {

            // Chamar cabeçalho do recibo
            viewHeader($fornecedor, $categoria, $diasUti, $ata);
            ?>            

            <table class="tabela tbProds">

                <thead>

                <tr scope="row">
                    <th scope="col" class="text-center tbTh">UNIDADE ESCOLAR</th>
                    <th scope="col" class="text-center tbTh">Nº ALUNOS</th>
					
					<?php
					
						foreach($produto as $p){
							
					?>
						<!-- <th scope="col" class="text-center tbTh">PRODUTO</th> -->
					<?php
						}
					
					?>
					
                </tr>

                </thead>

                <!-- ESPAÇO PARA COLUNA DE Nº ALUNOS -->
                <tr scope="row">
                    <th colspan="2">

                    </th>

                    <?php

                    // PEGANDO PRODUTOS PARA MONTAR CABEÇALHO DA TABELA
                    $i = (isset($i) && $i >= 5) ? $i : 0;
                    while ($i < ($volta * 6)) {

                        /* SE FOR MENOR QUE 12 PARA ADICIONAR APENAS COM A MESMA QUANTIDADE DE ESCOLAS */
                        if (isset($produto[$i][1])) {
                            $auxVet[$volta][$produto[$i][0]] = $produto[$i][0];
                            ?>
                            <td class="tbColuProd">
                                <table class="subTb">
                                    <tr><td><?= $produto[$i][1] ?></td></tr>
									<tr><td>Item</td></tr>
                                    <tr><td><?= $produto[$i][3] ?></td></tr>
                                    <tr><td>Marca</td></tr>
                                    <tr><td><?= $produto[$i][2] ?></td></tr>
                                    <tr><td>Unidade</td></tr>
                                    <tr><td><?= $produto[$i][4] ?></td></tr>
                                </table>
                            </td>

                            <?php

                        } else {
                            break;
                        }

                        $i++;

                    }

                    ?>

                </tr>


                <!-- INSERINDO LINHAS - ESCOLAS -->
                <?php

                $totalAlunos = 0;

                foreach ($listaEscolas as $escola) {

                    $totalAlunos += $escola->getAlunos();

                    ?>

                    <tr scope="row" class="tbRows">

                        <td class="colEscolas">
                            <?php echo "{$escola->getNome()} - End: </b> {$escola->getEndereco()->getRua()} <b> Fone: </b> {$escola->getTelefone()}" ?>
                        </td>
                        <!-- COLUNA DO NÚMERO DE ALUNOS -->
                        <td class="text-center tbColNumAlunos">

                            <?= $escola->getAlunos(); ?>

                        </td>

                        <?php

                        // $k -> id dos produtos
                        $aux = 0;
                        foreach($auxVet[$volta] as $k => $idProd){
                            if(($aux < count($auxVet[$volta])) && $k != 0){ 

                            // Buscar quantidade do produto
                            $pro = $daoProdutos->pegaQuantidade($fornecedor->id, $idPedido, $escola->getId(), $k);
                            if(isset($pro[0])){
                                $qnt = $pro[0]->quantidade;
                            }else{
                                $qnt = "-";
                            }
                        ?>

                            <td class="text-center"><?= $qnt ?></td>

                    <?php
                            }
                            $aux++;
                        }


                        ?>
                    </tr>
                    <?php

                }

                ?>

                <tr class="tbRows">

                    <td class="text-center"><b>TOTAL</b></td>
                    <td class="text-center"><b><?= $totalAlunos ?></b></td>

                    <?php

                // $k -> id dos produtos
                $aux = 0;
                foreach($auxVet[$volta] as $k => $idProd){
                    if(($aux < count($auxVet[$volta])) && $k != 0){ 

                            // Buscar quantidade do produto
                            $pro = $daoProdutos->pegaQuantidade($fornecedor->id, $idPedido, $escola->getId(), $k);
                            if(isset($pro[0])){
                                $qnt = $pro[0]->quantidade;
                            }else{
                                $qnt = "-";
                            }
                        ?>

                            <td class="text-center">
                                    <b>
                                    <?php
                                         echo $daoProdutos->contaTotalItem($categoria, $idPedido, $k);
                                    ?>
                                </b>
                            </td>

                        <?php
                    }
                    $aux++;
                }

                    ?>

                </tr>

            </table>

            <?php
            $volta++;
            // Incrementando variável para pegar o inicio da lista de quantidade de itens
            $limitInit += 6;
        }

        viewHeader($fornecedor, $categoria, $diasUti, $ata)

        ?>

        <table class="tabela tbAss">

            <thead>

            <tr scope="row">

                <th class="tbTh tbThEscolas text-center"><b>UNIDADE ESCOLAR</b></th>
                <th class="tbTh tbThDt text-center"><b>DATA</b></th>
                <th class="tbTh tbThRes text-center"><b>RESPONSÁVEL PELA RECEPÇÃO DO PRODUTO</b></th>
                <th class="tbTh bThCpf text-center"><b>CPF</b></th>
                <th class="tbTh tbThAss text-center"><b>ASSINATURA</b></th>

            </tr>

            </thead>

            <?php

            foreach ($listaEscolas as $escola) {


                ?>

                <tr scope="row" class="tbRows">
                    <td class="tbThAssEscola"><?= $escola->getNome() ?></td>
                    <td class="text-center">____/____/2019</td>
                    <td></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>

                <?php
            }
            ?>

            <tr class="tbRows">
                <td colspan="5">
                    <span>A ORDEM __________/2019 E ESTA LISTA DE DISTRIBUÇÃO FORAM GERADAS CONFORME PEDIDO DA NUTRICIONISTA ANDRIELLI CORRÊA,MATRÍCULA 8560/0.</span>
                </td>
            </tr>

        </table>
        <div class="row assinaturaNutri">
           <span class="">
           ______________________ <br>
            <span>ANDRIELLI CORRÊA</span> <br>
            <span>MATRÍCULA 8560/0</span>
           </span>
        </div>
        <?php
    }

    ?>