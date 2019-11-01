<?php

    function viewHeader($fornecedor, $categoria, $diasUti, $ata){

    ?>

<header class="relatorio-impressao">

<div class="text-center titulo"><b>RECIBO</b></div>

<p>

    Recebemos da empresa <b><?= $fornecedor->razaosocial ?></b>, situado na <?= $fornecedor->rua ?>
    , <?= $fornecedor->numero ?>
    - <?= $fornecedor->bairro ?> Naviraí-MS, CNPJ –
    <?= $fornecedor->cnpj ?> os produtos constantes na nota fiscal nº __________________ de
    ______/______/________ ,
    distribuídos entre as unidades da rede municipal de ensino, conforme ordem de fornecimento n°.
    ___________ /2019
    de ______/______/________, <?php if ($categoria == 4) {
        echo "para ser utilizado no dia logo após a
entrega";
    } else {
        echo " para ser utilizado durante o período de ______/______ a ______/______
($diasUti)";
    } ?>.
    Tais produtos/itens são especificados de acordo com a ATA DE REGISTRO DE PREÇOS Nº <?= $ata ?>. <b>Como
    especificado
    na referida ata, o prazo de entrega dos produtos é de 05 dias úteis (após o recebimento da ordem
    de fornecimento
    devidamente assinada).</b>

</p>

</header>

<?php

    }

?>