<?php 

//import do arquivo para iniciar as dependencias da API
require_once("vendor/autoload.php"); 

//Instancia da classe app
$app = new \Slim\App();

//(objeto)->  = para poder usar uma instancia do objeto

//metodo get

//EndPoint para o acesso a raiz da pasta da API
$app->get('/', function ($resquest, $response, $args){ // get ('/' = site/api/ (/pedido) ou raiz ,  function ($resquest, $response, $args){} 
    //resquest = pedido | response = dados/resposta | args = argumentos
    return $response->getBody()->write("API de contatos do CRUD"); //para enviar dados no body do protocolo http ou escrever uma mensagem para o usuario
}); 

//EndPoint para o acesso a os contatos do banco pela API
$app->get('/contatos', function ($resquest, $response, $args){ // get ('/' = site/api/ (/pedido) ou raiz ,  function ($resquest, $response, $args){} 

    require_once("../bd/apiContatos.php"); //import do arquivo que vai buscar no banco de dados

    //recebendo dados da QueryString (essas variáveis podem ou não chegar na requisição)
    //existem duas maneiras de receber uma variavel pela QueryString (no php)
    // 1- $_GET
    // 2- getQueryStringParams() - ( do slim ) -- uso = $(nome da variavel) = $resquest->getQueryParams()[(nome da variavel)]
    if(isset($resquest->getQueryParams()['nome'])){
        $nome = $resquest->getQueryParams()['nome'];


        $listContatos = buscarContatos($nome);

    }else {
    
     $listContatos = listarContatos(0);
    }

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

//EndPoint para buscar contato pelo id
//quando ira chegar um valor pelo metodo get deve usar o padrão que o parametro deve ter {}
$app->get('/contatos/{id}' , function ($resquest, $response, $args){
    //tudo depois da barra como parametro...exemplo = (id)... é pego pelo args
    //$args - puxar parametros colocado na url

    $id = $args['id'];
    
    require_once("../bd/apiContatos.php"); //import do arquivo que vai buscar no banco de dados

    $listContatos = listarContatos($id);
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

    
});



$app->run(); // carrega todos os EndPoints criados na API !!!sempre deixar como ultima linha