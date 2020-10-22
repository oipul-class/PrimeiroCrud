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
//####################################CÓDIGO PARA CARREGAR OS DADOS NAS CAIXAS (EDITAR)
//validar para ver se existe a variavel modo
if (isset($_GET["modo"])) {
    //Validação para verificar se modo é para buscar no banco de registro
    if (strtoupper($_GET["modo"])=="CONSULTAR") {
        if (isset($_GET["id"]) && $_GET["id"] != "") {
            

            $id = $_GET["id"];
            //ativa o recurso para utilização de variaveis de sessão
            //uma variavel de sessão permanece ativa até que o programa destrua ela ou o navegador seja fechado
            session_start();

            //criamos uma variavel chamada id como sando uma variavel de sessão
            $_SESSION["id"] = $id;
            $sql = "select tblcontatos.* , tblestados.sigla 
                    from tblcontatos, tblestados
                    where tblestados.idEstado = tblcontatos.idEstado
                    and tblcontatos.idContato =".$id;

            $select = mysqli_query($conex, $sql);
            if ( $rsContatos = mysqli_fetch_assoc($select) ) {
                $nome = $rsContatos['nome'];
                $celular = $rsContatos['celular'];
                $email = $rsContatos['email'];
                $sexo = $rsContatos['sexo'];
                if (strtoupper($sexo) == "F") {
                    $chkFeminino = "checked";
                }
                elseif (strtoupper($sexo) == "M") {
                    $chkMasculino = "checked";
                }
                $dataNascimento = explode( "-" , $rsContatos['dataNascimento']);
                $dataNasc = $dataNascimento[2] . "/" . $dataNascimento[1] . "/" . $dataNascimento[0];
                $obs = $rsContatos['obs'];
                $idEstado = $rsContatos['idEstado'];
                $sigla = $rsContatos['sigla'];
                
                $action = "bd\atualizarContato.php";
            }
        }
    }
}
//####################################EDITAR####################################


?>

<!DOCTYPE>
<html lang="pt-br">
    <head>
        
        <meta charset="UTF-8">
        <title> Cadastro </title>
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <script src="js/jquery.js"></script>
        <script>
            $(document).ready(function(){
                    //function para carregar a modal
                    $(".pesquisar").click(function(){
                        $("#modalContainer").slideDown(500);
                    });

                    $("#modalExit").click(function(){
                        $("#modalContainer").slideUp(500);
                    });
                
            });

            
            //function para carregar o visualizar Contato na modal
            const visualizar = (id) => {
                $.ajax({ //enviar dado
                    type: "POST",
                    url: "visualizarContato.php",
                    data: {idContato:id},
                    success: function(dados) {
                        $("#modal").html(dados);
                    }

                });
            };
            
        </script>
    </head>
    <body>
        <div id="modalContainer">
            <!-- <img src="./img/trash.png" id="modalExit"> -->
            <div id="modal">
                
            </div>
        </div>
        <!-- 
            Elementos para formulário do HTML 5
                <input type="tel" - indica que o dado a ser digitado será um telefone
                <input type="email" - indica que o dado a ser digitado será email
                <input type="url" - indica que o dado a ser digitado será o endereço de um site
                <input type="number" - indica que o dado será selecionado pelo usuário
                <input type="range" - criar uma régua de numeros a serem selecionados
                <input type="color" - permite que o usuário selecione uma cor e ele retorna o hexadecimal da cor escolhida

                Obs: cuidado, pois esses 3 elementos não funcionam em todos os navegadores
                    <input type="date" - permite que o usuário utilize um calendário na escolha da data
                    <input type="month" - permite que o usuário utilize um calendário na escolha apenas do mês
                    <input type="week" - permite que o usuário utilize um calendário na escolha apenas da semana

                placeholder - permite colocar uma mensagem de dica dentro da caixa
                required - deixa a caixa sendo obrigatória na digitação
                pattern - 

        -->
        <div id="cadastro"> 
            <div id="cadastroTitulo"> 
                <h1> Cadastro de Contatos </h1>
            </div>
            <div id="cadastroInformacoes">
        
                <form action="<?= $action ?>" name="frmCadastro" method="post" enctype="multipart/form-data"> <!-- permitir que o formulario possa tirar dados tradicionais e arquivos 
                !!! é obrigatório o form ser do metodo POST
                -->
                   
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <p> Nome: </p>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="text" name="txtNome" value="<?=@$nome?>" placeholder="Digite seu Nome" required pattern="[a-z A-Z é]*">
                        </div>
                    </div>

                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <p> arquivo: </p>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="file" name="fleFoto" accept=".jpg , .png , .jpeg"> <!-- colocar arquivos no site-->
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <p> Celular: </p>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="tel" name="txtCelular" value="<?=@$celular?>" pattern="[(][0-9]{2}[)][0-9]{5}-[0-9]{4}" placeholder="(99)99999-9999">
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <p> Email: </p>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="email" name="txtEmail" value="<?=@$email?>">
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <p> Estados: </p>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <select name="sltEstados">
                                <?php 
                                    if ( isset($_GET["modo"]) && strtoupper($_GET["modo"])=="CONSULTAR") {
                                ?>
                                
                                <option value="<?=$idEstado?>" ><?=$sigla?></option>

                                <?php 
                                    }
                                    else {
                                ?>
                                
                                <option value="" hidden>Selecione um Item</option>
                        
                                <?php

                                    }
                                    $sql = "select * from tblestados
                                            WHERE idEstado <>" . $idEstado;
                                
                                    $select = mysqli_query($conex, $sql);
                                    
                                    /*
                                        RS - RecordSet
                                        RS - Resultado
                                    
                                    */
                                
                                    while($rsEstados = mysqli_fetch_assoc($select))
                                    {
                                ?>
                                
                                    <option value="<?=$rsEstados['idEstado']?>"> <?=$rsEstados['sigla'];?> </option>
                                
                                <?php 
                                    }
                                
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <p> Data de Nascimento: </p>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="text" name="txtNascimento" value="<?=@$dataNasc?>" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                           Sexo:
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="radio" name="rdoSexo" value="F" <?=@$chkFeminino?> >Feminino.
                            <input type="radio" name="rdoSexo" value="M" <?=@$chkMasculino?> >Masculino.
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <p> Observações: </p>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <textarea name="txtObs" cols="50" rows="7"><?=@$obs?></textarea>
                        </div>
                    </div>
                    <div class="enviar">
                        <div class="enviar">
                            <input type="submit" name="btnEnviar" value="Salvar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="consultaDeDados">
            <table id="tblConsulta" >
                <tr>
                    <td id="tblTitulo" colspan="5">
                        <h1> Consulta de Dados.</h1>
                    </td>
                </tr>
                <tr id="tblLinhas">
                    <td class="tblColunas"> Nome </td>
                    <td class="tblColunas"> Celular </td>
                    <td class="tblColunas"> Estado </td>
                    <td class="tblColunas"> Email </td>
                    <td class="tblColunas"> Opções </td>
                </tr>
                
                <?php
                    //Script para buscar todos os dados no BD
                    $sql = " select tblcontatos.idContato, tblcontatos.nome, tblcontatos.celular,                       tblcontatos.email, tblestados.sigla 
                             from tblcontatos, tblestados
                             where tblcontatos.idEstado = tblestados.idEstado 
                             order by tblcontatos.idContato desc
                            ";
                    
                    //Executa o script no BD (Retorna o conteudo existente
                    // no banco de dados e armazena na variavel $select)
                    $select = mysqli_query($conex, $sql);
                    
                    /*
                        mysqli_fetch - permite converter uma estrutura que veio do BD para um formato de lista, temos 3 tipos de fetch
                        
                        mysqli_fetch_array - cria uma lista de dados, porém é um código mais obsoleto
                        
                        mysqli_fetch_assoc - cria uma lista de dados, com mais velocidade e segurança no processo de conversão
                        
                        mysqli_fetch_object - cria uma lista de dados em um formato de objeto
                    
                    */
                    //Permite gerenciar a qtde de vezes que a repetição irá realizar, além de converter os dados do BD em uma lista utilizando o mysqli_fetch_assoc
                    while($rsContatos = mysqli_fetch_assoc($select))
                    {
                ?>
                    <tr id="tblLinhas">
                        <td class="tblColunas"><?=$rsContatos['nome']?>  </td>
                        <td class="tblColunas"><?=$rsContatos['celular']?>  </td>
                        <td class="tblColunas"><?=$rsContatos['sigla']?>  </td>
                        <td class="tblColunas"><?=$rsContatos['email']?>  </td>
                        <td class="tblColunas">
                            <a href="bd/excluirContato.php?modo=excluir&id=<?=$rsContatos['idContato']?>" onclick="return confirm('Deseja realmente excluir esse Registro?')">
                                <img src="img/trash.png" alt="Excluir" title="Excluir" class="excluir">
                            </a>

                            <a href="index.php?modo=consultar&id=<?=$rsContatos['idContato']?>">
                                <img src="img/edit.png" alt="Editar" title="Editar" class="editar">
                            </a>  
                                
                            <img src="img/search.png" alt="Visualizar" title="Visualizar" class="pesquisar" onclick="visualizar(<?=$rsContatos['idContato']?>);">
                        </td>
                    </tr>
                <?php 
                    }
                
                ?>    
                
            </table>

        </div>
    </body>
</html>