<?php

/**
 * All action about Products And Sales
 */
//require("../Vendor/pusher-http-php-master/lib/Pusher.php");
require("../Vendor/phpmailer/PHPMailerAutoload.php");
require("../Vendor/pushbots/PushBots.class.php");
require("../Vendor/turbo_send_email_code/lib/TurboApiClient.php");

class UtilsController extends AppController {

    public function __construct($request = null, $response = null) {
        $this->layout = '';
       // $this->set('title_for_layout', '');
        parent::__construct($request, $response);
    }

	    public function beforeFilter() {
		
		}
	
    /**
     * Envia notificação apenas para UM usuário especifico
     * @param type $userId
     * @param type $message
     */
   public function sendMobileNotification($userId = null, $message = null) {
        //captura dados do usuario destinatario
        $query = "select * from users_using where user_id = {$userId};";
        $params = array(
            'User' => array(
                'query' => $query
            )
        );
        $destinatario = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);

        /*
         * Inicia envio da Push
         */
        $pb = new PushBots();
        //$appID = '56f2db1e4a9efa66868b4567';
       // $appSecret = 'c0e22a98aee35816d1266a4c20bc9979';
	   		$appID = '578798eb4a9efa173a8b4567';
        $appSecret = 'fb250a5c26b589fc9ae57ed790a26643';
        $pb->App($appID, $appSecret);

        //mensagem
        $pb->AlertOne($message);

        if ($destinatario[0]['users_using']['android'] == 'ACTIVE') {
            $pb->PlatformOne("1");
        } else if ($destinatario[0]['users_using']['ios'] == 'ACTIVE') {
            $pb->PlatformOne("0");
        }

        //captura registration id do usuario
        $pb->TokenOne($destinatario[0]['users_using']['registration_id']);

        //Push to Single Device
        $pb->PushOne();

    }
	
	
	  /**
     * Envia notificação apenas para UM PROFISSIONAL especifico
     * @param type $userId
     * @param type $message
     */
   public function sendMobileNotificationToSecondaryUser($userId = null, $message = null) {
		$this->autoRender = false;
        //captura dados do usuario destinatario
        $query = "select * from secondary_users_using where secondary_users_id = {$userId};";
        $params = array(
            'User' => array(
                'query' => $query
            )
        );
        $destinatario = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);

        /*
         * Inicia envio da Push
         */
        $pb = new PushBots();
        //$appID = '56f2db1e4a9efa66868b4567';
       // $appSecret = 'c0e22a98aee35816d1266a4c20bc9979';
	   
	     //DADOS PARA APP DE USUARIOS/CLIENTES
   		//$appID = '578798eb4a9efa173a8b4567';
       //$appSecret = 'fb250a5c26b589fc9ae57ed790a26643';
       
	   $appID = '5807ba1a4a9efa96ce8b4567';
        $appSecret = '364d8b0228a1883d300d36f11b02c6c8';
	   
	   $pb->App($appID, $appSecret);

        //mensagem
        $pb->AlertOne($message);

        if ($destinatario[0]['secondary_users_using']['android'] == 'ACTIVE') {
            $pb->PlatformOne("1");
        } else if ($destinatario[0]['secondary_users_using']['ios'] == 'ACTIVE') {
            $pb->PlatformOne("0");
        }

        //captura registration id do usuario
        $pb->TokenOne($destinatario[0]['secondary_users_using']['registration_id']);

        //Push to Single Device
        $pb->PushOne();
		
		print_r($destinatario);

    }
	
	    /**
     * Envia notificação apenas para UM usuário especifico
     * @param type $userId
     * @param type $message
     */
   public function TESTEsendMobileNotification($userId = null, $message = null) {
		$this->autoRender = false;
        //captura dados do usuario destinatario
        $query = "select * from users_using where user_id = {$userId};";
        $params = array(
            'User' => array(
                'query' => $query
            )
        );
        $destinatario = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);

        /*
         * Inicia envio da Push
         */
        $pb = new PushBots();
        //$appID = '56f2db1e4a9efa66868b4567';
       // $appSecret = 'c0e22a98aee35816d1266a4c20bc9979';
	   		$appID = '578798eb4a9efa173a8b4567';
        $appSecret = 'fb250a5c26b589fc9ae57ed790a26643';
        $pb->App($appID, $appSecret);

        //mensagem
        $pb->AlertOne($message);

        if ($destinatario[0]['users_using']['android'] == 'ACTIVE') {
            $pb->PlatformOne("1");
        } else if ($destinatario[0]['users_using']['ios'] == 'ACTIVE') {
            $pb->PlatformOne("0");
        }

        //captura registration id do usuario
        $pb->TokenOne($destinatario[0]['users_using']['registration_id']);

        //Push to Single Device
        $pb->PushOne();

    }

    /**
     * Envia a mesma notificação para todos os usuários
     * @param type $message
     */
    public function sendPublicMobileNotification($mensagem = null) {
 $this->autoRender = false;
        $pb = new PushBots();
// Application ID
        //$appID = '56f2db1e4a9efa66868b4567';
// Application Secret
       // $appSecret = 'c0e22a98aee35816d1266a4c20bc9979';
		
		$appID = '578798eb4a9efa173a8b4567';
        $appSecret = 'fb250a5c26b589fc9ae57ed790a26643';
		
        $pb->App($appID, $appSecret);
// Notification Settings
        $platforms[0] = 0;
        $platforms[1] = 1;

        $pb->Alert($mensagem);

        $pb->Platform($platforms);
// Custom fields - payload data
        $customfields = array("author" => "Jeff", "nextActivity" => "com.example.sampleapp.Next");
        $pb->Payload($customfields);
// Push it !
        $pb->Push();
    }

    public function testeNasp() {
        $this->layout = "";

        $this->enviarEmailAgendamentoCancelado("matheusodilon0@gmail.com");
    }

    public function testeapi() {

       $query = "select * from subclasses inner join classes on classes.id = subclasses.classe_id;";
			$params = array(
            'General' => array(
                'query' => $query
				)
			);
			$servicesArr = unserialize(utf8_decode($this->AccentialApi->urlRequestToGetData('General', 'query', $params)));
                        //$servicesArr = $this->AccentialApi->urlRequestToGetData('General', 'query', $params);
                        print_r($servicesArr);
    }

    /**
     * Função responsável por acompanhar mudança de status das transações,
     * as mudanças são enviadas pelo MoIP e processadas por nosso sistema
     * e notificadas aos respectivos usuários
     * */
    public function NASPMoip() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
		
			

            $transactionId = $_POST['id_transacao'];
            $transactionState = $_POST['status_pagamento'];
            $userEmail = $_POST['email_consumidor'];

            //Buscando Payment State na base de dados
            $arrayParams = array(
                'PaymentState' => array(
                    'conditions' => array(
                        'PaymentState.moip_code' => $transactionState
                    )
                )
            );
            $paymentState = $this->AccentialApi->urlRequestToGetData('payments', 'first', $arrayParams);


            //Alterando Status do pagamento na base de dados
            $updateSql = "update checkouts set payment_state_id = {$transactionState} where id = {$transactionId};";
            $updateParams = array(
                'User' => array(
                    'query' => $updateSql
                )
            );
            $statistics = $this->AccentialApi->urlRequestToGetData('users', 'query', $updateParams);

            //Buscando infos da compra
            $selectCheckout = "select * from checkouts inner join offers on offers.id = checkouts.offer_id inner join users on users.id = checkouts.user_id inner join companies on companies.id = checkouts.company_id inner join users_using on users_using.user_id = users.id  where checkouts.id = {$transactionId};";

            $selectParams = array(
                'User' => array(
                    'query' => $selectCheckout
                )
            );
            $checkout = $this->AccentialApi->urlRequestToGetData('users', 'query', $selectParams);

            //Email para o Usuário
            if ($transactionState == 1) {
                // $this->sendEmailChangeState($userEmail, $checkout[0], "{$paymentState['PaymentState']['name']}");
               
				$isService = false;
				
				/**
				* Vamos verificar se é uma compra de serviço. Caso seja, criaremos um voucher
				*/
				
				 /*$sqlSelectServicesCheck = "select * from checkouts_services where checkout_id = {$transactionId};";

				$selectParamsServicesCheckl = array(
                'User' => array(
                    'query' => $sqlSelectServicesCheck
					)
				);
				$servicesCheckout = $this->AccentialApi->urlRequestToGetData('users', 'query', $selectParamsServicesCheckl); */
				
				
				/**
				* VAMOS VERIFICAR SE REALMENTE ESTA É UMA COMPRA COM VOUCHER
				*/
				$sqlIsVoucher = "SELECT * FROM services_vouchers WHERE checkout_id = {$transactionId};";
				$paramsIsVouchers = array(
                'User' => array(
                    'query' => $sqlIsVoucher
					));
				
				$isVoucherCheck = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramsIsVouchers);  
				print_r($isVoucherCheck);
				
				
				
				 if(!empty($isVoucherCheck)){
				
				/*$sqlInsertVoucher = "INSERT INTO  services_vouchers(
				`company_id`, 
				`offer_id`,
				`service_id`,
				`user_id`,
				`pre_scheduled_date`,
				`pre_scheduled_hour`,
				`acquisition_date`,
				`status`,
				`checkout_id`)
				VALUES(
					{$checkout[0]['checkouts']['company_id']},
					{$checkout[0]['checkouts']['offer_id']},
					{$servicesCheckout[0]['checkouts_services']['service_id']},
					{$checkout[0]['checkouts']['user_id']},
					'0000-00-00 00:00:00',
					'00:00:00',
					'{$checkout[0]['checkouts']['date']}',
					'APPROVED',
					{$checkout[0]['checkouts']['id']}
				);"; */
				
				$updateVoucher = "UPDATE services_vouchers SET `status` = 'APPROVED' WHERE id = {$isVoucherCheck[0]['services_vouchers']['id']};";
				
				$paramUpVoucherCheck = array(
                'User' => array(
                    'query' => $updateVoucher
					)
				);
				$this->AccentialApi->urlRequestToGetData('users', 'query', $paramUpVoucherCheck);
					
				
				$this->enviarEmailStatusServicoDaEmpresaVoucherDisponivel($userEmail, $checkout[0]);
				
				$isService = true;
				
				} 
				
				if($isService == false){
				/*
				CASO VENDA SEJA PRÓPRIA DO SALÃO, NÃO ENVIAR EMAIL RELATORIO
				*/
				if($checkout[0]['checkouts']['company_id'] == 99999){
				
					$this->sendResumeEmail($transactionId);
					
					/**
					EMAIL PREPARANDO ENVIO DE PRODUTO JEZZY
					**/
					$this->enviarEmailStatusPreparandoEnvio($userEmail, $checkout[0]);
				
				}else{
				
					$this->enviarEmailStatusRetirarProdutoDaEmpresa($userEmail, $checkout[0]);
					
				}
				
				}
				
				$this->enviarEmailPagamentoAprovado($userEmail, $checkout[0], $paymentState['PaymentState']['name']);
				
				
                
				
				//$this->sendPublicMobileNotification('O pagamento da sua compra foi Aprovado!');
				$this->sendMobileNotification($checkout[0]['checkouts']['user_id'], 'O pagamento da sua compra foi Aprovado!');
				
				
            }

            if ($transactionState == 2) {
			
				
                // $this->enviarEmailPagamentoAprovado($userEmail, $checkout[0], $paymentState['PaymentState']['name']);
                $this->enviaremailStatusRecebemosPedido($userEmail, $checkout[0]);
                $this->enviarEmailStatusAguardandoPagamento($userEmail, $checkout[0]);
				
				$this->sendPublicMobileNotification('Nós recebemos seu pedido, aguarde aprovação do pagamento.');
				$this->sendMobileNotification($checkout[0]['checkouts']['user_id'], 'Nós recebemos seu pedido, aguarde aprovação do pagamento.');
            }

            /**
             * Caso o status do pagamento seja CONCLUIDO, então verificamos se não se refere a uma compra de serviço
             */
            if ($transactionState == 4) {

                $searchVoucher = "select * from services_vouchers inner join services on services.id = services_vouchers.service_id inner join subclasses on subclasses.id = services.subclasse_id where services_vouchers.checkout_id  = {$checkout[0]['checkouts']['id']};";
                $voucherParams = array(
                    'User' => array(
                        'query' => $searchVoucher
                    )
                );
                $voucher = $this->AccentialApi->urlRequestToGetData('users', 'query', $voucherParams);

                //print_r($voucher);

                if (!empty($voucher)) {

                    //Alterando Status do pagamento na base de dados
                    $updateVoucher = "update services_vouchers set status = 'APPROVED' where id = {$voucher[0]['services_vouchers']['id']};";
                    $updateParamsVoucher = array(
                        'User' => array(
                            'query' => $updateVoucher
                        )
                    );
                    $this->AccentialApi->urlRequestToGetData('users', 'query', $updateParamsVoucher);

                    $this->enviarEmailVoucher($userEmail, $checkout[0], $voucher[0]);
                }
				
				
				
				//$this->sendPublicMobileNotification('Sua compra foi concluída!');
				$this->sendMobileNotification($checkout[0]['checkouts']['user_id'], 'Sua compra foi concluída!');
            }
			
			
			/**
			*CANCELADO
			*/
			if ($transactionState == 5) {
			
			
				$this->enviarEmailPedidoCancelado($userEmail, $checkout[0], $paymentState['PaymentState']['name']);
				$this->sendMobileNotification($checkout[0]['checkouts']['user_id'], 'Sua compra foi cancelada!');
			
			}
			
			/**
			BOLETO IMPRESSO
			**/
			if($transactionState == 3){
			
				$this->enviarEmailMudancaDeStatus($userEmail, $checkout[0], $paymentState['PaymentState']['name']);
				$msg = "Sua compra está ".$paymentState['PaymentState']['name'];
				$this->sendMobileNotification($checkout[0]['checkouts']['user_id'], $msg);
			
			}
			
			/**
			EM ANALISE
			**/
			if($transactionState == 6){
				$this->enviarEmailMudancaDeStatus($userEmail, $checkout[0], $paymentState['PaymentState']['name']);
				$msg = "Sua compra está ".$paymentState['PaymentState']['name'];
				$this->sendMobileNotification($checkout[0]['checkouts']['user_id'], $msg);
			}
			
			
			/**
			ESTORNADO
			**/
			if($transactionState == 7){
				$this->enviarEmailMudancaDeStatus($userEmail, $checkout[0], $paymentState['PaymentState']['name']);
				$msg = "Sua compra está ".$paymentState['PaymentState']['name'];
				$this->sendMobileNotification($checkout[0]['checkouts']['user_id'], $msg);
			}
			
			/**
			EM REVISAO
			**/
			if($transactionState == 8){
				$this->enviarEmailMudancaDeStatus($userEmail, $checkout[0], $paymentState['PaymentState']['name']);
				$msg = "Sua compra está ".$paymentState['PaymentState']['name'];
				$this->sendMobileNotification($checkout[0]['checkouts']['user_id'], $msg);
			}
			
			/**
			REEMBOLSADO
			**/
			if($transactionState == 9){
				$this->enviarEmailMudancaDeStatus($userEmail, $checkout[0], $paymentState['PaymentState']['name']);
				$msg = "Sua compra está ".$paymentState['PaymentState']['name'];
				$this->sendMobileNotification($checkout[0]['checkouts']['user_id'], $msg);
			}
			
			/**
			INICIO DA TRANSACAO
			**/
			if($transactionState == 14){
				$this->enviarEmailMudancaDeStatus($userEmail, $checkout[0], $paymentState['PaymentState']['name']);
				$msg = "Sua compra está ".$paymentState['PaymentState']['name'];
				$this->sendMobileNotification($checkout[0]['checkouts']['user_id'], $msg);
			}

			//$this->sendPublicMobileNotification('O Status da sua compra mudou!!!');
        }
    }
	

    public function sendEmailChangeState($userEmail, $checkout, $newStatus) {
        $mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = '09#pLk#3}KgS'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = utf8_decode("Mudança de Status na compra - Jezzy"); // Seu nome

        $mail->AddAddress("{$userEmail}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->Subject = "{$newStatus}"; // Assunto da mensagem
        $mail->Body = utf8_decode("Ola,  Jezzy gostaria de informar que houve uma mudança de status na sua compra <i>{$checkout['offers']['title']}</i> 
 <br/> Status Atual da sua compra: <strong>{$newStatus}</strong> ");
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
    }

    public function enviarEmailPagamentoAprovado($userEmail, $checkout, $status) {
	
        /*$mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = '09#pLk#3}KgS'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = utf8_decode("Mudança de Status na compra - Jezzy"); // Seu nome

        $mail->AddAddress("{$userEmail}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto) */
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

		$endereco = '';
		if($checkout['checkouts']['company_id'] != 99999){
				$endereco = "Retirar o produto no Salão.";
		}else{

							$endereco = $checkout["checkouts"]["address"] . ', ' . $checkout["checkouts"]["number"] . ' - ' . $checkout["checkouts"]["complement"] . ' - ' . $checkout["checkouts"]["district"] . '<br/>
                            CEP ' . $checkout["checkouts"]["zip_code"] . ' - ' . $checkout["checkouts"]["city"] . ' - ' . $checkout["checkouts"]["state"];
							
		}

        $emailBody = '<table border="0" cellpadding="0" cellspacing="0"  style="background: #f2f2f2;">
            <tr>
                <td colspan="3"><img src="https://secure.jezzy.com.br/uploads/Emails/files/pagamento-aprovado/00.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="background: #f2f2f2;">
                <td colspan="4" style=" padding-left: 20px;">
                    <span style="color: #2597AC; font-size: 16px;  font-family: Helvetica, Arial, sans-serif;">
                        <br/>
                        <b><i>Caro Sr(a). ' . $checkout["users"]["name"] . ' </i></b>
                        <br/>
                    </span>
                </td>
            </tr>
            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 20px;">
                    <span>
                        <br/>
                        <b>Seu pedido foi recebido com sucesso! </b><br/>
                        Confira as informações abaixo que constam no seu pedido <b>nº ' . $checkout["checkouts"]["id"] . '</b><br/>
                        realizado em <b>10/02/15 às 18:45 foi ' . $status . ' pela instituição financeira!</b><br/>

                        <b>Detalhes do pedido:</b><br/>
                        Forma de Pagamento: Mastercard <br/>
                        Parcelas: ' . $checkout["checkouts"]["installment"] . ' vez(es) - R$ ' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . ' <br/>
                        Total Frete: R$ ' . str_replace(".", ",", $checkout["checkouts"]["shipping_value"]) . ' <br/>
                        Total Descontos: R$ 00,00<br/>
                        Total Pedido: R$ ' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . '
                    </span>
                </td>
            </tr>

            <tr style="background: #f2f2f2; text-align: center; ">
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/pagamento-aprovado/01.jpg" width="600" style="vertical-align: bottom;"/>
                    <br/><br/>
                </td>
            </tr>

            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; padding-top: 35px;">
                <td style="width: 150px; text-align: center;">
                    ' . $checkout["offers"]["title"] . '
                </td>
                <td  style="text-align: center; font-size: 10px; width: 300px;">' . $checkout["checkouts"]["amount"] . ' unidade<br/><hr/></td>
                <td  style="width: 150px; text-align: center;">
                    <b>R$' . str_replace(".", ",", $checkout["checkouts"]["unit_value"]) . '</b>
                </td>
            </tr>
			
			 <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; padding-top: 35px;">
                <td style="width: 150px; text-align: center;">
                   Frete
                </td>
                <td  style="text-align: center; font-size: 10px; width: 300px;"><br/><hr/></td>
                <td  style="width: 150px; text-align: center;">
                    <b>R$' . str_replace(".", ",", $checkout["checkouts"]["shipping_value"]) . '</b>
                </td>
            </tr>
            
            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B;">
                <td style="width: 150px; text-align: center;">

                </td>
                <td  style="text-align: center; font-size: 10px; width: 300px;"><br/></td>
                <td  style="width: 150px; text-align: center;">
                    <b><span style="font-size: 20px;">R$' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . '</span></b>
                </td>
            </tr>

            <tr style="background: #f2f2f2; text-align: center; ">
                <td colspan="4">
                    <br/>
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/pagamento-aprovado/02.jpg" width="600" style="vertical-align: bottom;"/>
                    <br/>
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 14px;">
                    <span>
                        O endereço de entrega informado/selecionado por você foi:<br/><br/>

                        <b>
                            ' . $endereco . '
                        </b>
                    </span>
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 14px;">
                    <br/><br />
                    <span>
                        <b>ORIENTAÇÕES IMPORTANTES!</b>
                    </span>
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 12px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; width: 420px; text-align: justify; padding-left: 20px; padding-right: 20px;">
                    <span>
                        - A entrega será realizada pela ' . $checkout["companies"]["fancy_name"] . ' ou por uma transportadora designada por ela. Se tiver dúvidas, por favor, entre em contato com a Empresa ' . $checkout["companies"]["fancy_name"] . ';' .
                '<br/> - Se seu pedido possui mais de um item, estes podem ser enviados separadamente, de acordo com a disponibilidade do estoque da Empresa ' . $checkout["companies"]["fancy_name"] . ';
                        <br/> - Se a entrega apresentar divergências como embalagem aberta ou avariada, falta de acessórios, ou produto em desacordo com o solicitado, recuse a entrega e contate imediatamente nossa Central de Atendimento;
                        <br/> - Juntamente com o seu pedido você deverá receber uma representação simplificada da NF-e a DANFE (Documento Auxiliar da Nota Fiscal Eletrônica). Na DANFE existe uma chave numérica (chave de acesso) de cada pedido, e com ela você poderá consultar e até mesmo imprimir uma versão de sua Nota Fiscal eletrônica no site do Ministério da Fazenda;
                        <br/> - Se precisar trocar ou devolver um produto, entre em contato com a Empresa ' . $checkout["companies"]["fancy_name"] . ' através dos dados abaixo ou com nossa Central de Atendimento.
                    </span>
                </td>

            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 12px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 14px; width: 600px;">
                    <br/><br/>
                    <span>
                        <b>
                            Empresa ' . $checkout["companies"]["fancy_name"] . '<br/>
                            ' . $checkout["companies"]["address"] . ',  ' . $checkout["companies"]["number"] . '<br/>
                             ' . $checkout["companies"]["district"] . ' -  ' . $checkout["companies"]["city"] . ' -  ' . $checkout["companies"]["state"] . '<br/>
                            CEP  ' . $checkout["companies"]["zip_code"] . '<br/>
                            Telefone:  ' . $checkout["companies"]["phone"] . '                           
                        </b>
                    </span>
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 12px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; width: 420px; text-align: justify; padding-left: 20px; padding-right: 20px;">
                    <br/><br/>
                    <span>
                        A qualquer momento, você pode acompanhar o andamento do seu pedido pelo aplicativo Jezzy através do histórico de Compras. <b>Clique aqui</b> para verificar agora.<br/><br/> Se precisar, entre em contato com nossa central de atendimento.De segunda a sexta das 8 às 18h.
                    </span>
                </td>
            </tr>
        </table>

        <table border="0" cellpadding="0" cellspacing="0"  style="background: #f2f2f2;">
            <tr style="background: #f7f7f7;">
                <td colspan="4">
                    <br/>
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/04.jpg" width="732"/>
                    <br/>
                </td>
            </tr>
            <tr style="background: #f7f7f7; width: 600px;">
                <td style="width: 50px;" colspan="1">
                </td>
                <td style="width: 150px; text-align: right;" colspan="1">
                    <a href="#"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/App%20Store.png" width="80"/></a>
                </td>
                <td style="width: 150px; text-align: left;" colspan="1">
                    <a href="#"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/Google%20Play.png" width="80"/></a>
                </td>
                <td style="width: 50px;" colspan="1">
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/05-1.jpg" width="750" height="30" style="vertical-align: bottom;"/>
                </td>
            </tr>
           
            <tr>
                <td colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/07.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td  colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/08.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td colspan="1"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/09.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/10.jpg" width="151" style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/11.jpg" width="732" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg" width="732" style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>
';

		$this->sendEmailByPOST($emailBody, $userEmail, "Pagamento Aprovado");
       /* $mail->Subject = "Pagamento Aprovado"; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments(); */
    }

    public function enviarEmailAgendamentoCancelado($email) {
       /*$mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = '09#pLk#3}KgS'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = "Contato - Jezzy"; // Seu nome

        $mail->AddAddress("{$email}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto) */


        $emailBody = ' <table border="0" cellpadding="0" cellspacing="0"  style="background: #f2f2f2;">
            <tr>
                <td colspan="4"><img src="files/agendamento-cancelado/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4"><img src="files/agendamento-cancelado/02.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4"><img src="files/agendamento-cancelado/03.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="color: #9b9b9b;  font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                <td style="width: 150px; text-align: right;">Corte de Cabelo</td>
                <td style="text-align: center;">20/04/2016</td>
                <td style="width: 170px;">12:00</td>
                <td style="width: 150px;">Januaria</td>
            </tr>
            <tr style="color: #9b9b9b;  font-family: Helvetica, Arial, sans-serif; font-size: 14px;">
                <td style="width: 150px; text-align: center;" colspan="4">
                    <br/><br/><br/>
                    <span><b>Salão Beauty Hair</b></span><br/>
                    <span>
                        Rua Cubatão, 411 - Bairro Paraiso - CEP 01234-500 - São Paulo - SP<br/>
                        Telefone: 11 9898-9898 - Horário de Funcionamento: Ter - Sáb das 09 às 20hrs
                    </span>
                    <br/><br/><br/>
                </td>
            </tr>
            <tr>
                <td colspan="4"><img src="files/agendamento-cancelado/04.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
             <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg"  style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>';

		
		$this->sendEmailByPOST($emailBody, $email, "Agendamento cancelado");
		
        /*$mail->Subject = "Agendamento cancelado"; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments(); */
    }

    public function enviaremailStatusRecebemosPedido($email, $checkout) {
       /*$mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = '09#pLk#3}KgS'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = "Contato - Jezzy"; // Seu nome

        $mail->AddAddress("{$email}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto) */


        $emailBody = '<table border="0" cellpadding="0" cellspacing="0"  >
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
           
            <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/02.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
             <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-recebemos-pedido/03.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 14px;  color: #9B9B9B;  background: #f2f2f2; text-align: center;">
                <td colspan="4">
                    <br/><br/>
                    Recebemos o seu pedido número ' . $checkout["checkouts"]["id"] . ' e aguardamos a confirmação do pagamento. <br/>
                    <b>Prazo de Entrega:</b> Tem inicio após a confirmação do pagamento pela administradora<br/>
                    do cartão de crédito.
                    <br/><br/><br/>
                </td>
            </tr>
            
             <tr style="">
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/ImgsEmail/products.jpg" width="800" style="vertical-align: bottom;"/>
                   
                </td>
            </tr>

            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; padding-top: 35px; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">
                    ' . $checkout["offers"]["title"] . '
                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;">' . $checkout["checkouts"]["amount"] . '<br/><hr/></td>
                <td  style="width: 200px; text-align: center;">
                    <b>R$' . str_replace(".", ",", $checkout["offers"]["value"]) . '</b>
                </td>
            </tr>
            
            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">

                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;"><br/></td>
                <td  style="width: 200px; text-align: center;">
                    <b><span style="font-size: 20px;">R$' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . '</span></b>
                </td>
            </tr>

            <tr >
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/pagamento-aprovado/02.jpg" width="800" style="vertical-align: bottom;"/>
                    
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 40px;">
                    <span>
                        O endereço de entrega informado/selecionado por você foi:<br/><br/>

                        <b> <br/>
                           ' . $checkout["checkouts"]["address"] . ',  ' . $checkout["checkouts"]["number"] . ' -  ' . $checkout["checkouts"]["complement"] . ' -  ' . $checkout["checkouts"]["district"] . 'a<br/>
                            CEP  ' . $checkout["checkouts"]["zip_code"] . ' -  ' . $checkout["checkouts"]["city"] . ' -  ' . $checkout["checkouts"]["state"] . '
                        </b>
                    </span>
                </td>
            </tr>
              <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-recebemos-pedido/06.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td  style="width: 200px;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/07.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  colspan="1"  style="width: 100px; background: red;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/08.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  style="width: 200px;"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/09.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  style="width: 200px;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/10.jpg" width="200" style="vertical-align: bottom;"/></td>
            </tr>
            
              <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/11.jpg" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src=https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg"  style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>';

       
	   $this->sendEmailByPOST($emailBody, $email, "Recebemos o Seu Pedido");
	   
	   /* $mail->Subject = "Recebemos o Seu Pedido"; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments(); */
    }

    public function enviarEmailStatusAguardandoPagamento($email, $checkout) {

      /*  $mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = '09#pLk#3}KgS'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = "Contato - Jezzy"; // Seu nome

        $mail->AddAddress("{$email}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto) */


        $emailBody = '<table border="0" cellpadding="0" cellspacing="0"  >
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
           
            <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/02.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
             <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/02%20-%20Status.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/03.jpg" width="800" style="vertical-align: bottom;"/></td>
            </tr>
            
             <tr style="">
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/ImgsEmail/products.jpg" width="800" style="vertical-align: bottom;"/>
                   
                </td>
            </tr>

           <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; padding-top: 35px; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">
                    ' . $checkout["offers"]["title"] . '
                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;">' . $checkout["checkouts"]["amount"] . '<br/><hr/></td>
                <td  style="width: 200px; text-align: center;">
                    <b>R$' . str_replace(".", ",", $checkout["offers"]["value"]) . '</b>
                </td>
            </tr>
            
            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">

                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;"><br/></td>
                <td  style="width: 200px; text-align: center;">
                    <b><span style="font-size: 20px;">R$' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . '</span></b>
                </td>
            </tr>

            <tr >
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/pagamento-aprovado/02.jpg" width="800" style="vertical-align: bottom;"/>
                    
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 40px;">
                    <span>
                        O endereço de entrega informado/selecionado por você foi:<br/><br/>

                      <b> <br/>
                           ' . $checkout["checkouts"]["address"] . ',  ' . $checkout["checkouts"]["number"] . ' -  ' . $checkout["checkouts"]["complement"] . ' -  ' . $checkout["checkouts"]["district"] . 'a<br/>
                            CEP  ' . $checkout["checkouts"]["zip_code"] . ' -  ' . $checkout["checkouts"]["city"] . ' -  ' . $checkout["checkouts"]["state"] . '
                        </b>
                    </span>
                </td>
            </tr>
            
            <tr>
                <td  style="width: 200px;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/07.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  colspan="1"  style="width: 100px; background: red;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/08.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  style="width: 200px;"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/09.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  style="width: 200px;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/10.jpg" width="200" style="vertical-align: bottom;"/></td>
            </tr>
            
              <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/11.jpg" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg"  style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>';

		
		$this->sendEmailByPOST($emailBody, $email, "Aguardando Pagamento");
		
        /* $mail->Subject = "Aguardando Pagamento"; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments(); */
    }

    public function enviarEmailStatusPreparandoEnvio($email, $checkout) {

        /*$mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = '09#pLk#3}KgS'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = "Contato - Jezzy"; // Seu nome

        $mail->AddAddress("{$email}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto) */

		$endereco = '';
		if($checkout['checkouts']['company_id'] != 99999){
				$endereco = "Retirar o produto no Salão.";
		}else{

							$endereco = $checkout["checkouts"]["address"] . ', ' . $checkout["checkouts"]["number"] . ' - ' . $checkout["checkouts"]["complement"] . ' - ' . $checkout["checkouts"]["district"] . '<br/>
                            CEP ' . $checkout["checkouts"]["zip_code"] . ' - ' . $checkout["checkouts"]["city"] . ' - ' . $checkout["checkouts"]["state"];
							
		}


        $emailBody = ' <table border="0" cellpadding="0" cellspacing="0"  >
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
           
            <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/02.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
             <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-preparado-para-envio/02-%20status.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
              <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 14px;  color: #9B9B9B;  background: #f2f2f2; text-align: center;">
                <td colspan="4">
                    <br/><br/>
                    Seu pedido '.$checkout["checkouts"]["id"] .' está sendo preparado para envio!<br/>
                    Esperamos que esteja satisfeito com nossos serviços e que aproveite sua aquisição.
                    <br/><br/><br/>
                </td>
            </tr>
            
             <tr style="">
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/ImgsEmail/products.jpg" width="800" style="vertical-align: bottom;"/>
                   
                </td>
            </tr>

            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; padding-top: 35px; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">
                    ' . $checkout["offers"]["title"] . '
                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;">' . $checkout["checkouts"]["amount"] . '<br/><hr/></td>
                <td  style="width: 200px; text-align: center;">
                    <b>R$' . str_replace(".", ",", $checkout["checkouts"]["unit_value"]) . '</b>
                </td>
            </tr>
			
			<tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; padding-top: 35px; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">
                    Frete
                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;"><br/><hr/></td>
                <td  style="width: 200px; text-align: center;">
                    <b>R$' . str_replace(".", ",", $checkout["checkouts"]["shipping_value"]) . '</b>
                </td>
            </tr>
            
            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">

                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;"><br/></td>
                <td  style="width: 200px; text-align: center;">
                    <b><span style="font-size: 20px;">R$' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . '</span></b>
                </td>
            </tr>

            <tr >
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/pagamento-aprovado/02.jpg" width="800" style="vertical-align: bottom;"/>
                    
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 40px;">
                    <span>
                        O endereço de entrega informado/selecionado por você foi:<br/><br/>

                       <b> <br/>
                           ' . $endereco . '
                        </b>
                    </span>
                </td>
            </tr>
            
            <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-preparado-para-envio/06.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
            <tr>
                <td  style="width: 200px;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/07.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  colspan="1"  style="width: 100px; background: red;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/08.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  style="width: 200px;"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/09.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  style="width: 200px;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/10.jpg" width="200" style="vertical-align: bottom;"/></td>
            </tr>
            
              <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/11.jpg" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg"  style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>';

		$this->sendEmailByPOST($emailBody, $email, "Preparando Envio");
		
      /*  $mail->Subject = "Preparando Envio"; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments(); */
    }

     public function enviarEmailStatusRetirarProdutoDaEmpresa($email, $checkout) {



							$endereco = $checkout["companies"]["address"] . ', ' . $checkout["companies"]["number"] . ' - ' . $checkout["companies"]["complement"] . ' - ' . $checkout["companies"]["district"] . '<br/>
                            CEP ' . $checkout["companies"]["zip_code"] . ' - ' . $checkout["companies"]["city"] . ' - ' . $checkout["companies"]["state"];
							
	


        $emailBody = ' <table border="0" cellpadding="0" cellspacing="0"  >
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
           
            <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/02.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
             <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/statuscomimagensalteradas/produto-salao-retirar_03.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
              <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 14px;  color: #9B9B9B;  background: #f2f2f2; text-align: center;">
                <td colspan="4">
                    <br/><br/>
                    Seu pedido '.$checkout["checkouts"]["id"] .' está pronto para ser retirado!<br/>
                    Esperamos que esteja satisfeito com nossos serviços e que aproveite sua aquisição.
                    <br/><br/><br/>
                </td>
            </tr>
            
             <tr style="">
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/ImgsEmail/products.jpg" width="800" style="vertical-align: bottom;"/>
                   
                </td>
            </tr>

            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; padding-top: 35px; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">
                    ' . $checkout["offers"]["title"] . '
                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;">' . $checkout["checkouts"]["amount"] . '<br/><hr/></td>
                <td  style="width: 200px; text-align: center;">
                    <b>R$' . str_replace(".", ",", $checkout["checkouts"]["unit_value"]) . '</b>
                </td>
            </tr>
            
            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">

                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;"><br/></td>
                <td  style="width: 200px; text-align: center;">
                    <b><span style="font-size: 20px;">R$' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . '</span></b>
                </td>
            </tr>

            <tr >
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/statuscomimagensalteradas/produto-salao-retirar_05.jpg" width="800" style="vertical-align: bottom;" />
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 40px;">
                    <span>
                        O(s) produto(s) deve(m) ser retirado(s) no próprio salão: <b>'.$checkout["companies"]["fancy_name"].'</b><br/><br/>

                       <b> <br/>
                           ' . $endereco . '
                        </b>
                    </span>
                </td>
            </tr>
			
			<tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 40px;">
                    <span>
                                      Dias e Horários de funcionamento<br/>
				'.$checkout["companies"]["work_days"].' das '.$checkout["companies"]["open_hour"].' as '.$checkout["companies"]["close_hour"].'
                    </span>
                </td>
            </tr>

            
            <tr>
                <td  style="width: 200px;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/07.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  colspan="1"  style="width: 100px; background: red;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/08.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  style="width: 200px;"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/09.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  style="width: 200px;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/10.jpg" width="200" style="vertical-align: bottom;"/></td>
            </tr>
            
              <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/11.jpg" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg"  style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>';

		$this->sendEmailByPOST($emailBody, $email, "Retirar produto no Salão");
		
      /*  $mail->Subject = "Preparando Envio"; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments(); */
    }
	
	
	  public function enviarEmailStatusServicoDaEmpresaVoucherDisponivel($email, $checkout) {



							$endereco = $checkout["companies"]["address"] . ', ' . $checkout["companies"]["number"] . ' - ' . $checkout["companies"]["complement"] . ' - ' . $checkout["companies"]["district"] . '<br/>
                            CEP ' . $checkout["companies"]["zip_code"] . ' - ' . $checkout["companies"]["city"] . ' - ' . $checkout["companies"]["state"];
							
	


        $emailBody = ' <table border="0" cellpadding="0" cellspacing="0"  >
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
           
            <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/02.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
             <tr>
                 <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/statuscomimagensalteradas/servicos-salao-voucher_03.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            
              <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 14px;  color: #9B9B9B;  background: #f2f2f2; text-align: center;">
                <td colspan="4">
                    <br/><br/>
                    Seu pedido '.$checkout["checkouts"]["id"] .' foi aprovado!<br/>
                    Esperamos que esteja satisfeito com nossos serviços e que aproveite sua aquisição.
                    <br/><br/><br/>
                </td>
            </tr>
            
             <tr style="">
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/ImgsEmail/services.jpg" width="800" style="vertical-align: bottom;"/>
                   
                </td>
            </tr>

            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; padding-top: 35px; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">
                    ' . $checkout["offers"]["title"] . '
                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;"><br/><hr/></td>
                <td  style="width: 200px; text-align: center;">
                    <b>R$' . str_replace(".", ",", $checkout["checkouts"]["unit_value"]) . '</b>
                </td>
            </tr>
            
            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; background: #f2f2f2;">
                <td style="width: 200px; text-align: center;">

                </td>
                <td colspan="2" style="text-align: center; font-size: 10px; width: 400px;"><br/></td>
                <td  style="width: 200px; text-align: center;">
                    <b><span style="font-size: 20px;">R$' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . '</span></b>
                </td>
            </tr>

            <tr >
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/statuscomimagensalteradas/servicos-salao-voucher_05.jpg" width="800" style="vertical-align: bottom;" />
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 40px;">
                    <span>
                        Acesse o aplicativo na opção "<b>Meus Vouchers</b>" para realizar o agendamento do Serviço adquirido.
                    </span>
                </td>
            </tr>
			

            
            <tr>
                <td  style="width: 200px;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/07.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  colspan="1"  style="width: 100px; background: red;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/08.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  style="width: 200px;"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/09.jpg" width="200" style="vertical-align: bottom;"/></td>
                <td  style="width: 200px;"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/10.jpg" width="200" style="vertical-align: bottom;"/></td>
            </tr>
            
              <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/11.jpg" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg"  style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>';

		$this->sendEmailByPOST($emailBody, $email, "Voucher disponível no Aplicativo");
		
      /*  $mail->Subject = "Preparando Envio"; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments(); */
    }
	
	public function enviarEmailVoucher($email, $checkout, $voucher) {

       /* $mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = '09#pLk#3}KgS'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = "Voucher - Jezzy"; // Seu nome

        $mail->AddAddress("{$email}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto) */


        $emailBody = ' <table border="0" cellpadding="0" cellspacing="0"  style="background: #f2f2f2;">
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/voucher/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="width: 800px; height: 452px; background: url(https://secure.jezzy.com.br/uploads/Emails/files/voucher/02.jpg);">
                <td colspan="4" style="color: #fff;  font-family: Helvetica, Arial, sans-serif; font-size: 16px;">

                    <span style="text-align: center; margin-left: 400px;">
                        CÓDIGO<br/>
                    </span>
                    <span style="text-align: center; margin-left: 380px;">
                        DO VOUCHER<BR/>
                    </span>
                    <span style="text-align: center; margin-left: 420px;">
                        ' . $voucher["services_vouchers"]["id"] . '
                    </span>


                </td>
            </tr>
            <tr style="background: url(https://secure.jezzy.com.br/uploads/Emails/files/voucher/03-0.jpg); background-size: 800px 39px; height: 39px; color: #fff;  font-family: Helvetica, Arial, sans-serif; font-size: 14px;" >
                <td colspan="1" style="width: 190px; text-align: right;">' . $voucher["subclasses"]["name"] . '</td>
                <td colspan="1" style="width: 200px; "><span style="margin-left: 80px;">20/04/2016</span></td>
                <td colspan="1" style="width: 200px;  text-align: center;">12:00</td>
                <td colspan="1" style="width: 200px; "><span style="margin-left: 30px;">Karina Santos</span></td>
            </tr>

            <tr style="background: url(https://secure.jezzy.com.br/uploads/Emails/files/voucher/03-1.jpg); " >

                <td colspan="4" style="text-align: center; color: #fff;  font-family: Helvetica, Arial, sans-serif; font-size: 14px;">
                    <br/><br/>
                    <b>' . $checkout["companies"]["fancy_name"] . '</b><br />
                    ' . $checkout["companies"]["address"] . ', ' . $checkout["companies"]["number"] . ' - ' . $checkout["companies"]["district"] . ' - CEP ' . $checkout["companies"]["zip_code"] . ' - ' . $checkout["companies"]["city"] . ' - ' . $checkout["companies"]["state"] . ' <br/>
                    Telefone: ' . $checkout["companies"]["phone"] . ' - Horário de Funcionamento: ' . $checkout["companies"]["work_days"] . ' das 09 às 20hrs <br/><br/><br/>

                    <b>Dados da aquisição:</b><br/>
                    Forma de Pagamento: Cartão de Crédito/Boleto Bancário<br/>
                    Número de Pedido: ' . $checkout["companies"]["id"] . '<br/>
                    Data da Compa: ' . date("d/m/Y", strtotime($checkout["checkouts"]["date"])) . ' <br/><br/><br/>

                    <b>Observações:</b><br/>
                    <span style="font-size: 12px;">Cupom não cumulativo. Uso apenas para compras realizadas pela aplicação. Válido até ' . date("d/m/Y", strtotime($checkout["offers"]["ends_at"])) . '</span>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/voucher/03-2.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/voucher/04.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
        </table>';

		
		$this->sendEmailByPOST($emailBody, $email, "Seu voucher");
		
        /* $mail->Subject = "Seu voucher"; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments(); */
        
        
    }
			
	public function sendResumeEmail($checkId){
			
				//$this->autoRender = false;
			
				$sql = "SELECT * FROM checkouts INNER JOIN offers on offers.id = checkouts.offer_id INNER JOIN payment_methods ON payment_methods.id = checkouts.payment_method_id INNER JOIN users on users.id = checkouts.user_id WHERE checkouts.id = ".$checkId.";";
				 $params = array(
                'User' => array(
                    'query' => $sql
                )
            );
            $checkout = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
			$checkout = $checkout[0];
			
			$emailBody = "<div style='font-family: COURIER NEW; color: #000000;'><b><h3>COMPRA {$checkout['checkouts']['id']}</h3></b>
			----<br/>
			<b>DADOS DO COMPRADOR </b><br/><br/>
			<b>Nome .....:</b>{$checkout['users']['name']} <br />
			<b>CPF  ......:</b> {$checkout['users']['cpf']}<br/>
			<b>Logradouro:</b> {$checkout['checkouts']['address']}<br/>
			<b>Número ...:</b> {$checkout['checkouts']['number']}<br/>
			<b>Bairro ...:</b> {$checkout['checkouts']['district']}<br/>
			<b>CEP ......:</b> {$checkout['checkouts']['zip_code']}<br/>
			<b>Cidade ...:</b> {$checkout['checkouts']['city']}<br/>
			<b>Estado ...:</b> {$checkout['checkouts']['state']}<br/>
			<br/>----<br/>
			<b>DADOS DA COMPRA</b><br/><br/>
			<b>Data ...........: </b>{$checkout['checkouts']['date']} <br />
			<b>Número do pedido: </b>{$checkout['checkouts']['id']} <br />
			<b>SKU ............: </b>{$checkout['offers']['SKU']} <br />
			<b>Quantidade .....: </b> {$checkout['checkouts']['amount']} <br />
			<b>Produto ........: </b>{$checkout['offers']['title']} , {$checkout['offers']['brand']} - {$checkout['offers']['line']}<br />
			<b>Valor do Produto: </b>{$checkout['offers']['value']} <br />
			<b>Valor fo Frete .: </b>{$checkout['checkouts']['shipping_value']} <br />
			<b>Valor total  ....: </b>{$checkout['checkouts']['total_value']} <br />
			<br/>----<br/>
			<b>DADOS DA COMPRA</b><br/><br/>
			<b>Forma de Entrega : </b> <br />
			<b>Dias para entrega: </b>{$checkout['checkouts']['delivery_time']} <br />
			<br/>----<br/>
			<b>DADOS DO PAGAMENTO</b><br/><br />
			<b>Forma de Entrega  .: </b> {$checkout['payment_methods']['type']} - {$checkout['payment_methods']['name']}  <br />
			<b>Cód. Trasação MoIP: </b>{$checkout['checkouts']['transaction_moip_code']} <br />
			</div>";
			
			 $mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br';  // Usuário do servidor SMTP
        $mail->Password = '09#pLk#3}KgS'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = "Jezzy Pedido"; // Seu nome

        $mail->AddAddress("contato@jezzy.com.br");
		$mail->AddAddress("jezzyapp@gmail.com");
		$mail->AddAddress("zsulsp@terra.com.br");
		$mail->AddAddress("mtduque@gmail.com");
		  

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
			
			  $mail->Subject = "Jezzy Pedido"; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
			print_r($enviado);
			}
			
	public function enviarEmailPedidoCancelado($userEmail, $checkout, $status) {
      /*  $mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = '09#pLk#3}KgS'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = utf8_decode("Mudança de Status na compra - Jezzy"); // Seu nome

        $mail->AddAddress("{$userEmail}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto) */
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

        $emailBody = '<table border="0" cellpadding="0" cellspacing="0"  style="background: #f2f2f2;">
            <tr>
                <td colspan="3"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="background: #f2f2f2;">
                <td colspan="4" style=" padding-left: 20px;">
                    <span style="color: #2597AC; font-size: 16px;  font-family: Helvetica, Arial, sans-serif;">
                        <br/>
                        <b><i>Caro Sr(a). ' . $checkout["users"]["name"] . ' </i></b>
                        <br/>
                    </span>
                </td>
            </tr>
            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 20px;">
                    <span>
                        <br/>
                        <b>Seu pedido foi cancelado! </b><br/>
                        Confira as informações abaixo que constam no seu pedido <b>nº ' . $checkout["checkouts"]["id"] . '</b><br/>
                        realizado em <b>10/02/15 às 18:45 foi ' . $status . ' pela instituição financeira!</b><br/>

                        <b>Detalhes do pedido:</b><br/>
                        Forma de Pagamento: Mastercard <br/>
                        Parcelas: ' . $checkout["checkouts"]["installment"] . ' vez(es) - R$ ' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . ' <br/>
                        Total Frete: R$ ' . str_replace(".", ",", $checkout["checkouts"]["shipping_value"]) . ' <br/>
                        Total Descontos: R$ 00,00<br/>
                        Total Pedido: R$ ' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . '
                    </span>
                </td>
            </tr>

            <tr style="background: #f2f2f2; text-align: center; ">
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/pagamento-aprovado/01.jpg" width="600" style="vertical-align: bottom;"/>
                    <br/><br/>
                </td>
            </tr>

            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; padding-top: 35px;">
                <td style="width: 150px; text-align: center;">
                    ' . $checkout["offers"]["title"] . '
                </td>
                <td  style="text-align: center; font-size: 10px; width: 300px;">' . $checkout["checkouts"]["amount"] . ' unidade<br/><hr/></td>
                <td  style="width: 150px; text-align: center;">
                    <b>R$' . str_replace(".", ",", $checkout["checkouts"]["unit_value"]) . '</b>
                </td>
            </tr>
            
            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B;">
                <td style="width: 150px; text-align: center;">

                </td>
                <td  style="text-align: center; font-size: 10px; width: 300px;"><br/></td>
                <td  style="width: 150px; text-align: center;">
                    <b><span style="font-size: 20px;">R$200,00</span></b>
                </td>
            </tr>

            <tr style="background: #f2f2f2; text-align: center; ">
                <td colspan="4">
                    <br/>
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/pagamento-aprovado/02.jpg" width="600" style="vertical-align: bottom;"/>
                    <br/>
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 14px;">
                    <span>
                        O endereço de entrega informado/selecionado por você foi:<br/><br/>

                        <b>
                            ' . $checkout["checkouts"]["address"] . ', ' . $checkout["checkouts"]["number"] . ' - ' . $checkout["checkouts"]["complement"] . ' - ' . $checkout["checkouts"]["district"] . '<br/>
                            CEP ' . $checkout["checkouts"]["zip_code"] . ' - ' . $checkout["checkouts"]["city"] . ' - ' . $checkout["checkouts"]["state"] . '
                        </b>
                    </span>
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 12px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 14px; width: 600px;">
                    <br/><br/>
                    <span>
                        <b>
                            Empresa ' . $checkout["companies"]["fancy_name"] . '<br/>
                            ' . $checkout["companies"]["address"] . ',  ' . $checkout["companies"]["number"] . '<br/>
                             ' . $checkout["companies"]["district"] . ' -  ' . $checkout["companies"]["city"] . ' -  ' . $checkout["companies"]["state"] . '<br/>
                            CEP  ' . $checkout["companies"]["zip_code"] . '<br/>
                            Telefone:  ' . $checkout["companies"]["phone"] . '                           
                        </b>
                    </span>
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 12px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; width: 420px; text-align: justify; padding-left: 20px; padding-right: 20px;">
                    <br/><br/>
                    <span>
                        <br/> Se precisar, entre em contato com nossa central de atendimento.De segunda a sexta das 8 às 18h.
                    </span>
                </td>
            </tr>
        </table>

        <table border="0" cellpadding="0" cellspacing="0"  style="background: #f2f2f2;">
            <tr style="background: #f7f7f7;">
                <td colspan="4">
                    <br/>
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/04.jpg" width="732"/>
                    <br/>
                </td>
            </tr>
            <tr style="background: #f7f7f7; width: 600px;">
                <td style="width: 50px;" colspan="1">
                </td>
                <td style="width: 150px; text-align: right;" colspan="1">
                    <a href="#"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/App%20Store.png" width="80"/></a>
                </td>
                <td style="width: 150px; text-align: left;" colspan="1">
                    <a href="#"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/Google%20Play.png" width="80"/></a>
                </td>
                <td style="width: 50px;" colspan="1">
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/05-1.jpg" width="750" height="30" style="vertical-align: bottom;"/>
                </td>
            </tr>
           
            <tr>
                <td colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/07.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td  colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/08.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td colspan="1"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/09.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/10.jpg" width="151" style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/11.jpg" width="732" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg" width="732" style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>
';

			$subject = "Pagamento ".$status;


      /* $mail->Subject = "Pagamento ".$status; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments(); */
    }
		
	public function enviarEmailMudancaDeStatus($userEmail, $checkout, $status) {
        /*$mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = '09#pLk#3}KgS'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = utf8_decode("Mudança de Status na compra"); // Seu nome

        $mail->AddAddress("{$userEmail}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto) */
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

        $emailBody = '<table border="0" cellpadding="0" cellspacing="0"  style="background: #f2f2f2;">
            <tr>
                <td colspan="3"><img src="https://secure.jezzy.com.br/uploads/Emails/files/status-aguardando-pagamento/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="background: #f2f2f2;">
                <td colspan="4" style=" padding-left: 20px;">
                    <span style="color: #2597AC; font-size: 16px;  font-family: Helvetica, Arial, sans-serif;">
                        <br/>
                        <b><i>Caro Sr(a). ' . $checkout["users"]["name"] . ' </i></b>
                        <br/>
                    </span>
                </td>
            </tr>
            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 20px;">
                    <span>
                        <br/>
                        <b>Seu pedido está  '.$status.'! </b><br/>
                        Confira as informações abaixo que constam no seu pedido <b>nº ' . $checkout["checkouts"]["id"] . '</b><br/>
                        realizado em <b>10/02/15 às 18:45 foi ' . $status . ' pela instituição financeira!</b><br/>

                        <b>Detalhes do pedido:</b><br/>
                        Forma de Pagamento: Mastercard <br/>
                        Parcelas: ' . $checkout["checkouts"]["installment"] . ' vez(es) - R$ ' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . ' <br/>
                        Total Frete: R$ ' . str_replace(".", ",", $checkout["checkouts"]["shipping_value"]) . ' <br/>
                        Total Descontos: R$ 00,00<br/>
                        Total Pedido: R$ ' . str_replace(".", ",", $checkout["checkouts"]["total_value"]) . '
                    </span>
                </td>
            </tr>

            <tr style="background: #f2f2f2; text-align: center; ">
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/pagamento-aprovado/01.jpg" width="600" style="vertical-align: bottom;"/>
                    <br/><br/>
                </td>
            </tr>

            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B; padding-top: 35px;">
                <td style="width: 150px; text-align: center;">
                    ' . $checkout["offers"]["title"] . '
                </td>
                <td  style="text-align: center; font-size: 10px; width: 300px;">' . $checkout["checkouts"]["amount"] . ' unidade<br/><hr/></td>
                <td  style="width: 150px; text-align: center;">
                    <b>R$' . str_replace(".", ",", $checkout["checkouts"]["unit_value"]) . '</b>
                </td>
            </tr>
            
            <tr style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;  color: #9B9B9B;">
                <td style="width: 150px; text-align: center;">

                </td>
                <td  style="text-align: center; font-size: 10px; width: 300px;"><br/></td>
                <td  style="width: 150px; text-align: center;">
                    <b><span style="font-size: 20px;">R$200,00</span></b>
                </td>
            </tr>

            <tr style="background: #f2f2f2; text-align: center; ">
                <td colspan="4">
                    <br/>
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/pagamento-aprovado/02.jpg" width="600" style="vertical-align: bottom;"/>
                    <br/>
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 14px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 14px;">
                    <span>
                        O endereço de entrega informado/selecionado por você foi:<br/><br/>

                        <b>
                            ' . $checkout["checkouts"]["address"] . ', ' . $checkout["checkouts"]["number"] . ' - ' . $checkout["checkouts"]["complement"] . ' - ' . $checkout["checkouts"]["district"] . '<br/>
                            CEP ' . $checkout["checkouts"]["zip_code"] . ' - ' . $checkout["checkouts"]["city"] . ' - ' . $checkout["checkouts"]["state"] . '
                        </b>
                    </span>
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 12px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; padding-left: 14px; width: 600px;">
                    <br/><br/>
                    <span>
                        <b>
                            Empresa ' . $checkout["companies"]["fancy_name"] . '<br/>
                            ' . $checkout["companies"]["address"] . ',  ' . $checkout["companies"]["number"] . '<br/>
                             ' . $checkout["companies"]["district"] . ' -  ' . $checkout["companies"]["city"] . ' -  ' . $checkout["companies"]["state"] . '<br/>
                            CEP  ' . $checkout["companies"]["zip_code"] . '<br/>
                            Telefone:  ' . $checkout["companies"]["phone"] . '                           
                        </b>
                    </span>
                </td>
            </tr>

            <tr style="background: #f2f2f2;">
                <td colspan="4" style="font-size: 12px; color: #9B9B9B; font-family: Helvetica, Arial, sans-serif; width: 420px; text-align: justify; padding-left: 20px; padding-right: 20px;">
                    <br/><br/>
                    <span>
                        <br/> Se precisar, entre em contato com nossa central de atendimento.De segunda a sexta das 8 às 18h.
                    </span>
                </td>
            </tr>
        </table>

        <table border="0" cellpadding="0" cellspacing="0"  style="background: #f2f2f2;">
            <tr style="background: #f7f7f7;">
                <td colspan="4">
                    <br/>
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/04.jpg" width="732"/>
                    <br/>
                </td>
            </tr>
            <tr style="background: #f7f7f7; width: 600px;">
                <td style="width: 50px;" colspan="1">
                </td>
                <td style="width: 150px; text-align: right;" colspan="1">
                    <a href="#"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/App%20Store.png" width="80"/></a>
                </td>
                <td style="width: 150px; text-align: left;" colspan="1">
                    <a href="#"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/Google%20Play.png" width="80"/></a>
                </td>
                <td style="width: 50px;" colspan="1">
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/05-1.jpg" width="750" height="30" style="vertical-align: bottom;"/>
                </td>
            </tr>
           
            <tr>
                <td colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/07.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td  colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/08.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td colspan="1"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/09.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/10.jpg" width="151" style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/11.jpg" width="732" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg" width="732" style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>
';

        $subject = "Pagamento ".$status; // Assunto da mensagem
        
		$this->sendEmailByPOST($emailBody, $userEmail, $subject);
		
		/*$mail->Body = utf8_decode($emailBody);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments(); */
    }
		
	public function offerByLimit(){
		$this->autoRender = false;
		
		
		$aPartirDe = $this->request->data['aPartirDe'];
		$quantidade = $this->request->data['quantidade'];
		$userId = $this->request->data['user_id'];
		
		$sql = 'SELECT DISTINCT Offer.id, Offer.title, Offer.company_id, Offer.photo, Offer.percentage_discount, Offer.value, Offer.parcels, Offer.parcels_quantity FROM offers_users RIGHT OUTER JOIN offers Offer ON Offer.id = offers_users.offer_id WHERE offers_users.user_id =  '.$userId.' and Offer.begins_at <= DATE_FORMAT(CURRENT_TIMESTAMP, "%Y-%m-%d") and Offer.company_id != 99999 and Offer.ends_at >= DATE_FORMAT(CURRENT_TIMESTAMP, "%Y-%m-%d") and Offer.status = "ACTIVE"  or  Offer.company_id = 99999 and Offer.begins_at <= DATE_FORMAT(CURRENT_TIMESTAMP, "%Y-%m-%d") and Offer.ends_at >= DATE_FORMAT(CURRENT_TIMESTAMP, "%Y-%m-%d")  and Offer.status = "ACTIVE" and Offer.amount_allowed >=1  ORDER BY Offer.company_id DESC, Offer.id DESC LIMIT '.$aPartirDe.','.$quantidade.';';
		
		
		
		
		   $updateParams = array(
                'User' => array(
                    'query' => $sql
                )
            );
            $statistics = $this->AccentialApi->urlRequestToGetData('users', 'query', $updateParams);
			
			echo json_encode($statistics);
			}
			
			public function solicitationAcceptAssociatedUser(){
			$this->autoRender = false;
			
			$solicitationId = $_GET['solicitationID'];
			
			/**
			GET
			*/
			$sql = "SELECT * FROM associated_user_solicitations  
			inner join associated_users on associated_users.id = associated_user_solicitations.requester_associated_user_id
			WHERE associated_user_solicitations.id = {$solicitationId};";
			
			$param = array(
						'User' => array(
								'query' => $sql
						)
					);
				
			$solicitation = $this->AccentialApi->urlRequestToGetData('users', 'query', $param);
			
			
			/**
			UPDATE
			*/
			$sqlUpdate = "UPDATE associated_user_solicitations SET `status` = 'ACCEPTED'  WHERE id = {$solicitationId} ;";
			$paramUpdate = array(
						'User' => array(
								'query' => $sqlUpdate
						)
					);
				
			$this->AccentialApi->urlRequestToGetData('users', 'query', $paramUpdate);
			
			/**
			VERIFICAMOS SE USUARIO JÁ FOI CRIADO PARA ESSA Empresa
			*/
			$sqlCheckExist = "SELECT * FROM secondary_users WHERE company_id = 
			{$solicitation[0]['associated_user_solicitations']['requester_company_id']} and email LIKE '{$solicitation[0]['associated_users']['email']}';";
			
			$paramExist = array(
						'User' => array(
								'query' => $sqlCheckExist
						)
					);
				
			$assocUser = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramExist);
			
			if(empty($assocUser)){
			
			$sqlCreate = "INSERT INTO secondary_users(`name`, `email`, `password`, `company_id`, `secondary_type_id`, `excluded`, `first_login`)
			VALUES(
			'{$solicitation[0]['associated_users']['name']}',
			'{$solicitation[0]['associated_users']['email']}',
			'{$solicitation[0]['associated_users']['password']}',
			{$solicitation[0]['associated_user_solicitations']['requester_company_id']},
			4,
			0,
			1);
			
			SET @LAST_INSERT_ID = LAST_INSERT_ID();
			
			INSERT INTO secondary_users_commissioning_fees(`secondary_user_id`, `rate_per_company_product`, `rate_per_jezzy_product`, `rate_per_service`)
			VALUES(
			@LAST_INSERT_ID,
			0,
			0,
			0);
			";
			$paramCREATE = array(
						'User' => array(
								'query' => $sqlCreate
						)
					);
				
			$this->AccentialApi->urlRequestToGetData('users', 'query', $paramCREATE);
			}
			
			$this->redirect(array('controller' => 'Utils', 'action' => 'welcomeAssociated'));
		}
		
		
		public function welcomeAssociated(){
		
			
			
		}
		
		
		/**
		SCHEDULES SOLICITATION VALIDATION
		Essa rotina é responsável por capturar todas as solicitações de agendamento que já passaram do horário solicitado e não foram respondidas.
		Transformamos essas solicitações em REJEITADAS e notificamos o cliente.
		*/
		public function schedulesSolicitationValidationCRON(){
			$this->autoRender = false;
			
			$hour = date("H:i");
			$sql = "select * from schedules_solicitation where date = CURDATE() and time_begin <= '{$hour}' and status = 'WAITING_COMPANY_RESPONSE';";
			     $params = array(
            'Service' => array(
                'query' => $sql
            )
        );
        
		$solicitations = $this->AccentialApi->urlRequestToGetData('Services', 'query', $params);
		
		//Caso existam solicitações para serem mudadas, faremos isso agora
		if(!empty($solicitations)){
		
			foreach($solicitations as $solicitation){

			$sqlStatus = "update schedules_solicitation set status = 'SOLICITATION_DOES_NOT_ACCEPTED' where id = {$solicitation['schedules_solicitation']['id']};";
			$paramsStatus = array(
				'Service' => array(
					'query' => $sqlStatus
				)
			);
        
		$this->AccentialApi->urlRequestToGetData('Services', 'query', $paramsStatus);
		echo "STATUS".$sqlStatus.'<br/>';
		
		$this->sendMobileNotification($solicitation['schedules_solicitation']['user_id'], "Desculpe, o agendamento não pode ser confirmado pelo Salão. Por favor, solicite uma nova data."); 
		
		}
		}

		}
		
		
		public function checkDatesOfCheckoutsByBillet(){
			$this->autoRender = false;		
			$month = date("m");
			$day = date("d");
			$sql = "select * from checkouts where payment_state_id = 2 and payment_method_id = 73 and MONTH(date) = {$month} and DAY(date)+3 = {$day};";
			$param= array(
				'Service' => array(
					'query' => $sql
					)
				
			);
        
		$checkouts = $this->AccentialApi->urlRequestToGetData('Services', 'query', $param);
		
		
		if(!empty($checkouts)){
		
		foreach($checkouts as $check){
		
				$update="UPDATE offers SET amount_allowed = amount_allowed+1 WHERE id = {$check['checkouts']['offer_id']};";
				$paramUp= array(
				'Service' => array(
					'query' => $update
					)
				
			);
        
		$this->AccentialApi->urlRequestToGetData('Services', 'query', $paramUp);
		
		
		/**
		* Muda STATUS do CHECKOUTS para cancelado e usuário deverá fazer uma nova compra
		**/
		$updateCheck = "UPDATE checkouts SET payment_state_id = 5 WHERE id = {$check['checkouts']['id']};";
		$paramUpCheck= array(
				'Service' => array(
					'query' => $updateCheck
					)
				
			);
        
			$this->AccentialApi->urlRequestToGetData('Services', 'query', $paramUpCheck);
			}
		}
		
			print_r($checkouts);
			echo $sql;
			
		}
		
		public function li(){
		
		$this->autoRender = false;
			echo "li";
		
		}
		
		
		public function sendEmailByPOST($emailBody, $emailAddress, $subject){
		
		
		$url = 'https://api.turbo-smtp.com/api/mail/send';

$data = array('authuser' => "contato@jezzy.com.br", 'authpass' => "09#pLk#3}KgS", 'from' => "contato@jezzy.com.br", 'to' => $emailAddress, 'subject' => $subject, 'html_content' => $emailBody);

$options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
		$context  = stream_context_create($options);
		$result = json_decode(file_get_contents($url, false, $context));





          // Exibe uma mensagem de resultado
          if ($result->message == "OK") {

               //ENVIADO

          } else {

              //NÃO ENVIADO
		}
		
		
		}

}
