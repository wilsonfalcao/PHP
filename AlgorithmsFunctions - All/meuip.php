<?php

//Verifica o IP a ser mostrado dependendo da requisição... Requisição SUB = IP_SUB, Requisição Internet = IP_VALIDO
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $IP_ADDREASS = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $IP_ADDREASS = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $IP_ADDREASS = $_SERVER['REMOTE_ADDR'];
}

//PEGANDO DADOS DE REGISTRO DE TRÁFEGO
$HOST_ARRAY = dns_get_record($IP_ADDREASS);

//RETORNANDO O HOST DE ACESSO DA MÁQUINA (REQUISIÇÃO)
$HOST = $HOST_ARRAY[0]['host'];

////RETORNANDO O IP DO GATEWAY DE ACESSO DA MÁQUINA (REQUISIÇÃO) | APENAS PARA USO LOCAL
$IP_GATEWAY = $HOST_ARRAY[0]['ip'];

?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Meu IP - Qual &eacute; o meu IP?</title>
        <meta name="description" content="Descubra qual &eacute; o seu endereÃ§o IP e seu IP Reverso (ip da rede, ip conex&atilde;o, endereÃ§o ip, ip fixo, ip reverso)." />

        <!-- Bootstrap Core CSS -->
        <!--<link href="css/bootstrap.min.css" rel="stylesheet"> Por algum motivo parou de funcionar aqui-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />

        <!-- Custom CSS -->
        <link href="" rel="stylesheet" />
    </head>

    <body>
        <!-- Navigation 
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="http://meuip.com">
                   <img src="img/meuip.com.png" alt="">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                   <li>
                      <a href="http://meuip.com">MeuIP.com</a>
                   </li>
	                <li>
		                <a href="//meuip.com/api-webservice-meuip.php">API-Webservice para detectar IP</a>
	                </li>
	                <!--                   <li>
																				<a href="http://www.localizaip.com.br">Localizar IP</a>
																		 </li>
                </ul>
            </div>
        </div>

    </nav>-->

        <!-- Page Content -->
        <div class="container">
            <div class="row">
                <div class="col-lg-12" align="center">
                    <i>
                        <h3 style="color: #666;"><b>MeuIP</b> Uma p&aacute;gina Simples, Leve e R&aacute;pida. Pra quem &eacute; objetivo e s&oacute; quer descobrir seu IP.<br /></h3>
                    </i>
                    <table>
                        <tr>
                            <td align="center">
                                <h1>
                                    <b>O meu IP &eacute;:<br /></b>
                                    <font color="#FF0000"><?php echo $IP_ADDREASS;?></font>
                                </h1>
                                <h3>Nome Local: <?php echo $HOST;?></h3>
								<h3>IP GATEWAY: <?php echo $IP_GATEWAY;?></h3>
                                <br />
                            </td>
                        </tr>
                    </table>
                    <br />
                </div>
                <br />
            </div>

            <div align="center">
                <div style="width: 70%;">
                    <h3>O QUE É SEU MEU IP?</h3>
                    &nbsp;&nbsp;&nbsp;&nbsp;IP significa <b>Internet Protocol</b> e &eacute; um n&uacute;mero que seu computador (ou roteador) recebe quando se conecta &agrave; Internet. &Eacute; atrav&eacute;s desse n&uacute;mero que seu
                    computador &eacute; identificado e pode enviar e receber dados.<br />
                    &nbsp;&nbsp;&nbsp;&nbsp;O IP &eacute; definido pelo seu provedor de Internet e pode ser est&aacute;tico (n&atilde;o mudar) ou din&acirc;mico (mudando de tempos em tempos).
                </div>
            </div>
            <br />

            <br />
            <br />
        </div>

        <!-- /.container -->

        <!-- jQuery -->
        <script src="js/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>

<!--Fim da Pagina-->
