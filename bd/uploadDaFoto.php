<?php 

function uploadFoto($arquivo) {
    //  Recebendo o arquivo para uplaod
//  validando o tamanho do arquivo e tipo
// $_FILES["arquivo"]["atributo"]
    if ($arquivo['size']!="0" && $arquivo['type']!="") 
    {
        $statusDoUpload = null;
        $foto = (string) null;
        $diretorioArquivo = "../arquivos/"; // nome da pasta que iremos armazenar as imagens de upload
        $arquivosPermitidos = array( "image/tiff", "image/jpeg" , "image/jpg", "image/png"); // reune todas as extensões permitidas para fazer o upload
        $tamanhoMaximoDoArquivo = 5120; // tamanho maximo do arquivo definido pelo php.int do xammp !valor relativo a kbytes

        $arquivoUpload = $arquivo; // puxar do form o arquivo
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
                
                if (move_uploaded_file($caminhoTemporario , $diretorioArquivo.$foto)) // permite mover um arquivo entre dois diretórios                                                                                                     // (caminho temporario , diretorio.nome do arquivo. "." .  extenção do arquivo)
                    $statusDoUpload = true;
                else
                    $statusDoUpload = false;
            }
            else // caso o arquvio passe do limite de tamanho 
            {
                // echo "<script> alert('tamanho do arquivo maior do que " . $tamanhoMaximoDoArquivo . "KB'); 
                // location.href = '../index.php';
                // window.history.back();
                // </script>";
                // die;
                return 3;
            }
        } 
        else // caso a extensão seja incopativel
        {
            // echo "<script> alert('extensão de arquivo invalido'); 
            // location.href = '../index.php';
            // window.history.back();
            // </script>";
            // die;
            return 2;
        }
        if ($statusDoUpload)
            return $foto;
        else
            return "noImage.png";
    }

}

?>