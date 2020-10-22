<?php

session_start();
/*Abre a conexão com o BD*/

    //Import do arquivo de Variaveis e Constantes
    require_once('../modulo/config.php');

    //Import do arquivo de função para conectar no BD
    require_once('conexaoMysql.php');

    //chama a função que vai estabelecer a conexão com o BD
    if(!$conex = conexaoMysql())
    {
        echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
        //die; //Finaliza a interpretação da página
    }

/*Variaveis*/
$nome = (string) null;
$celular = (string) null;
$email = (string) null;
$estado = (int) null;
$dataNascimento = (string) null;
$sexo = (string) null;
$obs = (string) null;

/*Recebe todos os dados do formulário*/
$nome = $_POST['txtNome'];
$celular = $_POST['txtCelular'];
$email = $_POST['txtEmail'];
$estado = $_POST['sltEstados'];

//explode() - localiza um caracter separador do conteudo e dividi os dados em um vetor
$data = explode("/", $_POST['txtNascimento']);

//Arrumando a data para ficar no padrão americano
$dataNascimento = $data[2] . "-" . $data[1] . "-" . $data[0];


/*
Ex: explode()
26/08/2003
0  1   2

$data[0] = 26
$data[1] = 08
$data[2] = 2003


*/

$sexo = $_POST['rdoSexo'];
$obs = $_POST['txtObs'];

$sql = "update tblcontatos set 
            
            nome = '".$nome."' ,
            celular = '".$celular."' , 
            email = '".$email."',
            idEstado = ".$estado.",
            dataNascimento = '".$dataNascimento."',
            sexo = '".$sexo."',
            obs = '".$obs."'
            
            where idContato = " . $_SESSION["id"] ." ;";

            //$_SESSION['id'] = null // elimina o conteudo da variavel de sessão

            //session_destroy(); //elimina todas a variaveis de sessão

            //session_unset($_SESSION['id']); //elimina a variavel de sessão inserida ! serve para versões mais antigas do php
            unset($_SESSION['id']); // igual ao session_unset porem para versões mais novas
echo ($sql);

// Executa no BD o Script SQL

if (mysqli_query($conex, $sql))
{
    echo("
            <script>
                alert('Registro atulizado com sucesso!');
                location.href = '../index.php';
            </script>
    ");
    
    //Permite redirecionar para uma outra página
    //header('location:../index.php');
}
else
    echo("
            <script>
                alert('Erro ao atualizar os dados no Banco de Dados! Favor verificar a digitação de todos os dados.');
                location.href = '../index.php';
                window.history.back();
            </script>
    
        ");

?>