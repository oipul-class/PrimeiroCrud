<?php

 //Import do arquivo de Variaveis e Constantes
 require_once('../modulo/config.php');

 //Import do arquivo de função para conectar no BD
 require_once('conexaoMysql.php');


 //chama a função que vai estabelecer a conexão com o BD


 function listarContatos () {

    if(!$conex = conexaoMysql())
    {
        echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
        //die; //Finaliza a interpretação da página
    }

    $sql = "select tblContatos.*, tblEstados.sigla from tblContatos, tblEstados where tblContatos.idEstado = tblEstados.idEstado and statusContato = 0";

    $select = mysqli_query($conex, $sql);

    if($rsContatos = mysqli_fetch_assoc($select)) {
        //criando o formato json

        header("Content-Type:applicantion/json"); // forçando o cabeçalho do arquivo a ser aplicação do tipo json
        $listContatosJSON = json_encode($rsContatos); // codificando em json
    } 
    //verificar se foi gerado um arquivo json
    if (isset($listContatosJSON)) 
    return $listContatosJSON;
    else
    return false;
 }

