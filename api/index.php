<?php 

//import do arquivo para iniciar as dependencias da API
require_once("vendor/autoload.php"); 

//Instancia da classe app
$app = new \Slim\App();

//(objeto)->  = para poder usar uma instancia do objeto

//metodo get

//EndPoint para o acesso a raiz da pasta da API
$app->get('/', function ($request, $response, $args){ // get ('/' = site/api/ (/pedido) ou raiz ,  function ($request, $response, $args){} 
    //request = pedido | response = dados/resposta | args = argumentos
    return $response->getBody()->write("API de contatos do CRUD"); //para enviar dados no body do protocolo http ou escrever uma mensagem para o usuario
}); 

//EndPoint para o acesso a os contatos do banco pela API
$app->get('/contatos', function ($request, $response, $args){ // get ('/' = site/api/ (/pedido) ou raiz ,  function ($request, $response, $args){} 

    require_once("../bd/apiContatos.php"); //import do arquivo que vai buscar no banco de dados

    //recebendo dados da QueryString (essas variáveis podem ou não chegar na requisição)
    //existem duas maneiras de receber uma variavel pela QueryString (no php)
    // 1- $_GET
    // 2- getQueryStringParams() - ( do slim ) -- uso = $(nome da variavel) = $request->getQueryParams()[(nome da variavel)]
    if(isset($request->getQueryParams()['nome'])){
        $nome = $request->getQueryParams()['nome'];


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
$app->get('/contatos/{id}' , function ($request, $response, $args){
    //tudo depois da barra como parametro...exemplo = (id)... é pego pelo args
    //$args - puxar parametros colocado na url

    $id = $args['id'];
    
    require_once("../bd/apiContatos.php"); //import do arquivo que vai buscar no banco de dados

    
    if($listContatos = listarContatos($id)) { // função para listar todos os contatos 
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

//EndPoint para inserir 
//conselho: a chegadas dos dados deve ser json

//FormData
$app->post('/contatos', function ($request, $response, $args){
    //garantindo que o formato seja JSON

    //recebe o content-type da requesição
    $contentType = $request->getHeaderLine('Content-Type'); // getHeaderLine permite pegar conteudo sobre o header

    //validando se é um json
    if ($contentType == "application/json") {
        //recebe todos os dados enviados para a api
        $dadosJson = $request->getParsedBody();
        
        if ($dadosJson=="" || $dadosJson==null) {

            return $response    -> withStatus(400)
                                -> withHeader('Content-Type', 'application/json')
                                -> write('
                                    {
                                        "status":"Fail",
                                        "Message":"Dados enviados não podem ser nulos"
                                    }
                                    ');

        }else {
            //Require das funções
            require_once("../bd/apiContatos.php");
            //dados inseridos com sucesso
            if ($dados = inserirContato($dadosJson)) {
                return $response    -> withStatus(201)
                                    -> withHeader('Content-Type', 'application/json')
                                    -> write($dados); 
            }else { //falha na inserção dos dados
                return $response    -> withStatus(401)
                                    -> withHeader('Content-Type', 'application/json')
                                    -> write('
                                            {
                                                "status":"Fail",
                                                "Message":"Falha ao inserir os dados no BD. Verificar se os dados enviados estão corretos"
                                            }
                                            ');
            }

        }

    }else {
        //mensagem de erro de Content-Type
        return $response    -> withStatus(415)
                            -> withHeader('Content-Type', 'application/json')
                            -> write('
                                    {
                                    "status":"Fail",
                                    "Message":"Erro no Content-Type da Requisição"
                                    }
                                    ');
    }
});

$app->delete('/contatos/{id}' , function ($request, $response, $args){
    //tudo depois da barra como parametro...exemplo = (id)... é pego pelo args
    //$args - puxar parametros colocado na url

    $id = $args['id'];
    
    require_once("../bd/apiContatos.php"); //import do arquivo que vai buscar no banco de dados

    $listContatos = deletarContato($id);
    if($listContatos=="1") { // função para listar todos os contatos 
        return $response    -> withStatus(200)
                            -> withHeader('Content-Type', 'application/json')
                            -> write("contato deletado");
        //widthStatus (status http)
        //widthHeader ('Content-Type' , 'application/tipo')
        //write() escreve na tela
    }else {
        return $response    -> withStatus(400)
                            -> write("contato não existe");
    }

    
});

//update no banco com imagem só pode ser feito com post ao invez de put
$app->post('/contatos/{id}' , function ($request, $response, $args){
    //tudo depois da barra como parametro...exemplo = (id)... é pego pelo args
    //$args - puxar parametros colocado na url


    $contentType = $request->getHeaderLine('Content-Type');  

    

    if (strstr($contentType ,"multipart/form-data")) {

        $id = $args['id'];
        $arquivo = $_FILES['file'];  

        require_once("../bd/apiContatos.php"); //import do arquivo que vai buscar no banco de dados

        //chama a função de fazer o upload e update no banco 
        $retornoDados = updateContato($arquivo , $id , "");

        if ($retornoDados=="1") 
            return $response    -> withStatus(201);
        elseif ($retornoDados=="0")
            return $response    -> withStatus(415)
                                -> withHeader('Content-Type', 'application/json')
                                -> write('
                                        {
                                            "status":"Fail",
                                            "Message":"Erro no upload"
                                        }
                                        ');
        else 
            return  $response   -> withStatus(415)
                                -> withHeader('Content-Type', 'application/json')
                                -> write('
                                        {
                                            "status":"Fail",
                                            "Message":"' . $retornoDados . '"
                                        }
                                        ');

    }

    
});

$app->put('/contatos/{id}' , function ($request, $response, $args){
    //garantindo que o formato seja JSON

    //recebe o content-type da requesição
    $contentType = $request->getHeaderLine('Content-Type'); // getHeaderLine permite pegar conteudo sobre o header
    
    $id = $args['id'];

    //validando se é um json
    if ($contentType == "application/json") {
        //recebe todos os dados enviados para a api
        $dadosJson = $request->getParsedBody();
        
        if ($dadosJson=="" || $dadosJson==null) {

            return $response    -> withStatus(400)
                                -> withHeader('Content-Type', 'application/json')
                                -> write('
                                    {
                                        "status":"Fail",
                                        "Message":"Dados enviados não podem ser nulos"
                                    }
                                    ');

        }else {
            //Require das funções
            require_once("../bd/apiContatos.php");
            //dados inseridos com sucesso
            if ($dados = updateContato(null, $id, $dadosJson)) {
                return $response    -> withStatus(201)
                                    -> withHeader('Content-Type', 'application/json')
                                    -> write($dados); 
            }else { //falha na inserção dos dados
                return $response    -> withStatus(401)
                                    -> withHeader('Content-Type', 'application/json')
                                    -> write('
                                            {
                                                "status":"Fail",
                                                "Message":"Falha ao inserir os dados no BD. Verificar se os dados enviados estão corretos"
                                            }
                                            ');
            }

        }

    }else {
        //mensagem de erro de Content-Type
        return $response    -> withStatus(415)
                            -> withHeader('Content-Type', 'application/json')
                            -> write('
                                    {
                                    "status":"Fail",
                                    "Message":"Erro no Content-Type da Requisição"
                                    }
                                    ');
    }
});


$app->run(); // carrega todos os EndPoints criados na API !!!sempre deixar como ultima linha