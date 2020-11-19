<?php 
/*Abre a conexão com o BD*/

    //Import do arquivo de Variaveis e Constantes
    require_once('../modulo/config.php');

    //Import do arquivo de função para conectar no BD
    require_once('conexaoMysql.php');

    //import do arquivo de função do upload da foto
    require_once('uploadDaFoto.php');

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
$foto = uploadFoto($_FILES['fleFoto']);



// var_dump($arquivoUpload);

$sql = "insert into tblcontatos 
            (
                nome, 
                celular, 
                email, 
                idEstado, 
                dataNascimento, 
                sexo, 
                obs,
                foto
                
            )
            values
            (
                '". $nome ."',
                '". $celular ."',
                '". $email ."', 
                 ".$estado.",
                '". $dataNascimento ."',
                '". $sexo ."', 
                '". $obs ."',
                '" . $foto . "'
            )
        ";

// Executa no BD o Script SQL

if (mysqli_query($conex, $sql))
{
    echo("
            <script>
                alert('Registro Inserido com sucesso!');
                location.href = '../index.php';
            </script>
    ");
    
    //Permite redirecionar para uma outra página
    //header('location:../index.php');
}
else
    echo("
            <script>
                alert('Erro ao Inserir os dados no Banco de Dados! Por favor verificar a digitação de todos os dados.');
                location.href = '../index.php';
                window.history.back();
            </script>
    
        ");

?>