<?php

require("../Vendor/phpmailer/PHPMailerAutoload.php");
require("../Vendor/turbo_send_email_code/lib/TurboApiClient.php");

/**
 * All actions about user login on Jezzy
 */
class LoginController extends AppController {

    public function __construct($request = null, $response = null) {
        $this->layout = 'default_login';
        parent::__construct($request, $response);
    }

    /**
     * Check the session every time the class is call, exepts on 'logout' 
     */
    public function beforeFilter() {
        if ($this->action !== "logout") {
            if ($this->Cookie->check("sessionLogado") === true && $this->Cookie->check("CompanyLoggedIn") === true && is_array($this->Cookie->read("CompanyLoggedIn"))) {
                $this->Session->write('sessionLogado', true);
                $this->Session->write('CompanyLoggedIn', $this->Cookie->read("CompanyLoggedIn"));
            }
            if ($this->Cookie->check('userLoggedType') === true) {
                $this->Session->write('userLoggedType', $this->Cookie->read('userLoggedType'));
            }
            if ($this->Cookie->check('userLoggedType') === true) {
                $this->Session->write('secondUserLogado', $this->Cookie->read('secondUserLogado'));
            }
            if ($this->Cookie->check('SecondaryUserLoggedIn') === true) {
                $this->Session->write('SecondaryUserLoggedIn', $this->Cookie->read('SecondaryUserLoggedIn'));
            }
            if ($this->Session->check("sessionLogado") === true && $this->Session->check("CompanyLoggedIn") === true && is_array($this->Session->read("CompanyLoggedIn"))) {
                
				$company = $this->Session->read("CompanyLoggedIn");
				$plan = $this->getCompanyPlan($company['Company']['id']);
				// $this->redirect(array('controller' => 'Company', 'action' => 'selectPlan'));
					if(!empty($plan)){
					 $this->Session->write('CompanyPlan', $plan);

						if($plan[0]['company_plans']['plan'] == 'TRIAL' && $plan[0]['company_plans']['TRIAL_COUNTER'] == 0){
							$this->redirect(array('controller' => 'Company', 'action' => 'selectPlan'));
							break;
						}
					 
					}
				
				$this->redirect(array('controller' => 'Dashboard', 'action' => 'index'));
            }
        }
    }

    /**
     * Used just to show 'view' 
     */
    public function index() {
	
	
    }

    /**
     * Used just to show 'view' 
     */
    public function forgotPassword() {
        
    }

    /**
     * Do the login of user or return to 'index' with a error message
     */
    public function login() {
        $this->autoRender = false;
        if ($this->request->is('post')) {

            $email = trim($this->request->data ['Company'] ['email']);
            $pass = md5(trim($this->request->data ['Company'] ['password']));
			
			/*$sqlSelect = "select * from companies inner join companies_preferences on companies_preferences.companies_id = companies.id where companies.email LIKE '{$email}' and companies.password = '{$pass}';";
			$paramsSel = array(
                        'User' => array(
                            'query' => $sqlSelect
                        )
                    );
                    $retornoSel = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramsSel );
					$company['Company'] = $retornoSel[0]['companies'];
					$company['CompanyPreference'] = $retornoSel[0]['companies_preferences']; */
		     $conditions = array(
                'Company' => array(
                    'conditions' => array(
                        'Company.responsible_email' => $email,
                        'Company.password' => $pass,
                        'Company.status' => 'ACTIVE'
                    )
                ),
                'CompanyPreference' => array()
            );
            $company = $this->AccentialApi->urlRequestToGetData('companies', 'first', $conditions); 
			
			
             if ((!empty($company ['status']) && $company ['status'] === 'GET_ERROR') || empty($company)) {//check if is secundary user
                $resultLogin = $this->secondaryUserLogin($email, $pass);
                if ($resultLogin['login_status'] === 'LOGIN_OK') {
                    $this->Session->write('sessionLogado', true);
                    $this->Session->write('CompanyLoggedIn', $resultLogin['company']);
                    $this->Session->write('userLoggedType', $resultLogin[0]['secondary_users']['secondary_type_id']);
                    $this->Session->write('secondUserLogado', true);
                    $this->Session->write('SecondaryUserLoggedIn', $resultLogin);
					
						
                } else {
				
					$sqlCheckStatus = "SELECT * FROM companies WHERE email LIKE '%{$email}%' and password LIKE '%{$pass}%';";
					$paramCheckStatus = array(
                        'User' => array(
                            'query' => $sqlCheckStatus
                        )
                    );
                    $checkCompStatus = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramCheckStatus);
					
					if(!empty($checkCompStatus) && $checkCompStatus[0]['companies']['status'] == 'INACTIVE'){
					
						$this->Session->setFlash(__('Essa conta está <i>inativa</i>, entre em contato com a equipe Jezzy.'));
                    $this->redirect("index");
					
					}else{
				
                    $this->Session->setFlash(__('Usuário ou senha inválidos.'));
                    $this->redirect("index");
					
					}
                }
            } else {
                $this->Session->write('sessionLogado', true);
                $this->Session->write('CompanyLoggedIn', $company);
                $this->Session->write('userLoggedType', 1);
				
				$plan = $this->getCompanyPlan($company['Company']['id']);
				// $this->redirect(array('controller' => 'Company', 'action' => 'selectPlan'));
					if(!empty($plan)){
					 $this->Session->write('CompanyPlan', $plan);

						if($plan[0]['company_plans']['plan'] == 'TRIAL' && $plan[0]['company_plans']['TRIAL_COUNTER'] == 0){
							$this->redirect(array('controller' => 'Company', 'action' => 'selectPlan'));
							break;
						}
					 
					}
				
            } 
            if (isset($this->request->data ['Company'] ['remember']) && $this->request->data ['Company'] ['remember'] === "true") {
                $this->Cookie->write('sessionLogado', true, time() + 172800);
                $this->Cookie->write('CompanyLoggedIn', $this->Session->read('CompanyLoggedIn'), time() + 172800);
                if ($this->Session->check('userLoggedType') === true) {
                    $this->Cookie->write('userLoggedType', $this->Session->read('userLoggedType'), time() + 172800);
                }
                if ($this->Session->check('secondUserLogado') === true) {
                    $this->Cookie->write('secondUserLogado', $this->Session->read('secondUserLogado'), time() + 172800);
                }
                if ($this->Session->check('SecondaryUserLoggedIn') === true) {
                    $this->Cookie->write('SecondaryUserLoggedIn', $this->Session->read('SecondaryUserLoggedIn'), time() + 172800);
                }
            } 
			
			$sqlCheckFirstLogin = "SELECT * FROM companies WHERE id = {$company['Company']['id']};";
					$paramCheckFirstLogin = array(
                        'User' => array(
                            'query' => $sqlCheckFirstLogin
                        )
                    );
                    $checkCompFirst= $this->AccentialApi->urlRequestToGetData('users', 'query', $paramCheckFirstLogin);
					
					if($checkCompFirst[0]['companies']['first_login'] == 1){
					
						   $this->Session->write('serviceFirstLogin', 1);
						   $this->Session->write('userFirstLogin', 1);
						   $this->Session->write('dashboardFirstLogin', 1);
						   
					}
			
			//print_r($company);
			//$this->Session->setFlash(__('Olha, é sucesso'.$email.' - '.$pass));
                  // $this->redirect("index");
            $this->redirect(array('controller' => 'Dashboard', 'action' => 'index'));
        }
        $this->redirect("index"); 
		}

    /**
     * Set new password to user and send e-mail with new password. 
     */
    public function sendPassword() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $email = $this->request->data ['Company'] ['email'];
            $this->GeneralFunctions = $this->Components->load('GeneralFunctions');
            $user = $this->getUserData($email);
            $pass = $this->GeneralFunctions->generateRandomPassword();
			
            $this->sendPasswordEmail($email, $pass);

            switch ($user['type']) {
                case 0:
                    $this->Session->setFlash(__('E-mail não encontrado.'));
                    $this->redirect("forgotPassword");
                    break;
                case 1:
                    $params = array(
                        'Company' => array(
                            'id' => $user['id'],
                            'email' => $email,
                            'password' => $pass
                        )
                    );
                    $updateSenha = $this->AccentialApi->urlRequestToSaveData('companies', $params);
                    break;
                case 2:
                    $query = "UPDATE secondary_users "
                            . " SET password =  '" . md5($pass) . "'"
                            . " WHERE id = " . $user['id'];
                    $params = array(
                        'User' => array(
                            'query' => $query
                        )
                    );
                    $updateSenha = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
                    break;
                default :
                    $this->Session->setFlash(__('Ops. Algo deu errado. Tente novamente.'));
                    $this->redirect("forgotPassword");
                    break;
            }
            if (is_null($updateSenha)) {
                $data['normalPass'] = $pass;
                $data['email'] = $email;
                $this->GeneralFunctions->postEmail('companies', 'newPass', $data);
                //TODO: remover o passa quando festiver tudo mais funcionando
                $this->Session->setFlash(__('Sua nova senha foi enviada por e-mail!'));
            }
        } else {
            $this->Session->setFlash(__('Ação desconhecida. Reinicie seu navegador e tente novamente'));
        }
        $this->redirect("index");
    }

    /**
     * On logout kill all session and cookies
     */
    public function logout() {
        $this->autoRender = false;
        $this->Session->destroy();
        $this->Cookie->destroy();
        $this->redirect("/Login/index");
    }

    // <editor-fold  defaultstate="collapsed" desc="Private Methods">
    /**
     * Check if user is a secundary user and send the information back
     * @param string $email
     * @param string $senha
     * @return string
     */
    private function secondaryUserLogin($email, $senha) {
        $query = "select * from secondary_users where email = '$email' and password = '$senha';";
        $params = array(
            'User' => array(
                'query' => $query
            )
        );
        $usuario = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
        if (!empty($usuario)) { //Check if user exists
            $usuario['login_status'] = 'LOGIN_OK';
            $conditions = array(
                'Company' => array(
                    'conditions' => array(
                        'Company.id' => $usuario[0]['secondary_users']['company_id']
                    )
                ),
                'CompanyPreference' => array()
            );
            $company = $this->AccentialApi->urlRequestToGetData('companies', 'first', $conditions);
            $usuario['company'] = $company;
        } else {
            $usuario['login_status'] = 'LOGIN_ERRO';
        }
        return $usuario;
    }

    /**
     * Get basic information about users. Used in forget password.
     * @param string $email
     * @return Array
     */
    private function getUserData($email) {
        $user['type'] = $conditions = array(
            'Company' => array(
                'conditions' => array(
                    'Company.responsible_email' => $email,
                    'Company.status' => 'ACTIVE'
                )
            ),
            'CompanyPreference' => array()
        );
        $retornData = $this->AccentialApi->urlRequestToGetData('companies', 'first', $conditions);
        if ((!empty($retornData ['status']) && $retornData ['status'] === 'GET_ERROR') || empty($retornData)) {
            $query = "select * from secondary_users where email = '{$email}';";
            $params = array(
                'User' => array(
                    'query' => $query
                )
            );
            $retornData = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
            if (isset($retornData[0]["secondary_users"]["id"])) {
                $user['type'] = 2;
                $user['id'] = $retornData[0]["secondary_users"]["id"];
            } else {
                $user['type'] = 0;
            }
        } else {
            $user['type'] = 1;
            $user['id'] = $retornData["Company"]["id"];
        }
        return $user;
    }

    // </editor-fold>


    public function sendPasswordEmail($email, $pass) {
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

        $mail->AddAddress("{$email}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

        $emailBody = '   <table border="0" cellpadding="0" cellspacing="0" >
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/mudanca-senha/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="background: #f2f2f2; text-align: center;">
                <td colspan="4">
                    <br/>
                    <span style="color: #999933; font-family: Helvetica, Arial, sans-serif; font-size: 36px;">
                        <i></i></span>
                    <br/>
                    <br/>
                </td>
            </tr>
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/mudanca-senha/02.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center; background: #f2f2f2;">
                    <span style="color: #2597ac; font-family: Helvetica, Arial, sans-serif; font-size: 16px;"><i>Senha: '.$pass.'</i></span>
                </td>
            </tr>
            <tr>
                <td colspan="4"><img src="https://secure.jezzy.com.br/uploads/Emails/files/mudanca-senha/03.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
             <tr>
                 <td colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/07.jpg" width="200" style="vertical-align: bottom;"/></td>
                 <td  colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/08.jpg" width="200"style="vertical-align: bottom;"/></td>
                 <td colspan="1"> <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/09.jpg"  width="200"style="vertical-align: bottom;"/></td>
                 <td colspan="1"><img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/10.jpg"  width="200" style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/11.jpg"  style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <img src="https://secure.jezzy.com.br/uploads/Emails/files/transacao-finalizada/12.jpg"  style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>
';

        $mail->Subject = "Nova senha"; // Assunto da mensagem
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
    }
	
	/*public function createMobileUser($name = null, $email = null, $password = null, $birthday = null, $phone = null, $regID = null, $system = null){
		$this->autoRender = false;
		
		$pass = md5($password);
		$name = $_POST['name'];
		$email = $_POST['email'];
		$birthday = $_POST['birthday'];
		$phone = $_POST['phone']; 
		
		$sql = "INSERT INTO users(
		`name`, 
		`email`, 
		`password`,
		`birthday`, 
		`phone`)
		VALUES(
		'{$name}',
		'{$email}',
		'{$pass}',
		'{$birthday}',
		'{$phone}'
		)";
		
		$param = array(
            'User' => array(
                'query' => $sql
            )
        );

        $retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $param);
		
		
		$sqlSelect = "select * from users where email LIKE '%{$email}%';";
		$paramSelect = array(
            'User' => array(
                'query' => $sqlSelect
            )
        );

        $retornoSelect = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramSelect);
		
		$paramUsersUsing = '';
		if($system == 'ANDROID'){
		
		$paramUsersUsing = "INSERT INTO users_using(
		`user_id`,
		`mobile`,
		`android`,
		`ios`, 
		`reg_id`)
		VALUES(
		{$retornoSelect[0]['users']['id']},
		'ACTIVE',
		'ACTIVE',
		'INACTIVE',
		'{$regID}')";
		}else if($system == 'IOS'){
		$paramUsersUsing = "INSERT INTO users_using(
		`user_id`,
		`mobile`,
		`android`,
		`ios`, 
		`reg_id`)
		VALUES(
		{$retornoSelect[0]['users']['']},
		'ACTIVE',
		'INACTIVE',
		'ACTIVE',
		'{$regID}')";
		}
		
		$paramUUsing = array(
            'User' => array(
                'query' => $paramUsersUsing
            )
        );

        $retornoUU = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramUUsing);
		
		echo $paramUsersUsing;
			$data['CREATE_STATUS'] = 'OK';
			print_r(json_encode($data));
		
		
	
	} */
	
	public function createMobileUser($name = null, $email = null, $password = null, $birthday = null, $phone = null, $regID = null, $system = null, $isFacebook = false){
		$this->autoRender = false;
		
		//VERIFICA SE USUÁRIO COM MESMO EMAIL JÁ EXISTE
		$sqlCheckUser = "select * from users where email LIKE '{$email}';";
		$paramCheckUser = array(
            'User' => array(
                'query' => $sqlCheckUser
            )
        );
        $retornoCheckUser = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramCheckUser);
		
		//CASO USUARIO JA EXISTA
    if(!empty($retornoCheckUser)){
		
        $data['CREATE_STATUS'] = 'ERROR';
            
        print_r(json_encode($data, JSON_ERROR_UTF8));
        //echo "ERROR";
    }else{
	
		$userPhoto = 'https://secure.jezzy.com.br/jezzy-mobile/public_html/img/icons/Foto%20do%20usuario%20-%2001.png';
		
		if($isFacebook != null){
		
			if($isFacebook == true){
				$userPhoto =  "https://graph.facebook.com/{$password}/picture?type=large";
			}	
		
		}
	
      //  $id = $password;
		$pass = md5($password);
		/*$name = $_POST['name'];
		$email = $_POST['email'];
		$birthday = $_POST['birthday'];
		$phone = $_POST['phone']; */
		
		$atual = date('Y-m-d H:i:s');
		
		$sql = "INSERT INTO users(
		`name`, 
		`email`, 
		`password`,
		`birthday`, 
		`phone`,
        `photo`,
		`date_register`,
		`last_update`)
		VALUES(
		'{$name}',
		'{$email}',
		'{$pass}',
		'{$birthday}',
		'{$phone}',
		'{$userPhoto}',
		'{$atual}',
		'{$atual}'
		)";
		
		$param = array(
            'User' => array(
                'query' => $sql
            )
        );

        $retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $param);
		
		
		$sqlSelect = "select * from users where email LIKE '%{$email}%';";
		$paramSelect = array(
            'User' => array(
                'query' => $sqlSelect
            )
        );

        $retornoSelect = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramSelect);
		
		$paramUsersUsing = '';
		if($system == 'ANDROID'){
		$regID = ''.$regID;
		$paramUsersUsing = "INSERT INTO users_using(
		`user_id`,
		`mobile`,
		`android`,
		`ios`, 
		`red_id`,
        `reg_id`,
        `registration_id`)
		VALUES(
		{$retornoSelect[0]['users']['id']},
		'ACTIVE',
		'ACTIVE',
		'INACTIVE',
		'{$regID}',
        '{$regID}',
		'{$regID}')";
		}else if($system == 'IOS'){
		$paramUsersUsing = "INSERT INTO users_using(
		`user_id`,
		`mobile`,
		`android`,
		`ios`, 
		`red_id`,
        `reg_id`,
        `registration_id`)
		VALUES(
		{$retornoSelect[0]['users']['id']},
		'ACTIVE',
		'INACTIVE',
		'ACTIVE',
		'{$regID}',
        '{$regID}',
		'{$regID}')";
		}
		
		$paramUUsing = array(
            'User' => array(
                'query' => $paramUsersUsing
            )
        );

        $retornoUU = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramUUsing);
        
        //Cria FB Profile
        
        $sqlFBProfile = "INSERT INTO facebook_profiles(
        `facebook_id`,
        `user_id`,
        `name`,
        `email`,
        `birthday`)
        VALUES(
               '000000000000000',
               '{$retornoSelect[0]['users']['id']}',
               '{$name}',
               '{$email}',
               '{$birthday}'
               )";
        
        $paramFBProfile = array(
                       'User' => array(
                                       'query' => $sqlFBProfile
                                       )
                       );
        
        $retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramFBProfile);
        
        //Cria Offers_Preferences Profile
        
        $sqlOPProfile = "INSERT INTO users_preferences(
        `user_id`)
        VALUES(
               '{$retornoSelect[0]['users']['id']}'
               )";
        
        $paramOPProfile = array(
                                'User' => array(
                                                'query' => $sqlOPProfile
                                                )
                                );
        
        $retornoOP = $this->AccentialApi->urlRequestToGetData('users', 'query', $paramOPProfile);
        
        
        //$this->sendNewUserMobileEmail($email, $password, $name);
		$this->sendNewUserMobileEmail_BYPOST($email, $password, $name);
		
		//echo $paramUsersUsing;

            
            $data['CREATE_STATUS'] = 'OK';
			print_r(json_encode($data, JSON_ERROR_UTF8));
			//echo "OK";
		}
	
	}
	
	/**
	$email = ENDERECO DE EMAIL USADO PELO USUARIO PARA EFETURAR O Cadastro
	$password = SENHA DO USUARIO USADA PARA EFETURAR O CADASTRO 
	$regID = ID UNICOD GERADO PELO PUSHBOTS PARA CADA DEVICE, USADO PARA O ENVIO E RECEBIMENTO DE NOTIFICACOES
	$deviceOS = SISTEMA OPERACIONAL DO DEVICE (IOS/ ANDROID)
	**/
	public function userMobileLogin($email = null, $password = null, $regID = null, $deviceOS = null){
	$this->autoRender= false;


		if($deviceOS == null){
			$deviceOS = 'IOS';
		}
	
		$pass = md5($password);
		$sql = "select * from users where email = '{$email}' and password = '{$pass}';";
		
		$param = array(
            'User' => array(
                'query' => $sql
            )
        );

        $retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $param);
		
		$id = $retorno[0]['users']['id'];
		

		$this->changeUserRegistrationID($id, $regID, $deviceOS);	
	
		
		//ALTERANDO REG ID DO USUARIO
		/*$sqlUpdate = "update users_using set `reg_id` = 'REG IS TRA TION' where user_id = {$id};";
		$paramUpdate = array(
            'User' => array(
                'query' => $sqlUpdate
            )
        );

        $this->AccentialApi->urlRequestToGetData('users', 'query', $paramUpdate); */
		
		
		if(!empty($retorno)){ 
		
			$retorno[0]['users']['reg_id'] = $regID;
			$retorno[0]['users']['LOGIN_STATUS'] = 'OK';
			echo json_encode($retorno[0]['users'], JSON_ERROR_UTF8);
			//echo $retorno[0]['users']['id'];
			
		}else{
			
			$retorno[0]['users']['LOGIN_STATUS'] = 'ERROR';
			json_encode($retorno[0]['users'], JSON_ERROR_UTF8);
			//echo 0;
		}
		
		$this->changeUndefiniedRegId();
	
	}
	
	public function changeUserRegistrationID($userId, $reg, $deviceOS){
	
	$sql = "";
	
			if($deviceOS == 'ANDROID'){
		
		//ALTERANDO REG ID DO USUARIO
		$sql = "UPDATE users_using SET `red_id` = '{$reg}', `reg_id` = '{$reg}', `registration_id` = '{$reg}', `ios` = 'INACTIVE', `android` = 'ACTIVE'  WHERE user_id = {$userId};";
		$param = array(
            'User' => array(
                'query' => $sql
            )
        );
		
		}else if($deviceOS == 'IOS'){
			
		//ALTERANDO REG ID DO USUARIO
		$sql = "UPDATE users_using SET `red_id` = '{$reg}', `reg_id` = '{$reg}', `registration_id` = '{$reg}', `ios` = 'ACTIVE', `android` = 'INACTIVE'  WHERE user_id = {$userId};";
		$param = array(
            'User' => array(
                'query' => $sql
            )
        );
	}

        $this->AccentialApi->urlRequestToGetData('users', 'query', $param);
	
	}
	
	
	public function changeUndefiniedRegId(){
	$this->autoRender = false;

		$sql = "SELECT * FROM users_using WHERE reg_id = 'undefined';";
		$param = array(
            'User' => array(
                'query' => $sql
            )
        );

        $users = $this->AccentialApi->urlRequestToGetData('users', 'query', $param);		
		
		if(!empty($users)){
			foreach($users as $user){
			
				$up = "UPDATE users_using SET `reg_id` = '{$user['users_using']['red_id']}' WHERE id = {$user['users_using']['id']};";
				$paramup = array(
            'User' => array(
                'query' => $up
            )
        );

        $this->AccentialApi->urlRequestToGetData('users', 'query', $paramup);	
			
			}
		}
	}
	
	
	public function professionalMobileLogin($email = null, $password = null, $regID = null, $SO = null){
	
		$this->autoRender= false;

			
		$android_SO= 'INACTIVE';
		$ios_SO= 'INACTIVE';
		if($SO == 'ANDROID'){
			$android_SO= 'ACTIVE';
			
		}else if($SO == 'IOS'){
			$ios_SO = 'ACTIVE';
		}
		
		$pass = md5($password);
		$sql = "select * from secondary_users where email = '{$email}' and password = '{$pass}';";
		
		$param = array(
            'User' => array(
                'query' => $sql
            )
        );

        $retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $param);
		
		
		//ALTERANDO REG ID DO USUARIO
		$sqlUpdate = "update secondary_users_using set `registration_id` = '{$regID}', `android` = '{$android_SO}', `ios` = '{$ios_SO}' where secondary_users_id = {$retorno[0]['secondary_users']['id']};";
		$paramUpdate = array(
            'User' => array(
                'query' => $sqlUpdate
            )
        );

        $this->AccentialApi->urlRequestToGetData('users', 'query', $paramUpdate);
		
		//caso seja primeiro login, mudamos o first_login para 0
		/* if($retorno[0]['secondary_users']['first_login'] == 0){
		
			$update = "UPDATE secondary_users SET `first_login` = 1 WHERE id = {$retorno[0]['secondary_users']['id']};";
			$paramUpdate = array(
            'User' => array(
                'query' => $update
            )
        );

        $this->AccentialApi->urlRequestToGetData('users', 'query', $paramUpdate);
		
		} */
		
		if(!empty($retorno)){ 
		
			$retorno[0]['secondary_users']['LOGIN_STATUS'] = 'OK';
			echo json_encode($retorno[0]['secondary_users'], JSON_ERROR_UTF8);
			//echo $retorno[0]['users']['id'];
			
		}else{
			
			$retorno[0]['secondary_users']['LOGIN_STATUS'] = 'ERROR';
			json_encode($retorno[0]['secondary_users'], JSON_ERROR_UTF8);
			//echo 0;
		}
	
	}
	
	public function autoLogin(){
		$this->autoRender = false;
		
		$data['dado1'] = "dado1";
		$data['dado2'] = "dado2";
		$data['dado3'] = "dado3";
		$data['dado4'] = "dado4s";
	
		echo json_encode($data);
	}
	
	/**
	Email será enviado caso usuário seja cadastrado pelas aplicações Android e IOS
	*/
	public function sendNewUserMobileEmail($email, $pass, $name) {
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

        $mail->AddAddress("{$email}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

        $emailBody = "<table border='0' cellpadding='0' cellspacing='0' ><tr><td colspan='4'><img src='https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas/01.jpg' width='600' style='vertical-align: bottom;'/></td></tr><tr style='background: #f7f7f7; text-align: center;'><td colspan='4'><br/><span style='color: #999933; font-family: Helvetica, Arial, sans-serif; font-size: 36px;'><i>".$name.", seja bem-vindo!</i></span><br/><br/></td></tr><tr><td colspan='4'><img src='http://www.schabla.com.br/jezzy_images/boas-vindas/03.jpg' width='600' style='vertical-align: bottom;'/></td>            </tr><tr style='background: #f7f7f7;'><td colspan='4' style='text-align: center;'><span style='color: #2597AC; font-size: 12px;  font-family: Helvetica, Arial, sans-serif;'>                        <br/><b>E-mail: ".$email."<br/>Senha:".$pass."</b><br/></span></td></tr><tr style='background: #f7f7f7;'><td colspan='4'><br/><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/04.jpg' width='600'/><br/></td></tr><tr style='background: #f7f7f7; width: 600px;'><td style='width: 50px;' colspan='1'></td><td style='width: 150px; text-align: right;' colspan='1'><a href='#'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/App%20Store.png' width='80'/></a></td><td style='width: 150px; text-align: left;' colspan='1'><a href='#'> <img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/Google%20Play.png' width='80'/></a></td><td style='width: 50px;' colspan='1'></td></tr><tr><td colspan='4'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/05-1.jpg' width='600' height='30' style='vertical-align: bottom;'/></td></tr><tr><td colspan='4'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/06.jpg' width='600' style='vertical-align: bottom;'/></td></tr><tr><td colspan='1'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/07.jpg' width='151' style='vertical-align: bottom;'/></td><td  colspan='1'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/08.jpg' width='151' style='vertical-align: bottom;'/></td>     <td colspan='1'> <img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/09.jpg' width='151' style='vertical-align: bottom;'/></td><td colspan='1'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/10.jpg' width='151' style='vertical-align: bottom;'/></td></tr><tr><td colspan='4'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/11.jpg' width='600' style='vertical-align: bottom;'/></td></tr><tr><td colspan='4'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/12.jpg' width='600' style='vertical-align: bottom;'/>  </td></tr></table>";
 

        $mail->Subject = "Jezzy - Cadastro efetuado com sucesso"; // Assunto da mensagem
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
    }
	
	
		public function sendNewUserMobileEmail_BYPOST($email, $pass, $name) {
      
        $emailBody = "<table border='0' cellpadding='0' cellspacing='0' ><tr><td colspan='4'><img src='https://secure.jezzy.com.br/uploads/Emails/files/boas-vindas/01.jpg' width='600' style='vertical-align: bottom;'/></td></tr><tr style='background: #f7f7f7; text-align: center;'><td colspan='4'><br/><span style='color: #999933; font-family: Helvetica, Arial, sans-serif; font-size: 36px;'><i>".$name.", seja bem-vindo!</i></span><br/><br/></td></tr><tr><td colspan='4'><img src='http://www.schabla.com.br/jezzy_images/boas-vindas/03.jpg' width='600' style='vertical-align: bottom;'/></td>            </tr><tr style='background: #f7f7f7;'><td colspan='4' style='text-align: center;'><span style='color: #2597AC; font-size: 12px;  font-family: Helvetica, Arial, sans-serif;'>                        <br/><b>E-mail: ".$email."<br/>Senha:".$pass."</b><br/></span></td></tr><tr style='background: #f7f7f7;'><td colspan='4'><br/><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/04.jpg' width='600'/><br/></td></tr><tr style='background: #f7f7f7; width: 600px;'><td style='width: 50px;' colspan='1'></td><td style='width: 150px; text-align: right;' colspan='1'><a href='#'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/App%20Store.png' width='80'/></a></td><td style='width: 150px; text-align: left;' colspan='1'><a href='#'> <img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/Google%20Play.png' width='80'/></a></td><td style='width: 50px;' colspan='1'></td></tr><tr><td colspan='4'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/05-1.jpg' width='600' height='30' style='vertical-align: bottom;'/></td></tr><tr><td colspan='4'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/06.jpg' width='600' style='vertical-align: bottom;'/></td></tr><tr><td colspan='1'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/07.jpg' width='151' style='vertical-align: bottom;'/></td><td  colspan='1'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/08.jpg' width='151' style='vertical-align: bottom;'/></td>     <td colspan='1'> <img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/09.jpg' width='151' style='vertical-align: bottom;'/></td><td colspan='1'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/10.jpg' width='151' style='vertical-align: bottom;'/></td></tr><tr><td colspan='4'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/11.jpg' width='600' style='vertical-align: bottom;'/></td></tr><tr><td colspan='4'><img src='http://www.schabla.com.br/jezzy_images/transacao-finalizada/12.jpg' width='600' style='vertical-align: bottom;'/>  </td></tr></table>";
 

		$this->sendEmailByPOST($emailBody, $email, "Jezzy - Cadastro efetuado com sucesso");
		
    }
	
	public function getCompanyPlan($companyID){
		
		$sql = "SELECT * FROM company_plans WHERE company_id = {$companyID};";
		
		$param = array(
            'User' => array(
                'query' => $sql
            )
        );

        $retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $param);
		
		 return $retorno;
	
	}
	
	/**
	* Essa função será sempre executada logo após o login, é responsável por validar se usuário Company está em dia com seu plano, TRIAL
	*/
	public function verifyTRIALPeriodicity($plan){
	
			if($plan[0]['company_plans']['plan'] === 'TRIAL' && $plan[0]['company_plans']['TRIAL_COUNTER'] === 0){
				
				$this->redirect(array('controller' => 'Company', 'action' => 'selectPlan'));
			
			}
	
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
