<?php 

//import do arquivo para iniciar as dependencias da API

require_once("vendor/autoload.php"); 

//Instancia da classe app
$app = new \Slim\App();

//(objeto)->  = para poder usar uma instancia do objeto

//metodo get

//EndPoint parar o acesso a araiz da pasata da API
$app->get('/', function ($resquest, $response, $args){ // get ('/' = site/api/ (/pedido) ou raiz ,  function ($resquest, $response, $args){} 
    //resquest = pedido | response = dados/resposta | args = argumentos
    return $response->getBody()->write("API de contatos do CRUD"); //para enviar dados no body do protocolo http ou escrever uma mensagem para o usuario
}); 

//EndPoint parar o acesso a araiz da pasata da API
$app->get('/contatos', function ($resquest, $response, $args){ // get ('/' = site/api/ (/pedido) ou raiz ,  function ($resquest, $response, $args){} 
    //resquest = pedido | response = dados/resposta | args = argumentos

    require_once("../bd/apiContatos.php"); //import do arquivo que vai buscar no banco de dados

    $listContatos = listarContatos();
    if($listContatos) { // função para listar todos os contatos 
        return $response    -> withStatus(200)
                            -> withHeader('Content-Type', 'application/json')
                            -> write($listContatos);
        //widthStatus (status http)
        //widthHeader ('Content-Type' , 'application/tipo')
        //write() escreve na tela
    }else {
        return $response    -> withStatus(204);
    } 
    
    //return $response->getBody()->write("Listar dados de contatos"); //para enviar dados no body do protocolo http ou escrever uma mensagem para o usuario
});


$app->run(); // carrega todos os EndPoints criados na API !!!sempre deixar como ultima linha

