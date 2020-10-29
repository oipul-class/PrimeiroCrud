<?php 
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
$foto = (string) "noImage.png";

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

//  Recebendo o arquivo para uplaod
//  validando o tamanho do arquivo e tipo
// $_FILES["arquivo"]["atributo"]
if ($_FILES['fleFoto']['size']!="0" && $_FILES['fleFoto']['type']!="") 
{
    
    $diretorioArquivo = "../arquivos/"; // nome da pasta que iremos armazenar as imagens de upload
    $arquivosPermitidos = array( "image/tiff", "image/jpeg" , "image/jpg", "image/png"); // reune todas as extensões permitidas para fazer o upload
    $tamanhoMaximoDoArquivo = 5120; // tamanho maximo do arquivo definido pelo php.int do xammp !valor relativo a kbytes

    $arquivoUpload = $_FILES['fleFoto']; // puxar do form o arquivo
    $caminhoTemporario = $arquivoUpload['tmp_name'];    

    $tamanhoDoArquivo = (round($arquivoUpload['size']/1024)); // Recebe o tamanho do arquvio que será enviado ao servidor e dividindo por 1024 para deixar em kb ao inves de byte
    
    $extencaoDoArquivo = $arquivoUpload['type'];
    
    if ( in_array($extencaoDoArquivo , $arquivosPermitidos) ) 
    {
        if ( $tamanhoDoArquivo <= $tamanhoMaximoDoArquivo ) 
        {
            $nomeDoArquivo = pathinfo( $arquivoUpload['name'] , PATHINFO_FILENAME ); // extrair o nome do arquivo usando a função pathinfo(variavel[atributo] , PATHINFO_(TIPO))
            
            $extencaoDoArquivoExtraida = pathinfo ( $arquivoUpload['name'] , PATHINFO_EXTENSION); // extrair a extensão do nome do arquivo usando a função pathinfo
            /* algoritimode criptografia 
                md5(variavel)
                sha1(variavel)
                hash() - permite criar sua própria criptografia
            */

            $nomeDoArquivoCripty = md5($nomeDoArquivo.uniqid(time())); // gerando um nome criptografado em md5 com
            // muniqid() - gerador de id baseado em hardware do dispositivo usado
            // time() - pega o horario(hora,minutos,segundo) daquele momento
            
            $foto = $nomeDoArquivoCripty.".".$extencaoDoArquivoExtraida; // juntando
            
            if (move_uploaded_file($caminhoTemporario , $diretorioArquivo.$foto)) // permite mover um arquivo entre dois diretórios
            {                                                                                                             // (caminho temporario , diretorio.nome do arquivo. "." .  extenção do arquivo)
                $foto = $foto;
            }
        }
        else // caso o arquvio passe do limite de tamanho 
        {
            echo "<script> alert('tamanho do arquivo maior do que " . $tamanhoMaximoDoArquivo . "KB'); </script>";
        }
    } 
    else // caso a extensão seja incopativel
    {
        echo "<script> alert('extensão de arquivo invalido'); </script>";
    }

}

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
                '". $obs ."' ,
                '" . $foto . "'
            )
        ";



//Executa no BD o Script SQL

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
                alert('Erro ao Inserir os dados no Banco de Dados! Favor verificar a digitação de todos os dados.');
                location.href = '../index.php';
                window.history.back();
            </script>
    
        ");

?>