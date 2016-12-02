<?php $this->Html->css('View/Settings.index', array('inline' => false)); ?>
<?php $this->Html->script('View/Settings.index', array('inline' => false)); ?>
<h1 class="page-header"  style="margin-top: -38px;">Minha Empresa</h1>
<form action="<?php echo $this->Html->url("index"); ?>" id="" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <h4 class="panel-title">Dados Gerais</h4>
                </a>
            </div>
            <div id="collapseOne" class="panel-collapse collapse out">
                <div class="panel-body">
				
                    <div class="row">
                        <div class="col-md-3">
						 <label>Logo</label><br/>
						 <div class="company-logo">
                            <!-- <img id="comp-logo-preview" src="" class="sampleImageComapny" alt="Click to Upload" /><br/>-->
							  <img id="comp-logo-preview" src="<?php echo $company['Company']['logo']; ?>" class="sampleImageComapny" alt="Click to Upload" /><br/>
						</div>
                            <input type="file" id="comp-logo-upper" name="data[Company][logo]" class="inputFileHide"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Razão Social</label>
                            <input name="data[Company][corporate_name]" value="<?php echo $company['Company']['corporate_name']; ?>" disabled type="text" class="form-control" id="companyName" placeholder="Razão Social">
                        </div>
                    </div>      
                    <div class="row">
                        <div class="col-md-8">
                            <label>Nome fantasia</label>
                            <input name="data[Company][fancy_name]" value="<?php echo $company['Company']['fancy_name']; ?>" type="text" class="form-control" id="companyName" placeholder="Nome fantasia">
                        </div>
                        <div class="col-md-4">
                            <label>CNPJ</label>
                            <input name="data[Company][cnpj]" value="<?php echo $company['Company']['cnpj']; ?>" disabled type="text" class="form-control" id="companyName" placeholder="CNPJ">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Telefone 1</label>
                            <input name="data[Company][phone]" value="<?php echo $company['Company']['phone']; ?>" type="text" class="form-control" id="companyName" placeholder="Telefone">
                        </div>
                        <div class="col-md-4">
                            <label>Telefone 2</label>
                            <input name="data[Company][phone_2]" value="<?php echo $company['Company']['phone_2']; ?>" type="text" class="form-control" id="companyName" placeholder="Telefone">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label>E-mail de contato</label>
                            <input name="data[Company][email]" value="<?php echo $company['Company']['email']; ?>" type="text" class="form-control" id="companyName" placeholder="E-mail de contato">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>CEP</label>
                            <input name="data[Company][zip_code]" value="<?php echo $company['Company']['zip_code']; ?>" type="text" class="form-control" id="companyName" placeholder="CEP">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Endereço</label>
                            <input name="data[Company][address]" value="<?php echo $company['Company']['address']; ?>" type="text" class="form-control" id="companyName" placeholder="Endereço">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <label>Número</label>
                            <input name="data[Company][number]" value="<?php echo $company['Company']['number']; ?>" type="text" class="form-control" id="companyName">
                        </div>
                        <div class="col-md-6">
                            <label>Bairro</label>
                            <input name="data[Company][district]" value="<?php echo $company['Company']['district']; ?>" type="text" class="form-control" id="companyName" placeholder="Bairro">
                        </div>
                        <div class="col-md-1">
                            <label>UF</label>
                            <input name="data[Company][state]" value="<?php echo $company['Company']['state']; ?>" type="text" class="form-control" id="companyName" placeholder="UF">
                        </div>
                        <div class="col-md-4">
                            <label>Cidade</label>
                            <input name="data[Company][city]" value="<?php echo $company['Company']['city']; ?>" type="text" class="form-control" id="companyName" placeholder="Cidade">
                        </div>
                    </div>
					
					<!-- RESPONSAVEL PELA EMPRESA -->
					<div class="row">
						
							<h1 class="col-md-12">Dados do Responsável</h1>
						
					</div>
					
					<div class="row">
                        <div class="col-md-8">
                            <label>Nome</label>
                            <input name="data[Company][responsible_name]" value="<?php echo $company['Company']['responsible_name']; ?>" type="text" class="form-control" id="responsibleCompanyName" disabled>
                        </div>
						
						<div class="col-md-4">
                            <label>CPF</label>
                            <input name="data[Company][responsible_cpf]" value="<?php echo $company['Company']['responsible_cpf']; ?>" type="text" class="form-control" id="responsibleCompanyCPF" disabled>
                        </div>
					</div>
					
					
					<div class="row">
                        <div class="col-md-8">
                            <label>Email</label>
                            <input name="data[Company][responsible_email]" value="<?php echo $company['Company']['responsible_email']; ?>" type="text" class="form-control" id="responsibleCompanyEmail" disabled>
                        </div>
						<div class="col-md-4">
                            <label>Data de Nascimento</label>
                            <input name="data[Company][responsible_birthday]" value="<?php echo date('d/m/Y', strtotime($company['Company']['responsible_birthday'])); ?>" type="text" class="form-control" id="responsibleCompanyBirthday" disabled>
                        </div>
					</div>
					<div class="row">
						<div class="col-md-4">
                            <label>Telefone 1</label>
                            <input name="data[Company][responsible_phone]" value="<?php echo $company['Company']['responsible_phone']; ?>" type="text" class="form-control" id="responsibleCompanyPhone" disabled>
                        </div>
						<div class="col-md-4">
                            <label>Telefone 2</label>
                            <input name="data[Company][responsible_phone_2]" value="<?php echo $company['Company']['responsible_phone_2']; ?>" type="text" class="form-control" id="responsibleCompanyPhone2" disabled>
                        </div>
						<div class="col-md-4">
                            <label>Telefone 3</label>
                            <input name="data[Company][responsible_cell_phone]" value="<?php echo $company['Company']['responsible_cell_phone']; ?>" type="text" class="form-control" id="responsibleCompanyCellPhone" disabled>
                        </div>
					</div>
                </div>
            </div>
        </div>
        <div class="panel panel-default" id="opcoesDeFrete">
            <div class="panel-heading">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    <h4 class="panel-title">Opções de frete</h4>
                </a>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse out">
                <div class="panel-body">
                    <div class="row">
                        <div class="btn-group col-md-4 " data-toggle="buttons">Vai usar API dos correios?</div>
                    </div>
                    <div class="row">
                        <div class="btn-group col-md-4" data-toggle="buttons">
                            <?php
                            $useAPI = "";
                            $doNotUseAPI = "";
                            $useAPIActive = "";
                            $doNotUseAPIActive = "";
                            if ($companyPreference[0]['company_preferences']['use_correios_api'] == 1   ) {
                                $useAPI = "checked='checked'";
                                $useAPIActive = "active";
                            } else {
                                $doNotUseAPI = "checked='checked'";
                                $doNotUseAPIActive = "active";
                            }
                            ?>
                            <label class="btn btn-default <?php echo $useAPIActive;  ?>">
                                <input name="data[CompanyPreference][use_correios_api]" id="useAPI" type="radio" value="1" <?php echo $useAPI; ?> /> <span class="glyphicon glyphicon-thumbs-up"> Sim</span>
                            </label> 
                            <label class="btn btn-default  <?php echo $doNotUseAPIActive;  ?>">
                                <input name="data[CompanyPreference][use_correios_api]" id="dontUseAPI" type="radio" value="2" <?php echo $doNotUseAPI; ?> /> <span class="glyphicon glyphicon-thumbs-down"> Não</span>
                            </label> 
                        </div>
                    </div>
                    <div class="row" id="sendTax">
                        <div class="col-md-3">
                            <label>Valor Fixo</label>
                            <input name="data[CompanyPreference][shipping_value]" value="<?php echo number_format($companyPreference[0]['company_preferences']['shipping_value'], 2, ',', '.'); ?>" type="text" class="form-control" id="companyName" placeholder="Valor">
                        </div>
                        <div class="col-md-3">
                            <label>Dias para entrega</label>
                            <input name="data[CompanyPreference][delivery_time]" value="<?php echo $companyPreference[0]['company_preferences']['delivery_time']; ?>" type="text" class="form-control" id="companyName" placeholder="Nº dias">
                        </div>
                    </div>
					
					<div class="col-md-12 help-icon">
								<?php echo $this->Html->image('jezzy_icons/help-icon.png'); ?>
					</div>
                </div>
            </div>
        </div>
		
		<div class="panel panel-default">
            <div class="panel-heading">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapsePreferences">
                    <h4 class="panel-title">Preferências do Salão</h4>
                </a>
            </div>
			 <div id="collapsePreferences" class="panel-collapse collapse out">
                <div class="panel-body">
                    <div class="col-md-12">
						<div class="col-md-6">
							<label>Horário de Abertura:</label>
							<select class="form-control" id="data[Company][open_hour]" name="data[Company][open_hour]">
								<option value="<?php echo $company['Company']['open_hour']; ?>"><?php echo $company['Company']['open_hour']; ?></option>
								<option value="07:00">07:00</option>
								<option value="07:30">07:30</option>
								<option value="08:00">08:00</option>
								<option value="08:30">08:30</option>
								<option value="09:00">09:00</option>
								<option value="09:30">09:30</option>
								<option value="10:00">10:00</option>
								<option value="10:30">10:30</option>
								<option value="11:00">11:00</option>
								<option value="11:30">11:30</option>
								<option value="12:00">12:00</option>
								<option value="12:30">12:30</option>
							</select>
						</div>
						
						<div class="col-md-6">
							<label>Horário de Fechamento:</label>
							<select class="form-control"  id="data[Company][close_hour]" name="data[Company][close_hour]">
							<option value="<?php echo $company['Company']['close_hour']; ?>"><?php echo $company['Company']['close_hour']; ?></option>
								<option value="13:00">13:00</option>
								<option value="13:30">13:30</option>
								<option value="14:00">14:00</option>
								<option value="14:30">14:30</option>
								<option value="15:00">15:00</option>
								<option value="15:30">15:30</option>
								<option value="16:00">16:00</option>
								<option value="16:30">16:30</option>
								<option value="17:00">17:00</option>
								<option value="17:30">17:30</option>
								<option value="18:00">18:00</option>
								<option value="18:30">18:30</option>
								<option value="19:00">19:00</option>
								<option value="19:30">19:30</option>
								<option value="20:00">20:00</option>
								<option value="20:30">20:30</option>
								<option value="21:00">21:00</option>
								<option value="21:30">21:30</option>
							</select>
						</div>
						
						<div class="col-md-12">
						<br/><br/>
						<label for="">Dias de funcionamento:</label>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Dom</th>
                                <th class="text-center">Seg</th>
                                <th class="text-center">Ter</th>
                                <th class="text-center">Qua</th>
                                <th class="text-center">Qui</th>
                                <th class="text-center">Sex</th>
                                <th class="text-center">Sab</th>
                            </tr>
                            <tr class="text-center">
                                <td>
                                    <input type="checkbox"  id="dom" val="dom" name="data[Company][workDays][dom]"  <?php if(strpos($company['Company']['work_days'], 'dom') !== false){echo "checked='checked'";}?> />
                                </td>
                                <td>
                                    <input type="checkbox" id="seg" val="seg" name="data[Company][workDays][seg]" <?php if(strpos($company['Company']['work_days'], 'seg') !== false){echo "checked='checked'";}?>/>
                                </td>
                                <td>
                                    <input type="checkbox"  id="ter" val="ter" name="data[Company][workDays][ter]" <?php if(strpos($company['Company']['work_days'], 'ter') !== false){echo "checked='checked'";}?>/>
                                </td>
                                <td>
                                    <input type="checkbox" id="qua" val="qua" name="data[Company][workDays][qua]" <?php if(strpos($company['Company']['work_days'], 'qua') !== false){echo "checked='checked'";}?>/>
                                </td>
                                <td>
                                    <input type="checkbox" id="qui" val="qui" name="data[Company][workDays][qui]" <?php if(strpos($company['Company']['work_days'], 'qui') !== false){echo "checked='checked'";}?>/>
                                </td>
                                <td>
                                    <input type="checkbox" id="sex" val="sex" name="data[Company][workDays][sex]" <?php if(strpos($company['Company']['work_days'], 'sex') !== false){echo "checked='checked'";}?>/>
                                </td>
                                <td>
                                    <input type="checkbox" id="sab" val="sab" name="data[Company][workDays][sab]" <?php if(strpos($company['Company']['work_days'], 'sab') !== false){echo "checked='checked'";}?>/>
                                </td>
                            </tr>
                        </thead>
                    </table>
						</div>
					
					</div>
				</div>
			</div>
		</div>
		
        <div class="panel panel-default">
            <div class="panel-heading">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                    <h4 class="panel-title">Redes Sociais</h4>
                </a>
            </div>
            <div id="collapseThree" class="panel-collapse collapse out">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-inline marginTop10">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <?php echo $this->Html->image('socialnetwork/icon-facebook.png', array('class' => 'imgSocialNetwork')); ?>
                                    </span>
                                    <input name="fbkLink" value="<?php echo $social['fbk_link']; ?>" type="text" class="form-control" placeholder="Facebook">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <?php 
                                        $checked = "checked='checked'";
                                        if ($social['fbk_new_offers'] == 'INACTIVE') {
                                            $checked = '';
                                        }
                                        ?>
                                        <input value="ACTIVE" name="fbkOffers" type="checkbox" <?php echo $checked; ?> class="social-check"> <span class="social-check">Compartilhar Nova oferta</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-inline marginTop10">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <?php echo $this->Html->image('socialnetwork/icon-plus.png', array('class' => 'imgSocialNetwork')); ?>
                                    </span>
                                    <input name="gplusLink" value="<?php echo $social['gplus_link']; ?>" type="text" class="form-control" placeholder="Google Plus">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <?php 
                                        $checked = "checked='checked'";
                                        if ($social['gplus_new_offers'] == 'INACTIVE') {
                                            $checked = '';
                                        }
                                        ?>
                                        <input value="ACTIVE" name="gplusOffers" type="checkbox" <?php echo $checked; ?> class="social-check"> <span class="social-check">Compartilhar Nova oferta</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-inline marginTop10">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <?php echo $this->Html->image('socialnetwork/icon-twitter.png', array('class' => 'imgSocialNetwork')); ?>
                                    </span>
                                    <input name="twtLink" value="<?php echo $social['twitter_link']; ?>" type="text" class="form-control" placeholder="Twitter">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <?php 
                                        $checked = "checked='checked'";
                                        if ($social['twitter_new_offers'] == 'INACTIVE') {
                                            $checked = '';
                                        }
                                        ?>
                                        <input value="ACTIVE" name="twtOffers" type="checkbox" <?php echo $checked; ?> class="social-check"> <span class="social-check">Compartilhar Nova oferta</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-12 help-icon">
								<?php echo $this->Html->image('jezzy_icons/help-icon.png'); ?>
					</div>
                </div>
            </div>
        </div>
    
		<?php if(!empty($company['Company']['moip_account'])) {?>
		 <div class="panel panel-default">
            <div class="panel-heading">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseMoip">
                    <h4 class="panel-title">Conta MoIP</h4>
                </a>
            </div>
			 <div id="collapseMoip" class="panel-collapse collapse out">
                <div class="panel-body">
                    <div class="col-md-12">
						Para configurar e ter acesso à sua conta MoIP, clique <a href="<?php echo $company['Company']['link_setpassword_moip']; ?>" target="_blank">aqui</a>. 
					</div>
				</div>
			</div>
		</div>
		<?php }?>
	
	</div>
    <div class="panel-body">
        <button type="submit" class="btn btn-success pull-right">Salvar</button>
    </div>
</form>