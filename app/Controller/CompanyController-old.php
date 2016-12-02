				<?php

				/**
				 * All actions about user login on Jezzy
				 */
				require("../Vendor/pushbots/PushBots.class.php");
				require("../Vendor/phpmailer/PHPMailerAutoload.php");
				require("../Vendor/turbo_send_email_code/lib/TurboApiClient.php");

				class CompanyController extends AppController {

					// token e key para cadastro no moip
					protected $key = '11PB4FPN68M1FE8MAPWUDIMEHFIGM8P6DMSBNXZZ';
					protected $token = 'JK75V6UGKYYUZR2ICVHJSSLD687UEJ9H';

					public function __construct($request = null, $response = null) {
						$this->layout = 'default_login';
						parent::__construct($request, $response);
					}

					/**
					 * Check the session every time the class is call, exepts on 'logout' 
					 */
					public function beforeFilter() {
						if ($this->action !== "logout") {
							
						}
					}

					/**
					 * Used just to show 'view' 
					 */
					public function createDir() {

						$companyName = $this->request->data ['companyName'];

						mkdir("/../../{$companyName}", 0700);
					}

					public function registra() {
						$this->layout = "";
					}

					public function createCompany() {
						
					}

					public function register() {
						$this->layout = "";
						
						 $this->Session->destroy();
							$this->Cookie->destroy();
						
					}

					/**
					 * Action responsável por cadastrar empresa
					 */
					public function inserCompany() {
						$this->layout = "";
						
						$birth = explode("/", $this->request->data['Company']['responsible_birthday']);
						if(!empty($birth)){
						$realBirthdayStr = $birth[2]."-".$birth[1]."-".$birth[0];
	}else{
		$realBirthdayStr = '1940-01-01';
	}

						$cep = str_replace("-","", $this->request->data['Company']['cep']);
						$dataRegistro = date('Y-m-d');
						$password = $this->geraSenha();
						// CRIAÇÃO DE FORNECEDOR
						$sql = "INSERT INTO companies(" .
								"`corporate_name`,"
								. "`fancy_name`,"
								. "`description`,"
								. "`site_url`,"
								. "`category_id`,"
								. "`sub_category_id`,"
								. "`cnpj`,"
								. "`email`,"
								. "`password`,"
								. "`phone`,"
								. "`phone_2`,"
								. "`address`,"
								. "`complement`,"
								. "`city`,"
								. "`state`,"
								. "`district`,"
								. "`number`,"
								. "`zip_code`,"
								. "`responsible_name`,"
								. "`responsible_cpf`,"
								. "`responsible_email`,"
								. "`responsible_phone`,"
								. "`responsible_phone_2`,"
								. "`responsible_cell_phone`,"
								. "`responsible_birthday`,"
								. "`logo`,"
								. "`status`,"
								. "`login_moip`,"
								. "`register`,"
								. "`facebook_install`,"
								. "`date_register`,"
								. "`open_hour`,"
								. "`close_hour`,"
								. "`first_login`"
								. ") VALUES("
								. "'" . $this->request->data['Company']['corporate_name'] . "',"
								. "'" . $this->request->data['Company']['fancy_name'] . "',"
								. "'descricao forn',"
								. "'" . $this->request->data['Company']['site'] . "',"
								. "15,"
								. "15, "
								. "'" . $this->request->data['Company']['cnpj'] . "',"
								. "'" . $this->request->data['Company']['email'] . "',"
								. "'" . md5($password) . "',"
								. "'" . $this->request->data['Company']['phone'] . "',"
								. "'" . $this->request->data['Company']['phone_2'] . "',"
								. "'" . $this->request->data['Company']['address'] . "',"
								. "'" . $this->request->data['Company']['complement'] . "',"
								. "'" . $this->request->data['Company']['city'] . "',"
								. "'" . $this->request->data['Company']['uf'] . "',"
								. "'" . $this->request->data['Company']['district'] . "',"
								. "'" . $this->request->data['Company']['number'] . "',"
								. "'" . $cep. "',"
								. "'" . $this->request->data['Company']['responsible_name'] . "',"
								. "'" . $this->request->data['Company']['responsible_cpf'] . "',"
								. "'" . $this->request->data['Company']['responsible_email'] . "',"
								. "'" . $this->request->data['Company']['responsible_phone'] . "',"
								. "'" . $this->request->data['Company']['responsible_phone_2'] . "',"
								. "'" . $this->request->data['Company']['responsible_cell'] . "',"
								. "'" . $realBirthdayStr . "',"
								. "'https://secure.jezzy.com.br/uploads/default-logo/logo.jpg',"
								. "'ACTIVE',"
								. "0,"
								. "0,"
								. "0,"
								. "'{$dataRegistro}',"
								. "'09:00:00.000000',"
								. "'18:00:00.000000',"
								. "1"
								. ");";

						$CompanysParam = array(
							'User' => array(
								'query' => $sql
							)
						);

						$retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $CompanysParam);


						$selectSql = "select * from companies where cnpj LIKE '" . $this->request->data['Company']['cnpj'] . "';";
						$SelCompanyParam = array(
							'User' => array(
								'query' => $selectSql
							)
						);

						$retornoSelect = $this->AccentialApi->urlRequestToGetData('users', 'query', $SelCompanyParam);

						//CRIANDO COMPANY PREFERENCE
						$INSERTPREFERENCE = "INSERT INTO  companies_preferences(companies_id) VALUES(" . $retornoSelect[0]['companies']['id'] . ");";
						$INSERTPREFERENCEParam = array(
							'User' => array(
								'query' => $INSERTPREFERENCE
							)
						);

						$this->AccentialApi->urlRequestToGetData('users', 'query', $INSERTPREFERENCEParam);
						
						// CRIANDO DIRETORIOS PARA COMPANY
						$this->AccentialApi->createCompanyDir($retornoSelect[0]['companies']['id']);


						//	UPDATING LOGO
						$hasLog = $this->request->data['Company']['hasPhoto'];
						if($hasLog == "TRUE"){
							$logo = $this->saveCompanyLogo($this->request->data['Company']['logo'], $retornoSelect[0]['companies']['id']);
							$LogoSql = "UPDATE companies SET logo = '" . $logo . "' WHERE id = " . $retornoSelect[0]['companies']['id'] . ";";
							$UpdCompanyParam = array(
								'User' => array(
									'query' => $LogoSql
								)
							);

							$reti = $this->AccentialApi->urlRequestToGetData('users', 'query', $UpdCompanyParam);
						}


						//CRIANDO CATEGORY SUB CATEGORY
						$categorySql = "insert into companies_categories_sub_categories(category_id, sub_category_id, company_id) values(7,4,{$retornoSelect[0]['companies']['id']});";
						$CategoryCompanyParam = array(
							'User' => array(
								'query' => $categorySql
							)
						);

						$this->AccentialApi->urlRequestToGetData('users', 'query', $CategoryCompanyParam);


						//	ENVIANDO EMAIL COM USUARIO E SENHA
						//$this->sendEmailNewUser($this->request->data['Company']['fancy_name'], $this->request->data['Company']['email'], $password);
						$this->sendEmailNewUserWithLayout($this->request->data['Company']['fancy_name'], $this->request->data['Company']['responsible_email'], $password);
						
						//ENVIANDO EMAIL DE BOAS VINDAS
						#VERSÃO COM LAYOUT ANTIGO DE BOAS VINDAS ~> $this->sendEmailWelcomeCompany($this->request->data['Company']['fancy_name'], $this->request->data['Company']['responsible_email']);
						$this->sendEmailWelcomeCompany_versionTWO($this->request->data['Company']['fancy_name'], $this->request->data['Company']['responsible_email']);
						
						
						//CRIANDO USUÁRIO SECUNDÁRIO PADRÃO, COM DADOS DO RESPONSAVEL PELA COMPANY
						$this->autoCreateSecondaryUser($retornoSelect[0], $password);
						
					
						$this->Session->setFlash(__('Cadastrado com sucesso!<br/>Verifique sua caixa de entrada, enviamos um email com seu usuário e senha'));
						
						$this->Session->write('insertedCompanyId', $retornoSelect[0]['companies']['id']);
						$this->Session->write('insertedCompany', $retornoSelect[0]);
						
						//ENVIANDO EMAIL PARA EQUIPE JEZZY, NOTIFICANDO CADASTRO DE SALÃO
						$this->notifyNewCompany($retornoSelect[0]['companies']['id']);
						
					//$this->redirect(array('controller' => 'login', 'action' => 'index'));
				$this->redirect(array('controller' => 'company', 'action' => 'planos'));
					}

					public function selectCompany() {
						$this->layout = "";

						$sql = "SELECT *  from classes inner join subclasses on  subclasses.classe_id = classes.id;";
						$CompanysParam = array(
							'User' => array(
								'query' => $sql
							)
						);

						$retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $CompanysParam);

						echo print_r($retorno);
					}

					public function saveCompanyLogo($logo, $companyId) {

						$this->autoRender = false;
						$url = "jezzyuploads/company-" . $companyId . "/config";
						$offersExtraPhotos  = '';
						
						if($logo['error'] != 0){
						$offersExtraPhotos = $this->AccentialApi->uploadAnyPhotoCompany($url, $logo, $companyId);
						// $saveDatabase = $this->saveImageUrl($this->request['data']['offerId'], $offersExtraPhotos, true);
						}else{
							$offersExtraPhotos = 'https://secure.jezzy.com.br/uploads/default-logo/logo.jpg';
						}

						return $offersExtraPhotos;
					}

					public function sendEmailNewUser($fancyName, $companyemail, $pass) {
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
						$mail->FromName = "Contato - Jezzy"; // Seu nome

						$mail->AddAddress("{$companyemail}");

						// Define os dados técnicos da Mensagem
				// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
						$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
				// Define a mensagem (Texto e Assunto)
				// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						$mail->Subject = "Bem-Vindo ao Jezzy Empresas"; // Assunto da mensagem
						$mail->Body = "Ola, {$fancyName} seja bem-vindo ao Jezzy Empresas, seus dados de login sao: <br/> Usuario: {$companyemail} <br/> Senha: {$pass} <br/><br/> <b>Boas Compras!</b>";
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

					function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
				// Caracteres de cada tipo
						$lmin = 'abcdefghijklmnopqrstuvwxyz';
						$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$num = '1234567890';
						$simb = '!@#$%*-';
				// Variáveis internas
						$retorno = '';
						$caracteres = '';
				// Agrupamos todos os caracteres que poderão ser utilizados
						$caracteres .= $lmin;
						if ($maiusculas)
							$caracteres .= $lmai;
						if ($numeros)
							$caracteres .= $num;
						if ($simbolos)
							$caracteres .= $simb;
				// Calculamos o total de caracteres possíveis
						$len = strlen($caracteres);
						for ($n = 1; $n <= $tamanho; $n++) {
				// Criamos um número aleatório de 1 até $len para pegar um dos caracteres
							$rand = mt_rand(1, $len);
				// Concatenamos um dos caracteres na variável $retorno
							$retorno .= $caracteres[$rand - 1];
						}
						return $retorno;
					}

					public function searchAddressByZipcode() {
						$this->layout = "";
					   // $this->autoRender = false;
					   $cep = $this->request->data['cep'];
						$cURL = curl_init("http://api.postmon.com.br/v1/cep/{$cep}");
						curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
						$resultado = curl_exec($cURL);
						curl_close($cURL);
						echo $resultado;
					}

					public function moipPreCadastroUrl() {
						$sua_key = $this->key;
						$seu_token = $this->token;
						$auth = $seu_token . ':' . $sua_key;
						/*
						 * $sua_key = 'SKMQ5HKQFTFRIFQBJEOROIGM70I6QVIN9KA5YIWB'; $seu_token = 'WOA4NBQ2AUMHJQ2NJIA6Q6X4ECXHFJUR'; $auth = $seu_token.':'.$sua_key;
						 */

						/**  $xml = "<PreCadastramento>
						  <prospect>
						  <idProprio>{$cadastro[0]['Company']['id']}</idProprio>
						  <nome>{$cadastro[0]['Company']['responsible_name']}</nome>
						  <sobrenome></sobrenome>
						  <email>{$cadastro[0]['Company']['responsible_email']}</email>
						  <dataNascimento></dataNascimento>
						  <rg></rg>
						  <cpf>{$cadastro[0]['Company']['responsible_cpf']}</cpf>
						  <cep>{$cadastro[0]['Company']['zip_code']}</cep>
						  <rua>{$cadastro[0]['Company']['address']}</rua>
						  <numero>{$cadastro[0]['Company']['number']}</numero>
						  <complemento>{$cadastro[0]['Company']['complement']}</complemento>
						  <bairro>{$cadastro[0]['Company']['district']}</bairro>
						  <cidade>{$cadastro[0]['Company']['city']}</cidade>
						  <estado>{$cadastro[0]['Company']['state']}</estado>
						  <telefoneFixo>{$cadastro[0]['Company']['responsible_phone']}</telefoneFixo>
						  <razaoSocial>{$cadastro[0]['Company']['corporate_name']}</razaoSocial>
						  <nomeFantasia>{$cadastro[0]['Company']['fancy_name']}</nomeFantasia>
						  <cnpj>{$cadastro[0]['Company']['cnpj']}</cnpj>
						  <cepEmpresa>{$cadastro[0]['Company']['zip_code']}</cepEmpresa>
						  <ruaEmpresa>{$cadastro[0]['Company']['address']}</ruaEmpresa>
						  <numeroEmpresa>{$cadastro[0]['Company']['number']}</numeroEmpresa>
						  <complementoEmpresa></complementoEmpresa>
						  <bairroEmpresa>{$cadastro[0]['Company']['district']}</bairroEmpresa>
						  <cidadeEmpresa>{$cadastro[0]['Company']['city']}</cidadeEmpresa>
						  <estadoEmpresa>{$cadastro[0]['Company']['state']}</estadoEmpresa>
						  <telefoneFixoEmpresa>{$cadastro[0]['Company']['phone']}</telefoneFixoEmpresa>
						  <tipoConta>1</tipoConta>
						  </prospect>
						  </PreCadastramento>
						  "; */
						$xml = "<PreCadastramento>
						<prospect>
						<idProprio>123</idProprio>
						<nome>Marcos do Santos</nome>
										<sobrenome></sobrenome>
											<email>marcos@santos.com</email>
										<dataNascimento></dataNascimento>
											<rg></rg>
											<cpf>000.000.000-09</cpf>
										<cep>04013040</cep>
													<rua>Rua Cubatão</rua>
															<numero>411</numero>
										<complemento>sala 2</complemento>
											<bairro>vila mariana</bairro>
										<cidade>são paulo</cidade>
													<estado>SP</estado>
													<telefoneFixo>0000000000</telefoneFixo>
															<razaoSocial>Sale For me LTDA</razaoSocial>
															<nomeFantasia>Sale for M</nomeFantasia>
															<cnpj>000000000000000000</cnpj>
																	<cepEmpresa>04013040</cepEmpresa>
																	<ruaEmpresa>Rua cuvbatão</ruaEmpresa>
																	<numeroEmpresa>411</numeroEmpresa>
																	<complementoEmpresa></complementoEmpresa>
																	<bairroEmpresa>vl mariana</bairroEmpresa>
																	<cidadeEmpresa>são paulo</cidadeEmpresa>
																	<estadoEmpresa>SP</estadoEmpresa>
																	<telefoneFixoEmpresa>{00999998888</telefoneFixoEmpresa>
																	<tipoConta>1</tipoConta>
																	</prospect>
																	</PreCadastramento>
																	";

						// pr($xml);exit;
						// O HTTP Basic Auth � utilizado para autentica��o
						$header [] = "Authorization: Basic " . base64_encode($auth);

						// URL do SandBox - Nosso ambiente de testes
						// $url = "https://desenvolvedor.moip.com.br/sandbox/ws/alpha/PreCadastramento";
						$url = "https://www.moip.com.br/ws/alpha/PreCadastramento";

						$curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $url);

						// header que diz que queremos autenticar utilizando o HTTP Basic Auth
						curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

						// informa nossas credenciais
						curl_setopt($curl, CURLOPT_USERPWD, $auth);
						curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0");
						curl_setopt($curl, CURLOPT_POST, true);

						// Informa nosso XML de instru��o
						curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

						// efetua a requisi��o e coloca a resposta do servidor do MoIP em $ret
						$ret = curl_exec($curl);
						$err = curl_error($curl);
						curl_close($curl);
						$xml = simplexml_load_string($ret);
						$json = json_encode($xml);
						$array = json_decode($json, TRUE);
						print_r($array);
					}

					public function carrega() {
						$this->layout = "";
					}
					
					  public function sendEmailNewUserWithLayout($fancyName, $companyemail, $pass) {
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
						$mail->FromName = "Contato - Jezzy"; // Seu nome

						$mail->AddAddress("{$companyemail}");

						// Define os dados técnicos da Mensagem
				// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
						$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
				// Define a mensagem (Texto e Assunto)
				// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						$mail->Subject = "Bem-Vindo ao Jezzy Empresas"; // Assunto da mensagem
						$mail->Body = ' <table border="0" cellpadding="0" cellspacing="0" >
							<tr>
								<td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas/01.jpg" width="600" style="vertical-align: bottom;"/></td>
							</tr>
							<tr style="background: #f7f7f7; text-align: center;">
								<td colspan="4">
									<br/>
									<span style="color: #999933; font-family: Helvetica, Arial, sans-serif; font-size: 36px;"><i>'.utf8_encode($fancyName).', seja bem-vindo!</i></span>
									<br/>
									<br/>
								</td>
							</tr>
							<tr>
								<td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas/03.jpg" width="600" style="vertical-align: bottom;"/></td>
							</tr>
							<tr style="background: #f7f7f7;">
								<td colspan="4" style="text-align: center;">
									<span style="color: #2597AC; font-size: 12px;  font-family: Helvetica, Arial, sans-serif;">
										<br/>
										<b> E-mail: '.$companyemail.'<br/>
											Senha: '.$pass.'</b>
										<br/>
									</span>
								</td>
							</tr>
							<tr style="background: #f7f7f7;">
								<td colspan="4">
									<br/>
									<img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/04.jpg" width="600"/>
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
									<img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/05-1.jpg" width="600" height="30" style="vertical-align: bottom;"/>
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/06.jpg" width="600" style="vertical-align: bottom;"/>
								</td>
							</tr>
							<tr>
								<td colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/07.jpg" width="151" style="vertical-align: bottom;"/></td>
								<td  colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/08.jpg" width="151" style="vertical-align: bottom;"/></td>
								<td colspan="1"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/09.jpg" width="151" style="vertical-align: bottom;"/></td>
								<td colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/10.jpg" width="151" style="vertical-align: bottom;"/></td>
							</tr>
							<tr>
								<td colspan="4">
									<img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/11.jpg" width="600" style="vertical-align: bottom;"/>
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg" width="600" style="vertical-align: bottom;"/>
								</td>
							</tr>
						</table>';
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
					
					/**
					* FALSE - USUÁRIO NÃO EXISTE
					* TRUE - USUARIO JA CADASTRADO NA BASE
					*/
					public function verificaEmailCompany(){
							 $this->autoRender = false;
							$email = $this->request->data['email'];
							
							$sql = "SELECT * FROM companies WHERE email LIKE '{$email}'  or responsible_email LIKE '{$email}';";
							   $params = array('User' => array('query' => $sql));
							$secondary = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
							
							if(empty($secondary)){
								return false;
							}else{
								return true;
							}		
					}
					
					
					public function verificaEmailCompanyInSecondUserTable(){
					
					
						 $this->autoRender = false;
							$email = $this->request->data['email'];
							
							$sql = "SELECT * FROM secondary_users WHERE email LIKE '{$email}';";
							   $params = array('User' => array('query' => $sql));
							$secondary = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
							
							if(empty($secondary)){
								return false;
							}else{
								return true;
							}	
						
					
					}
					
					
					
					public function calculaQtdTrailDays($compDateRegister){
					
						$atualDate = date('Y-m-d');
						
						$initialDate = strtotime($compDateRegister);
						$finalDate = strtotime($atualDate);
						
						$difference = $finalDate - $initialDate;
						
						$qtdDays = (int)floor($difference/ (60 * 60 *24));
						
						return $qtdDays;
					
					}

					
					
					public function planos(){
						$this->layout = "";
					
						$companyId = 0;
						
						if(!empty($this->Session->read('insertedCompanyId'))){
						
						$companyId = $this->Session->read('insertedCompanyId');
						
						}else {
						
							$company = $this->Session->read('CompanyLoggedIn');
							$companyId = $company['Company']['id'];
						
						}
					
							if ($this->request->is('post')) {
							
								$query = "SELECT * FROM company_plans WHERE company_id ={$companyId};";
								$paramsSelect = array('User' => array('query' => $query));
								$register = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramsSelect);
								
								if(empty($register)){
							
								$data = date('Y-m-d');
								$sql = "INSERT INTO company_plans(
									`company_id`,
									`plan`,
									`date_register`
								) VALUES(
									{$companyId},
									'{$this->request->data['plan']}',
									'{$data}'
								);";
								
							$params = array('User' => array('query' => $sql));
							$this->AccentialApi->urlRequestToGetData('users', 'query', $params);
							
							}else{
							
							$data = date('Y-m-d');
								$sql = "UPDATE company_plans SET `plan` = '{$this->request->data['plan']}', `date_register` = '{$data}' ";
								
							$params = array('User' => array('query' => $sql));
							$this->AccentialApi->urlRequestToGetData('users', 'query', $params);
							
							}
						
								$this->returnCompanyPosCreate();
							}
					}
					
					
					public function createTRIALRegister(){
						$this->autoRender = false;
						$companyId = $this->Session->read('insertedCompanyId');
						$data = date('Y-m-d');
						
						$sql = "INSERT INTO company_plans(
						`company_id`,
						`plan`, 
						`date_register`, 
						`TRIAL_COUNTER`) VALUES(
						{$companyId},
						'TRIAL',
						'{$data}',
						15
						);";
						
						$params = array('User' => array('query' => $sql));
						$this->AccentialApi->urlRequestToGetData('users', 'query', $params);
					echo $sql;
					}
					
					public function returnCompanyPosCreate(){
						$this->autoRender = false;
						
						$company = '';
						
						if(!empty($this->Session->read('insertedCompany'))){
						
						$company = $this->Session->read('insertedCompany');
						
						}else {
						
							$companyallready = $this->Session->read('CompanyLoggedIn');
							$company['companies'] = $companyallready['Company'];
						
						}
						
						echo json_encode($company);
					}
					
					
					/**
					* @author Matheus Odilon
					* @
					*
					* O Script dessa função será usado para a criação dos planos de assinaturas pelas empresas
					* no JEZZY
					* PREMIUM && STANDARD
					*
					* O código deve ser executado duas vezes, uma para cada criação de plano
					**/
					public function createPlansOnMoIP(){
					$this->autoRender = false;
					// STANDARD - amount correspondente à 149,80 - setup_free = tacha de inscrição
						$jsonSTANDARD = '{
								"code": "standard",
									"name": "STANDARD",
				  "description": "Aplicativo para o Salão (web) com todas as funcionalidades disponíveis",
				  "amount": 14980,
				  "setup_fee": 14980,
				  "max_qty": 1,
				  "interval": {
					"length": 1,
					"unit": "MONTH"
				  },
				  "billing_cycles": 12,
				  "trial": {
					"days": 15,
					"enabled": true,
					"hold_setup_fee": true
				  },
				  "payment_method": "CREDIT_CARD"
				}';

				// PREMIUM - amount correspondente à 249,80 - setup_free = tacha de inscrição
				$jsonPREMIUM= '{
								"code": "premium",
									"name": "PREMIUM",
				  "description": "Aplicativo personalizado com logotipo e cores para o Salão (web) com todas as funcionalidades disponíveis",
				  "amount": 24980,
				  "setup_fee": 24980,
				  "max_qty": 1,	
				  "interval": {
					"length": 1,
					"unit": "MONTH"
				  },
				  "billing_cycles": 12,
				  "trial": {
					"days": 15,
					"enabled": true,
					"hold_setup_fee": true
				  },
				  "payment_method": "CREDIT_CARD"
				}';

													   $header = array();
														$header[] = 'Content-type: application/json';
														//$header [] = "Authorization: Basic SFJFT1RPSEpPNElZUVJPMjRBRVVVTVE4OVpRMTEzUk46U1BUUTJYUllTN1dISlVLUUtIMjVUQzk1N0gwM0xJNFpXS0xDTzBDTA==";
														//$auth = 'SFJFT1RPSEpPNElZUVJPMjRBRVVVTVE4OVpRMTEzUk46U1BUUTJYUllTN1dISlVLUUtIMjVUQzk1N0gwM0xJNFpXS0xDTzBDTA';
														$header [] = "Authorization: Basic Sks3NVY2VUdLWVlVWlIySUNWSEpTU0xENjg3VUVKOUg6MTFQQjRGUE42OE0xRkU4TUFQV1VESU1FSEZJR004UDZETVNCTlhaWg==";
														$auth = 'Sks3NVY2VUdLWVlVWlIySUNWSEpTU0xENjg3VUVKOUg6MTFQQjRGUE42OE0xRkU4TUFQV1VESU1FSEZJR004UDZETVNCTlhaWg==';
														
														
														// URL do SandBox - Nosso ambiente de testes
														//$url = "https://sandbox.moip.com.br/assinaturas/v1/plans";
														$url = "https://api.moip.com.br/assinaturas/v1/plans";
														
														$curl = curl_init();
														curl_setopt($curl, CURLOPT_URL, $url);

														// header que diz que queremos autenticar utilizando o HTTP Basic Auth
														curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

														// informa nossas credenciais
														curl_setopt($curl, CURLOPT_USERPWD, $auth);
														curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
														curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0");
														curl_setopt($curl, CURLOPT_POST, true);

														// Informa nosso XML de instru��o
														curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonSTANDARD);

														curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

														// efetua a requisi��o e coloca a resposta do servidor do MoIP em $ret
														$ret = curl_exec($curl);
														$err = curl_error($curl);
														$err = curl_error($curl);
														curl_close($curl);
														
														var_dump($ret);
					
					}
					
					
					/**
					* Essa funcao sera usada para criar as assinaturas das empresas àos planos oferecidos pelo JEZZY (criados na function acima)
					* Todas a Empresas cadastradas devem aderir a um plano
					*/
					public function createPlansSignatureMoIP(){
					$this->autoRender = false;
					
					$this->Session->write("isSelectingPlan", false);
					
					$company = '';
						
						if(!empty($this->Session->read('insertedCompany'))){
						
						$company = $this->Session->read('insertedCompany');
						
						}else {
						
							$companyallready = $this->Session->read('CompanyLoggedIn');
							$company['companies'] = $companyallready['Company'];
						
						}
					
					//$company = $this->Session->read('insertedCompany');
					
					//ALTERANDO REGISTRO DO COMPANY_PLAN/ INSERINDO CODIGO DA ASSINATURA
					$query = "UPDATE company_plans SET signature_plan_code = 'assinatura" . $company["companies"]["id"] . "' WHERE company_id = " . $company["companies"]["id"] . ";";
						$param = array(
							'User' => array(
								'query' => $query
							)
						);

					$this->AccentialApi->urlRequestToGetData('users', 'query', $param);
					//********************************************************
					
					$noDotcpf = str_replace(".", "", $company['companies']['responsible_cpf']);
					$cpf = str_replace("-", "", $noDotcpf );
					
					$almostPhone = str_replace("-", "", 
					str_replace("(", "",
					str_replace(" ", "", $company['companies']['responsible_phone'])));
					
					$phone = explode(")", $almostPhone);				
					
					$data = strtotime($company["companies"]["responsible_birthday"]);
					
					$amountValue = "";
					if($this->request->data["planCode"] == 'standard'){
					$amountValue = "14980";
					}else if($this->request->data["planCode"] == 'standard'){
					$amountValue = "24980";
					}
					
						$json = '{
			  "code": "assinatura'.$company["companies"]["id"].'",
			  "amount": "'.$amountValue.'",
			  "payment_method": "CREDIT_CARD",
			  "plan": {
				"name": "'.$this->request->data["planName"].'",
				"code": "'.$this->request->data["planCode"].'"
			  },
			  "customer": {
				"code": "'.$company["companies"]["id"].'",
				"email": "'.$company["companies"]["responsible_email"].'",
				"fullname": "'.$company["companies"]["responsible_name"].'",
				"cpf": "'.$cpf.'",
				"phone_number": "'.$phone[1].'",
				"phone_area_code": "'.$phone[0].'",
				"birthdate_day": "'.date('d', $data).'",
				"birthdate_month": "'.date('m', $data).'",
				"birthdate_year": "'.date('Y', $data).'",
				"address": {
				  "street": "'.$company["companies"]["address"].'",
				  "number": "'.$company["companies"]["number"].'",
				  "complement": "'.$company["companies"]["complement"].'",
				  "district": "'.$company["companies"]["district"].'",
				  "city": "'.$company["companies"]["city"].'",
				  "state": "'.$company["companies"]["state"].'",
				  "country": "BRA",
				  "zipcode": "'.$company["companies"]["zip_code"].'"
				},
				"billing_info": {
				  "credit_card": {
					"holder_name": "'.$this->request->data["nameFromCard"].'",
					"number": "'.$this->request->data["numberCard"].'",
					"expiration_month": "'.$this->request->data["monthExpirationCard"].'",
					"expiration_year": "'.$this->request->data["yearExpirationCard"].'"
				  }
				}
			  }
			}
			';
			
			 $header = array();
														$header[] = 'Content-type: application/json';
														//$header [] = "Authorization: Basic SFJFT1RPSEpPNElZUVJPMjRBRVVVTVE4OVpRMTEzUk46U1BUUTJYUllTN1dISlVLUUtIMjVUQzk1N0gwM0xJNFpXS0xDTzBDTA==";
														//$auth = 'SFJFT1RPSEpPNElZUVJPMjRBRVVVTVE4OVpRMTEzUk46U1BUUTJYUllTN1dISlVLUUtIMjVUQzk1N0gwM0xJNFpXS0xDTzBDTA';
														$header [] = "Authorization: Basic Sks3NVY2VUdLWVlVWlIySUNWSEpTU0xENjg3VUVKOUg6MTFQQjRGUE42OE0xRkU4TUFQV1VESU1FSEZJR004UDZETVNCTlhaWg==";
														$auth = 'Sks3NVY2VUdLWVlVWlIySUNWSEpTU0xENjg3VUVKOUg6MTFQQjRGUE42OE0xRkU4TUFQV1VESU1FSEZJR004UDZETVNCTlhaWg==';
														
														// URL do SandBox - Nosso ambiente de testes
														//$url = "https://sandbox.moip.com.br/assinaturas/v1/subscriptions?new_customer=true";
														$url = "https://api.moip.com.br/assinaturas/v1/subscriptions?new_customer=true";

														$curl = curl_init();
														curl_setopt($curl, CURLOPT_URL, $url);

														// header que diz que queremos autenticar utilizando o HTTP Basic Auth
														curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

														// informa nossas credenciais
														curl_setopt($curl, CURLOPT_USERPWD, $auth);
														curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
														curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0");
														curl_setopt($curl, CURLOPT_POST, true);

														// Informa nosso XML de instru��o
														curl_setopt($curl, CURLOPT_POSTFIELDS, $json);

														curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

														// efetua a requisi��o e coloca a resposta do servidor do MoIP em $ret
														$ret = curl_exec($curl);
														$err = curl_error($curl);
														$err = curl_error($curl);
														curl_close($curl);
														
														$j = json_decode($ret);
														echo json_encode($j);

					}
					
					/**
				* Funcao responsavel por verificar se já existe alguma empresa cadastrada com o cnpj em questão
				* Caso exista será retornado o valor TRUE, senão, FALSE
				*/
				public function verifyExistentCNPJ(){
				
					$this->autoRender = false;
					
					$cnpj = $this->request->data['cnpj'];
					
					$sql = "SELECT * FROM companies WHERE cnpj LIKE '{$cnpj}';";
					
					$param = array(
							'User' => array(
								'query' => $sql
							)
						);

					$retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $param);
					
					if(!empty($retorno)){
					
						return true;
					
					}else{
					
						return false;
					
					
					}
				
				}
				
				public function autoCreateTRIALPlan(){
						$this->autoRender = false;
						$company = $this->Session->read('insertedCompany');
						$date = date('Y-m-d');
						
						/**
						VERIFICAMOS SE REGISTRO JÁ EXISTE
						*/
						$sqlSelect = "SELECT * FROM company_plans WHERE company_id = {$company['companies']['id']};";
						$paramSELECT = array(
							'User' => array(
								'query' => $sqlSelect
							)
						);

					$companyPlan = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramSELECT);
					
					if(empty($paramSELECT)){
						
					
					//ALTERANDO REGISTRO DO COMPANY_PLAN/ INSERINDO CODIGO DA ASSINATURA
					$sql = "INSERT INTO company_plans(`company_id`, `plan`, `date_register`)
					VALUES({$company['companies']['id']}, 'TRIAL', '{$date}');";
					
					//$query = "UPDATE company_plans SET `plan` = 'TRIAL' WHERE company_id = " . $company["companies"]["id"] . ";";
						
						$param = array(
							'User' => array(
								'query' => $sql
							)
						);

					$this->AccentialApi->urlRequestToGetData('users', 'query', $param);
				}else{
				
						$query = "UPDATE company_plans SET `plan` = 'TRIAL' WHERE company_id = " . $company["companies"]["id"] . ";";
						
						$param = array(
							'User' => array(
								'query' => $query
							)
						);

					$this->AccentialApi->urlRequestToGetData('users', 'query', $param);
				
				}
				}				
				
				/**
				* R O T I N A ROTINA 
				* Rotina responsável por decrescer o valor dos dias restantes para as empresas 
				* que aderiram inicialmente o JEZZY com o Plano TRIAL
				*/
				public function decreaseCountCompaniesONTrail(){
					$this->autoRender = false;
					$sql = "UPDATE company_plans SET TRIAL_COUNTER = TRIAL_COUNTER-1 WHERE plan LIKE 'TRIAL' and TRIAL_COUNTER > 0;";
					$param = array(
							'User' => array(
								'query' => $sql
							)
						);

					$this->AccentialApi->urlRequestToGetData('users', 'query', $param);
				}
				
				public function generateTokenOAuth(){
				
				$this->autoRender = false;
				
				/*$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"https://connect-sandbox.moip.com.br/oauth/token");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
				"client_id=APP-FQYVVYN2AQ3J&client_secret=67619a1aae4b4f988967b86e0bbad4e8&grant_type=85ea3adf8465e5ac8e1ae9da70f5969a496b416e&redirect_uri=https://secure.jezzy.com.br/uploads/recebeRetorno.php");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));


	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);
		$ret = curl_exec($ch);
	curl_close ($ch);

		$j = json_decode($ret);
														echo json_encode($j);*/
				
				 $header = array();
														$header[] = 'Content-type: application/x-www-form-urlencoded';
														$header [] = "Authorization: Basic SFJFT1RPSEpPNElZUVJPMjRBRVVVTVE4OVpRMTEzUk46U1BUUTJYUllTN1dISlVLUUtIMjVUQzk1N0gwM0xJNFpXS0xDTzBDTA==";
														$header[] = 'Cache-Control: no-cache';
														//$header[] = 'client_id=APP-FQYVVYN2AQ3J&client_secret=67619a1aae4b4f988967b86e0bbad4e8&grant_type=AUTHORIZATION_CODE&redirect_uri=https://secure.jezzy.com.br/uploads/recebeRetorno.php';
														
														//$auth = 'SFJFT1RPSEpPNElZUVJPMjRBRVVVTVE4OVpRMTEzUk46U1BUUTJYUllTN1dISlVLUUtIMjVUQzk1N0gwM0xJNFpXS0xDTzBDTA';
														
														// URL do SandBox - Nosso ambiente de testes
														$url = "https://connect-sandbox.moip.com.br/oauth/token=85ea3adf8465e5ac8e1ae9da70f5969a496b416e";

														$curl = curl_init();
														curl_setopt($curl, CURLOPT_URL, $url);

														// header que diz que queremos autenticar utilizando o HTTP Basic Auth
														curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

														// informa nossas credenciais
														//curl_setopt($curl, CURLOPT_USERPWD, $auth);
														curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
														curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0");
														curl_setopt($curl, CURLOPT_POST, true);
														curl_setopt($curl, CURLOPT_POSTFIELDS,
														"client_id=APP-FQYVVYN2AQ3J&client_secret=67619a1aae4b4f988967b86e0bbad4e8&grant_type=85ea3adf8465e5ac8e1ae9da70f5969a496b416e&redirect_uri=https://secure.jezzy.com.br/uploads/recebeRetorno.php");

														// Informa nosso XML de instru��o
														//curl_setopt($curl, CURLOPT_POSTFIELDS, $json);

														curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

														// efetua a requisi��o e coloca a resposta do servidor do MoIP em $ret
														$ret = curl_exec($curl);
														$err = curl_error($curl);
														$err = curl_error($curl);
														curl_close($curl);
														
														$j = json_decode($ret);
														echo json_encode($j); 
														echo "<br/>tes";
				
				}
				
				public function tesss(){

				$this->autoRender = false;
				
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL,"https://connect-sandbox.moip.com.br/oauth/token");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,
				"client_id=APP-FQYVVYN2AQ3J&client_secret=67619a1aae4b4f988967b86e0bbad4e8&grant_type=85ea3adf8465e5ac8e1ae9da70f5969a496b416e&redirect_uri=https://secure.jezzy.com.br/uploads/recebeRetorno.php");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic SVFWVDFZRFNGMVdCWEpSOVNJS09KRDIzQlMzVFZIVjA6TklRV0RVSkhQOUlTVzJDSVhDRU9QUVFWWFlOUEVLUUQyTkhLR0IwSg=='));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache'));
	
	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);

	//curl_close ($ch);
$ret = curl_exec($ch);
														$err = curl_error($ch);
														$err = curl_error($ch);
														curl_close($ch);
														
														$j = json_decode($ret);
														echo json_encode($j); 
														echo "<br/>tes";
	
				
				}
				
				public function selectPlan(){
				$this->layout= "";
				
					$this->Session->write("isSelectingPlan", true);
				
				}
				
				public function sendMobileNotification($userId = null, $message = null) {
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
    public function sendPublicMobileNotification($message = null) {
        $this->autoRender = false;
        $pb = new PushBots();
// Application ID
       // $appID = '56f2db1e4a9efa66868b4567';
// Application Secret
        //$appSecret = 'c0e22a98aee35816d1266a4c20bc9979';
		
				$appID = '578798eb4a9efa173a8b4567';
        $appSecret = 'fb250a5c26b589fc9ae57ed790a26643';
        $pb->App($appID, $appSecret);
// Notification Settings
        $platforms[0] = 0;
        $platforms[1] = 0;

        $pb->Alert($message);

        $pb->Platform($platforms);
// Custom fields - payload data
        $customfields = array("author" => "Jeff", "nextActivity" => "com.example.sampleapp.Next");
        $pb->Payload($customfields);
// Push it !
        $pb->Push();
       
    }
	
	
	public function createDropoutCompany(){
		$this->autoRender = false;
		$date = date("Y-m-d");
		$description = $this->request->data['description'];
		$company = $this->Session->read('CompanyLoggedIn');
		
		$sql = "INSERT INTO `dropouts_companies`
					(`description`,
					`date`,
					`company_id`)
						VALUES
					('{$description}',
					'{$date}',
					{$company['Company']['id']});";
						
		$params = array(
            'User' => array(
                'query' => $sql
            )
        );
		
      $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
	  
	  // ************************************************************************************
	  $updateSQL = "UPDATE companies SET status = 'INACTIVE' WHERE id = {$company['Company']['id']}";
	  $paramsUpdate = array(
            'User' => array(
                'query' => $updateSQL
            )
        );
		
      $this->AccentialApi->urlRequestToGetData('users', 'query', $paramsUpdate);
	   
	   //$this->redirect(array('controller' => 'Utils', 'action' => 'welcomeAssociated'));
	   	   
	}
	
	
	public function sendEmailWelcomeCompany($fancy_company_name, $companyemail){

	
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
						$mail->FromName = "Contato - Jezzy"; // Seu nome

						$mail->AddAddress("{$companyemail}");

						// Define os dados técnicos da Mensagem
				// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
						$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
				// Define a mensagem (Texto e Assunto)
				// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						$mail->Subject = "Boas Vindas Salão"; // Assunto da mensagem
						
						$mail->Body = '<table border="0" cellpadding="0" cellspacing="0" >
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(1).jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
			<tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(2)-1.jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="background: #f2f2f2; text-align: center;">
                <td colspan="4">
                    <br/>
                    <span style="color: #999933; font-family: Helvetica, Arial, sans-serif; font-size: 36px;"><i>Salão '.$fancy_company_name.',</i></span>
                    <br/>
                    <br/>
                </td>
            </tr>
			<tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(2)-2.jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
           <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(3).jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
         
		   <tr>
                <td colspan="4">
				<a href=" https://youtu.be/0ZEQB7o13_c">
				<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(4).jpg" width="600" style="vertical-align: bottom;"/></td>
				</a>
		   </tr>
			  <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(5).jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
			  <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(6).jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
			  <tr>
                <td colspan="4">
				<a href="https://youtu.be/3hk1ZH5AQTg">
					<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(7).jpg" width="600" style="vertical-align: bottom;"/></td>
				</a>
		   </tr>
			
			 <tr>
                <td colspan="4">
				<a href="https://youtu.be/SuGp02HHLN4">
				<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(8).jpg" width="600" style="vertical-align: bottom;"/></td>
				</a>
		   </tr>
		 
		  <tr>
                <td colspan="4">
				<a href=" https://youtu.be/0ZEQB7o13_c">
				<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(9).jpg" width="600" style="vertical-align: bottom;"/></td>
				</a>
			</tr>
		 
		  <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(10).jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
		 
		  <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(11).jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
			
			 <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(12).jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
			
			 <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(13).jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
			
			 <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(14).jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
		 
		 
		 
		 
		 <!-- -->
            <tr>
                <td colspan="4">
                    <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/05-1.jpg" width="600" height="30" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(15).jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/07.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td  colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/08.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td colspan="1"> <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/09.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/10.jpg" width="151" style="vertical-align: bottom;"/></td>
            </tr>
   
            <tr>
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-empresas/boas-vindas-empresa(17).jpg" width="600" style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>';
						
						
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
	
	
	public function sendEmailWelcomeCompany_versionTWO($fancy_company_name, $companyemail){

	$this->autoRender = false;
						
						$emailBody = '<table style="background: #f2f2f2;" id="Table_01" width="800" height="2393" border="0" cellpadding="0" cellspacing="0" >
	<tr style="background: #f2f2f2;">
		<td colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-1.jpg" width="800"  alt=""></td>
	</tr>
	<tr style="background: #f2f2f2;">
		<td colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-2.jpg" width="800" alt=""></td>
	</tr>
	<tr style="background: #f2f2f2;">
		<td colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-3.jpg" width="800" height="129" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-4.jpg" width="800" height="30" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<a href="https://youtu.be/0ZEQB7o13_c"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-5.jpg" width="800" height="110" alt=""></a></td>
	</tr>
	<tr>
		<td colspan="4">
			<a href="https://youtu.be/93dehF9I6CA"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-6.jpg" width="800" height="105" alt=""></a></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-7.jpg" width="800" height="146" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-8.jpg" width="800" height="148" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<a href="https://youtu.be/3hk1ZH5AQTg"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-9.jpg" width="800" height="164" alt=""></a></td>
	</tr>
	<tr>
		<td colspan="4">
			<a href="https://youtu.be/SuGp02HHLN4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-10.jpg" width="800" height="107" alt=""></a></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-11.jpg" width="800" height="89" alt=""></td>
	</tr>
	<tr>
		<td height="164" colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-12.jpg" width="800" height="164" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-13.jpg" width="800" height="123" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-14.jpg" width="800" height="112" alt=""></td>
	</tr>
	<tr>
		<td height="142" colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-15.jpg" width="800" height="142" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-16.jpg" width="800" height="121" alt=""></td>
	</tr>
	<tr>
		<td height="71" colspan="4">
			<img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-17.jpg" width="800" height="71" alt=""></td>
	</tr>
	<tr>
		<td>
			<a href="https://www.facebook.com/JezzyApp/"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-18.jpg" width="184" height="95" alt=""></a></td>
		<td>
			<a href="https://twitter.com/jezzyapp"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-19.jpg" width="216" height="95" alt=""></a></td>
		<td>
			<a href="https://www.instagram.com/jezzyapp/"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-20.jpg" width="198" height="95" alt=""></a></td>
		<td>
			<a href="https://plus.google.com/u/0/108979612059457905903"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-21.jpg" width="202" height="95" alt=""></a></td>
	</tr>
	<tr>
		<td colspan="4">
			<a href="http://www.jezzy.com.br/site/jezzy-para-saloes/"><img src="https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas-TWO/boas-vindas-company-22.jpg" width="800" height="87" alt=""></a></td>
	</tr>
</table>';
						
						
						$this->sendEmailByPOST($emailBody, $companyemail, "Boas Vindas");
	
	}
	public function notifyNewCompany($id){
	
		$this->autoRender = false;
		
		//$companyId = $this->Session->read('insertedCompanyId');
		$companyId = $id;
		
		$Sql = "SELECT * FROM companies WHERE id = {$companyId};";
		$INSERTPREFERENCEParam = array(
							'User' => array(
								'query' => $Sql
							)
						);

						$company = $this->AccentialApi->urlRequestToGetData('users', 'query', $INSERTPREFERENCEParam);
	
		$emailBody = "<div style='font-family: COURIER NEW; color: #000000;'><b><h3>NEW COMPANY CREATED</h3></b><br/><br/>
		<b>NOME DO SALÃO...:</b> {$company[0]['companies']['fancy_name']} ( {$company[0]['companies']['corporate_name']} )<br/>
		<b>NOME DO RESP....:</b> {$company[0]['companies']['responsible_name']} <br/>
		<b>EMAIL...........:</b> {$company[0]['companies']['email']} e {$company[0]['companies']['responsible_email']} <br/>
		<b>TELEFONE........:</b>{$company[0]['companies']['phone']}<br/>
		<b>DATA DO CADASTRO:</b>{$company[0]['companies']['date_register']}<br/>
		<b>ENDEREÇO........:</b>{$company[0]['companies']['address']} {$company[0]['companies']['number']} - {$company[0]['companies']['district']}, {$company[0]['companies']['city']} - {$company[0]['companies']['state']}, {$company[0]['companies']['zip_code']}
		</div>";
		
		$userEmail = "jezzyapp@gmail.com";
		$this->sendEmailByPOST($emailBody, $userEmail, "Novo Salão Cadastrado");
	
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
		
		public function autoCreateSecondaryUser($company, $password){
		//$this->autoRender = false;
		
		/*para executar na mão
			$sql2 = "SELECT * FROM companies WHERE id = ";
			$password = md5(123456);
				$param2 = array(
            'User' => array(
                'query' => $sql2
            )
        );

        $comp = $this->AccentialApi->urlRequestToGetData('users', 'query', $param2);
		$company = $comp[0]; */
			
			$password = md5($password);
		
			$query = "INSERT INTO secondary_users(name, email, password, company_id, secondary_type_id, first_login)"
						. " VALUES('" . $company['companies']['responsible_name'] . "'"
						. ",'" . $company['companies']['responsible_email'] . "'"
						. ",'" . $password . "'"
						. "," . $company['companies']['id']. ""
						. ", 3, 0);
						SET @LAST_ID =  last_insert_id();
						INSERT INTO `secondary_users_commissioning_fees`
(`secondary_user_id`,
`rate_per_company_product`,
`rate_per_jezzy_product`,
`rate_per_service`)
VALUES(
@LAST_ID,
0,
0,
0);

	INSERT INTO `jezzyapp_main`.`secondary_users_using`
(`secondary_users_id`,
`mobile`,
`android`,
`ios`,
`registration_id`)
VALUES
(@LAST_ID,
'ACTIVE',
'INACTIVE',
'INACTIVE',
'REGISTRATION_PUSHBOTS_ID');
						";
						
						$param = array(
            'User' => array(
                'query' => $query
            )
        );

        $this->AccentialApi->urlRequestToGetData('users', 'query', $param);
		
		}
		
		#pesquisa usuário por nome, 
	# usado no input dentro do bloco de indicação
	public function searchUsersByNameToDropDown(){
		$this->autoRender = false;
		
		$name = $this->request->data['name'];
		$sql = "select * from users where name LIKE '{$name}%';";
		$params = array(
            'User' => array(
                'query' => $sql
            )
        );
		
      $users = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
	
		$usersOptions = '';
		
		if(!empty($users)){
		
		foreach( $users as $user){
		
			$usersOptions .= '<li class="selectableIndicationUser" onclick="selectUserFromIndication(this)" id="'.$user['users']['id'].'" name="'.$user['users']['name'].'"><span><img src="'.$user['users']['photo'].'" width="35"/>  </span>'.$user['users']['name'].'</li>';
		
		}
		
		}
		
		echo $usersOptions;
	
	}
	
	public function searchIndicationCodeByUser(){
		$this->autoRender = false;
	
		$userId = $this->request->data['userID'];
		$companyEmail = $this->request->data['companyEmail'];
		$responsibleEmail = $this->request->data['responsibleEmail'];
		
		$sql = "select * from indications where indication_user_id = {$userId};";
		$params = array(
            'User' => array(
                'query' => $sql
            )
        );
		
      $indications = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
	  
	  $indicationCode = '';
	  
	  if(!empty($indications)){
	  foreach($indications as $indication){
	  
		if($indication['indications']['company_email'] == $companyEmail){
			$indicationCode = $indication['indications']['cod_indication'];
		
		}else if($indication['indications']['company_email'] == $responsibleEmail){
			$indicationCode = $indication['indications']['cod_indication'];
			
		}else{
			$indicationCode = 0;
		}
	  
	  }
	  }else{
		$indicationCode = 0;
	  }
	  
	  $indicationCode = str_replace(' ', '', $indicationCode);
	  return $indicationCode;
	
	}	
				
				}
				
				
