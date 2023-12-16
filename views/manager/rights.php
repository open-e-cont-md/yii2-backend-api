<?php
//use yii\helpers\Html;
use openecontmd\backend_api\models\Terms;

$this->title = 'User Rights'; //Terms::translate('settings', 'cabinet');


//$this->registerJs('$("input[data-bootstrap-switch]").each(function(){$(this).bootstrapSwitch("state", $(this).prop("checked"));})', 1);



?>
<style>
<!--
.table td, .table th {
  padding: 0.6rem 0 0 0;
  font-size: 1.1rem;
}
-->
</style>

        <!-- /.row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
<?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'rights-form']) ?>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-wrap">
                  <thead>
                    <tr class="text-nowrap">
                      <th width="5%" style="padding-bottom:0.6rem"><b class="text-secondary">#</b></th>
                      <th width="*" style="padding-bottom:0.6rem"><b class="text-secondary">Description</b></th>
                      <th width="10%" style="padding-bottom:0.6rem" class="text-center"><b class="text-secondary">Service Admin</b></th>
                      <th width="10%" style="padding-bottom:0.6rem" class="text-center"><b class="text-secondary">Senior Manager</b></th>
                      <th width="10%" style="padding-bottom:0.6rem" class="text-center"><b class="text-secondary">Manager</b></th>
                      <th width="10%" style="padding-bottom:0.6rem" class="text-center"><b class="text-secondary">Guest (Read-only)</b></th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr style="background-color:#f0f0ff">
                      <td style="padding-bottom:0.6rem"><b class="text-secondary">1.</b></td>
                      <td><b class="text-secondary">Account information:</b></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>View and edit account details, address & company details, delete e-Cont.md account</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-1-1-a" value="1" checked><label class="custom-control-label" for="sw-1-1-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-1-1-b" value="1" checked><label class="custom-control-label" for="sw-1-1-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-1-1-c" value="1"><label class="custom-control-label" for="sw-1-1-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-1-1-d" value="1"><label class="custom-control-label" for="sw-1-1-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>

                    <tr style="background-color:#f0f0ff">
                      <td style="padding-bottom:0.6rem"><b class="text-secondary">2.</b></td>
                      <td><b class="text-secondary">Subscription page:</b></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Manage subscription of e-Cont.md servcies </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-2-1-a" value="1" checked><label class="custom-control-label" for="sw-2-1-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-2-1-a" value="1" checked><label class="custom-control-label" for="sw-2-1-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-2-1-a" value="1"><label class="custom-control-label" for="sw-2-1-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-2-1-a" value="1"><label class="custom-control-label" for="sw-2-1-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>

                    <tr style="background-color:#f0f0ff">
                      <td style="padding-bottom:0.6rem"><b class="text-secondary">3.</b></td>
                      <td><b class="text-secondary">Billing (Invoicing):</b></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Manage billing information, download and view subscription invoices</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-3-1-a" value="1" checked><label class="custom-control-label" for="sw-3-1-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-3-1-b" value="1" checked><label class="custom-control-label" for="sw-3-1-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-3-1-c" value="1"><label class="custom-control-label" for="sw-3-1-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-3-1-d" value="1"><label class="custom-control-label" for="sw-3-1-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>View Dashboard</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-3-2-a" value="1" checked><label class="custom-control-label" for="sw-3-2-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-3-2-b" value="1" checked><label class="custom-control-label" for="sw-3-2-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-3-2-c" value="1" checked><label class="custom-control-label" for="sw-3-2-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-3-2-d" value="1" checked><label class="custom-control-label" for="sw-3-2-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>

                    <tr style="background-color:#f0f0ff">
                      <td style="padding-bottom:0.6rem"><b class="text-secondary">4.</b></td>
                      <td><b class="text-secondary">Invoice for payment Reports:</b></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Generate Reports</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-4-1-a" value="1" checked><label class="custom-control-label" for="sw-4-1-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-4-1-b" value="1" checked><label class="custom-control-label" for="sw-4-1-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-4-1-c" value="1" checked><label class="custom-control-label" for="sw-4-1-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-4-1-d" value="1"><label class="custom-control-label" for="sw-4-1-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Download Reports</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-4-2-a" value="1" checked><label class="custom-control-label" for="sw-4-2-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-4-2-b" value="1" checked><label class="custom-control-label" for="sw-4-2-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-4-2-c" value="1" checked><label class="custom-control-label" for="sw-4-2-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-4-2-d" value="1" checked><label class="custom-control-label" for="sw-4-2-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>

                    <tr style="background-color:#f0f0ff">
                      <td style="padding-bottom:0.6rem"><b class="text-secondary">5.</b></td>
                      <td><b class="text-secondary">Invoice for payment:</b></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Create invoices</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-1-a" value="1"><label class="custom-control-label" for="sw-5-1-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-1-b" value="1" checked><label class="custom-control-label" for="sw-5-1-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-1-c" value="1" checked><label class="custom-control-label" for="sw-5-1-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-1-d" value="1"><label class="custom-control-label" for="sw-5-1-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Download invoices</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-2-a" value="1"><label class="custom-control-label" for="sw-5-2-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-2-b" value="1" checked><label class="custom-control-label" for="sw-5-2-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-2-c" value="1" checked><label class="custom-control-label" for="sw-5-2-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-2-d" value="1" checked><label class="custom-control-label" for="sw-5-2-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Cancel invoices</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-3-a" value="1"><label class="custom-control-label" for="sw-5-3-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-3-b" value="1" checked><label class="custom-control-label" for="sw-5-3-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-3-c" value="1" checked><label class="custom-control-label" for="sw-5-3-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-3-d" value="1"><label class="custom-control-label" for="sw-5-3-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Delete invoices</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-4-a" value="1"><label class="custom-control-label" for="sw-5-4-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-4-b" value="1" checked><label class="custom-control-label" for="sw-5-4-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-4-c" value="1" checked><label class="custom-control-label" for="sw-5-4-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-4-d" value="1"><label class="custom-control-label" for="sw-5-4-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Mark invoices as paid/unpaid</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-5-a" value="1"><label class="custom-control-label" for="sw-5-5-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-5-b" value="1" checked><label class="custom-control-label" for="sw-5-5-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                          only paid
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-5-5-d" value="1"><label class="custom-control-label" for="sw-5-5-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>

                    <tr style="background-color:#f0f0ff">
                      <td style="padding-bottom:0.6rem"><b class="text-secondary">6.</b></td>
                      <td><b class="text-secondary">Clients / Payers:</b></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>View list of clients</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-1-a" value="1" checked><label class="custom-control-label" for="sw-6-1-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-1-b" value="1" checked><label class="custom-control-label" for="sw-6-1-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-1-c" value="1" checked><label class="custom-control-label" for="sw-6-1-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-1-d" value="1" checked><label class="custom-control-label" for="sw-6-1-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Edit client</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-2-a" value="1"><label class="custom-control-label" for="sw-6-2-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-2-b" value="1" checked><label class="custom-control-label" for="sw-6-2-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-2-c" value="1" checked><label class="custom-control-label" for="sw-6-2-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-2-d" value="1"><label class="custom-control-label" for="sw-6-2-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Archive client</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-3-a" value="1"><label class="custom-control-label" for="sw-6-3-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-3-b" value="1" checked><label class="custom-control-label" for="sw-6-3-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-3-c" value="1" checked><label class="custom-control-label" for="sw-6-3-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-3-d" value="1"><label class="custom-control-label" for="sw-6-3-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Delete client</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-4-a" value="1"><label class="custom-control-label" for="sw-6-4-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-4-b" value="1" checked><label class="custom-control-label" for="sw-6-4-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-4-c" value="1"><label class="custom-control-label" for="sw-6-4-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-6-4-d" value="1"><label class="custom-control-label" for="sw-6-4-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>

                    <tr style="background-color:#f0f0ff">
                      <td style="padding-bottom:0.6rem"><b class="text-secondary">7.</b></td>
                      <td><b class="text-secondary">Business Settings:</b></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Edit Business information</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-1-a" value="1"><label class="custom-control-label" for="sw-7-1-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-1-b" value="1" checked><label class="custom-control-label" for="sw-7-1-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-1-c" value="1"><label class="custom-control-label" for="sw-7-1-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-1-d" value="1"><label class="custom-control-label" for="sw-7-1-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>General Preferences</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-2-a" value="1"><label class="custom-control-label" for="sw-7-2-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-2-b" value="1" checked><label class="custom-control-label" for="sw-7-2-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-2-c" value="1"><label class="custom-control-label" for="sw-7-2-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-2-d" value="1"><label class="custom-control-label" for="sw-7-2-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Statement Preferences</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-3-a" value="1"><label class="custom-control-label" for="sw-7-3-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-3-b" value="1" checked><label class="custom-control-label" for="sw-7-3-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-3-c" value="1"><label class="custom-control-label" for="sw-7-3-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-3-d" value="1"><label class="custom-control-label" for="sw-7-3-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Payment Integration</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-4-a" value="1"><label class="custom-control-label" for="sw-7-4-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-4-b" value="1" checked><label class="custom-control-label" for="sw-7-4-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-4-c" value="1"><label class="custom-control-label" for="sw-7-4-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-4-d" value="1"><label class="custom-control-label" for="sw-7-4-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Taxes, Discounts and Shipping</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-5-a" value="1"><label class="custom-control-label" for="sw-7-5-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-5-b" value="1" checked><label class="custom-control-label" for="sw-7-5-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-5-c" value="1"><label class="custom-control-label" for="sw-7-5-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-5-d" value="1"><label class="custom-control-label" for="sw-7-5-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Manage Saved Items</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-6-a" value="1"><label class="custom-control-label" for="sw-7-6-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-6-b" value="1" checked><label class="custom-control-label" for="sw-7-6-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-6-c" value="1"><label class="custom-control-label" for="sw-7-6-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-6-d" value="1"><label class="custom-control-label" for="sw-7-6-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Business Customization</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-7-a" value="1"><label class="custom-control-label" for="sw-7-7-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-7-b" value="1" checked><label class="custom-control-label" for="sw-7-7-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-7-c" value="1"><label class="custom-control-label" for="sw-7-7-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-7-d" value="1"><label class="custom-control-label" for="sw-7-7-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Manage Files</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-8-a" value="1" checked><label class="custom-control-label" for="sw-7-8-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-8-b" value="1" checked><label class="custom-control-label" for="sw-7-8-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-8-c" value="1"><label class="custom-control-label" for="sw-7-8-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-8-d" value="1"><label class="custom-control-label" for="sw-7-8-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>Manage Trash</td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-9-a" value="1" checked><label class="custom-control-label" for="sw-7-9-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-9-b" value="1" checked><label class="custom-control-label" for="sw-7-9-b" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-9-c" value="1"><label class="custom-control-label" for="sw-7-9-c" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                      <td>
                      	<div class="form-group text-center">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-7-9-d" value="1"><label class="custom-control-label" for="sw-7-9-d" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
                    </tr>


                  </tbody>
                </table>
              </div>

                <div class="card-body login-card-body">
                    <div class="row">
                        <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <?//= Html::submitButton('Save', ['class' => 'btn btn-primary btn-block']) ?>
                  			<button type="reset" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-3" style="margin-right:20px">
                            	<span class="ms-1"><?= Terms::translate('reset', 'button') ?></span><span class="btn-label-right"><i class="mdi mdi-reply"></i></span>
                			</button>
                			<button type="submit" class="btn btn-success rounded-pill waves-effect waves-light me-3" onclick="$('#mode').val('stay');" style="margin-right:20px">
                            	<span class="ms-1"><?= Terms::translate('save', 'button') ?></span><span class="btn-label-right"><i class="mdi mdi-download"></i></span>
                			</button>
                        </div>
                    </div>
            	</div>
<?php \yii\bootstrap4\ActiveForm::end(); ?>

              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->




















