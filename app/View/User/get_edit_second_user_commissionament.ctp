
<div>

					<div class="form-group">
					<input type="hidden" id="EDIT_commissionament_id" value="<?php echo $commissioningFee[0]['secondary_users_commissioning_fees']['id']; ?>" />
                        <label for="" >Comissão por Produto do Salão (%)</label>
                        <div >
                            <input name="EDIT_rate_per_company_product" type="number" class="form-control"  id="EDIT_rate_per_company_product" value="<?php echo $commissioningFee[0]['secondary_users_commissioning_fees']['rate_per_company_product']; ?>" >
                        </div>
                    </div>
					
					
					<div class="form-group">
                        <label for="" >Comissão por Produto Jezzy (%)</label>
                        <div >
                            <input name="EDIT_rate_per_jezzy_product" type="number" class="form-control"  id="EDIT_rate_per_jezzy_product" placeholder="E-mail" value="<?php echo $commissioningFee[0]['secondary_users_commissioning_fees']['rate_per_jezzy_product']; ?>">
                        </div>
                    </div>
					
					
					<div class="form-group">
                        <label for="inputPassword3" >Comissão por Serviço (%)</label>
                        <div>
                            <input name="EDIT_rate_per_service" type="number" class="form-control"  id="EDIT_rate_per_service" placeholder="E-mail" value="<?php echo $commissioningFee[0]['secondary_users_commissioning_fees']['rate_per_service']; ?>">
                        </div>
                    </div>

</div>