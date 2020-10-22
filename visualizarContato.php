<?php

    $chkFeminino = (string) "";
    $chkMasculino = (string) "";
    $idEstado = (int) 0;
    $action = "bd/inserirContato.php";
    //variavel que será utilizada no atributo action do form 


    //Import do arquivo de Variaveis e Constantes
    require_once('modulo/config.php');

    //Import do arquivo de função para conectar no BD
    require_once('bd/conexaoMysql.php');

    //chama a função que vai estabelecer a conexão com o BD
    if(!$conex = conexaoMysql())
    {
        echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
        //die; //Finaliza a interpretação da página
    }

    $id = $_POST['idContato'];

    $sql = "select tblcontatos.* , tblestados.sigla 
                        from tblcontatos, tblestados
                        where tblestados.idEstado = tblcontatos.idEstado
                        and tblcontatos.idContato =".$id;

    $select = mysqli_query($conex , $sql);

    if ($rscontato = mysqli_fetch_assoc($select)) {
        $nome = $rscontato['nome'];
        $celular = $rscontato['celular'];
        $email = $rscontato['email'];
        $estado = $rscontato['sigla'];
        $dataNascimento = explode("-" , $rscontato['dataNascimento']);
        $dataNasc = $dataNascimento[2] . "/" . $dataNascimento[1] . "/" . $dataNascimento[0];
        $sexo = $rscontato['sexo'];
        if (strtoupper($sexo)=='F') {
            $sexo = 'Feminino';
        }else {
            $sexo = 'Masculino';
        }
        $obs = $rscontato['obs'];
    }
?>

<html>
    <head>
        <title>Visualizar Contato</title>
        <link rel="stylesheet" href="style/style.css">
    </head>
    <script>
                $(document).ready(function(){
                    $("#modalExit").click(function(){
                        $("#modalContainer").slideUp(200);
                    });
                });
    </script>
    <body>
        
        <table id="VisualizarContato">
            <tr>
                <td colspan="2">
                    <span id='modalExit'> fechar </span>
                </td>
            </tr>
            <tr>
                <td>
                    Nome:
                </td>
                
                <td>
                    <?= $nome?>
                </td>
            </tr>

            <tr>
                <td>
                    Celular:
                </td>
                
                <td>
                <?= $celular?>
                </td>
            </tr>

            <tr>
                <td>
                    Email:
                </td>
                
                <td>
                <?= $email?>
                </td>
            </tr>

            <tr>
                <td>
                    Estado:
                </td>
                
                <td>
                <?= $estado?>
                </td>
            </tr>

            <tr>
                <td>
                    Data de Nascimento:
                </td>
                
                <td>
                <?= $dataNasc?>
                </td>
            </tr>

            <tr>
                <td>
                    Sexo:
                </td>
                
                <td>
                <?= $sexo?>
                </td>
            </tr>

            <tr>
                <td>
                    Observações: 
                </td>
                
                <td>
                <?= $obs?>
                </td>
            </tr>

        </table>
    </body>
</html>