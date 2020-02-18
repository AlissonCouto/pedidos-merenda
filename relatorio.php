<?php

require_once 'viewHeader.php';

$diasUti = "";

switch ($categoria) {

    case 1:
        $diasUti = "05 dias letivos";
        $ata = "038/2019";
        break;
    
    case 2:
        $diasUti = "5 dias letivos";
        $ata = "047/2019";
        break;
    
    case 3:
        $diasUti = "10 dias letivos";
        $ata = "083/2019";
        break;
    
    default:
        $ata = "050/2019";
    
    }

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf8">
    <meta name="author" content="Alisson Couto">
    <title>Relatório do pedido</title>

    <!-- IMPORTAÇÕES DE ESTILO -->
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilo/relatorio.css">

</head>

<body id="body">

<div class="container-principal" id="main">
    <button onclick="window.print();">IMPRIMIR</button>
    <?php
    
        require_once 'func_relatorio.php';

    ?>

</div> <!-- CONTAINER PRINCIPAL -->

<script src="lib/jquery/jquery191.js"></script>
<script src="./js/eventos.js"></script>

<script>
           var prop = null

           window.addEventListener("beforeprint", function (event){
                /* DIV CRIADA PELOS DESENVOLVEDORES DA HOSPEDAGEM */
                let prop = document.querySelector(".prop-width")
                prop.parentNode.removeChild(prop)

           })

           window.addEventListener("afterprint", function(event){
               let bd = document.getElementById('body')
               let filho = bd.appendChild(prop)
           })
</script>
</body>

</html>		