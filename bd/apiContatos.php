<?php

 function listarContatos ($id) {

    //Import do arquivo de Variaveis e Constantes
    require_once('../modulo/config.php');

    //Import do arquivo de função para conectar no BD  
    require_once('conexaoMysql.php');

    if(!$conex = conexaoMysql())
    {
        echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
        //die; //Finaliza a interpretação da página
    }

    $sql = "select tblContatos.*, tblEstados.sigla from tblContatos, tblEstados where tblContatos.idEstado = tblEstados.idEstado and statusContato = 1";
    
    if ($id > 0) {
        $sql = $sql . " and tblContatos.idContato = " . $id;
    }

    $sql = $sql . " order by tblContatos.nome asc";

    $select = mysqli_query($conex, $sql);
    
    while($rsContatos = mysqli_fetch_assoc($select)) {
        //varios itens para o json
        $dados[] = array (
            //          => - o que alimenta o dado de um array
            'idContato'         => $rsContatos['idContato'],
            'nome'              => $rsContatos['nome'],
            'celular'           => $rsContatos['celular'],
            'email'             => $rsContatos['email'],
            'idEstado'          => $rsContatos['idEstado'],
            'sigla'             => $rsContatos['sigla'],
            'dataNascimento'    => $rsContatos['dataNascimento'],
            'sexo'              => $rsContatos['sexo'],
            'obs'               => $rsContatos['obs'],
            'foto'              => $rsContatos['foto'],
            'statusContato'     => $rsContatos['statusContato']

        );            
    } 

    //faça um header para dados importantes
    // $headerDados = array (
    //     'status' => 'success',
    //     'data' => date('d-m-y'),
    //     'contatos' => $dados
    // );
    if (isset($dados))
        $listContatosJson = convertJson($dados);
    else 
        false;
    //verificar se foi gerado um arquivo json
    if (isset($listContatosJson)) 
        return $listContatosJson;
    else
        return false;
 }

 function buscarContatos($nome) {

    //Import do arquivo de Variaveis e Constantes
    require_once('../modulo/config.php');

    //Import do arquivo de função para conectar no BD  
    require_once('conexaoMysql.php');

    if(!$conex = conexaoMysql())
    {
        echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
        //die; //Finaliza a interpretação da página
    }

    $sql = "select tblContatos.*, tblEstados.sigla from tblContatos, tblEstados where tblContatos.idEstado = tblEstados.idEstado and statusContato = 1 and tblContatos.nome like '%".$nome."%'";

    $select = mysqli_query($conex, $sql);
    
    while($rsContatos = mysqli_fetch_assoc($select)) {
        //varios itens para o json
        $dados[] = array (
            //          => - o que alimenta o dado de um array
            'idContato'         => $rsContatos['idContato'],
            'nome'              => $rsContatos['nome'],
            'celular'           => $rsContatos['celular'],
            'email'             => $rsContatos['email'],
            'idEstado'          => $rsContatos['idEstado'],
            'sigla'             => $rsContatos['sigla'],
            'dataNascimento'    => $rsContatos['dataNascimento'],
            'sexo'              => $rsContatos['sexo'],
            'obs'               => $rsContatos['obs'],
            'foto'              => $rsContatos['foto'],
            'statusContato'     => $rsContatos['statusContato']

        );            
    } 

    //faça um header para dados importantes
    // $headerDados = array (
    //     'status' => 'success',
    //     'data' => date('d-m-y'),
    //     'contatos' => $dados
    // );
    if (isset($dados))
        $listContatosJson = convertJson($dados);
    else 
        false;
    //verificar se foi gerado um arquivo json
    if (isset($listContatosJson)) 
        return $listContatosJson;
    else
        return false;
 }

 function inserirContato($dadosContato) {
    //Import do arquivo de Variaveis e Constantes
    require_once('../modulo/config.php');

    //Import do arquivo de função para conectar no BD  
    require_once('conexaoMysql.php');

    if(!$conex = conexaoMysql())
    {
        echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
        //die; //Finaliza a interpretação da página
    }
    
    $nome = (string) null;
    $celular = (string) null;
    $email = (string) null;
    $estado = (int) null;
    $dataNascimento = (string) null;
    $sexo = (string) null;
    $obs = (string) null;
    $foto = "noImage.png";


    $nome = $dadosContato['nome'];
    $celular = $dadosContato['celular'];
    $email = $dadosContato['email'];
    $estado = $dadosContato['estado'];
    $dataNascimento =$dadosContato['dataNascimento'];

    $sexo = $dadosContato['sexo'];
    $obs = $dadosContato['obs'];
    //$foto = uploadFoto($_FILES['fleFoto']);



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
                    foto,
                    statusContato
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
                    '" . $foto . "',
                    1
                )
            ";
    // Executa no BD o Script SQL

    if (mysqli_query($conex, $sql))
        
        return convertJson($dadosContato);
    else
        return false;       
     
}

function deletarContato($id) {
    //Import do arquivo de Variaveis e Constantes
    require_once('../modulo/config.php');

    //Import do arquivo de função para conectar no BD  
    require_once('conexaoMysql.php');

    if(!$conex = conexaoMysql())
    {
        echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
        //die; //Finaliza a interpretação da página
    }

    $sql = "delete from tblcontatos where idContato =" . $id;
                
    // Executa no BD o Script SQL

    if (mysqli_query($conex, $sql))
        return true;
    else
        return false;       
     
}

//converte uma Array em Json
function convertJson($data) {
    header("Content-Type:applicantion/json"); // forçando o cabeçalho do arquivo a ser aplicação do tipo json
    $listJson = json_encode($data); // codificando em json   
    return $listJson;
}
