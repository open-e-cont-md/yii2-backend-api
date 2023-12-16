<?php
//use yii\helpers\Html;
use openecontmd\backend_api\models\Terms;

$this->title = 'Settings'; //Terms::translate('settings', 'cabinet');


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
<?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'settings-form']) ?>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-wrap">
                  <thead>
                    <tr class="text-nowrap">
                      <th width="5%" style="padding-bottom:0.6rem"><b class="text-secondary">#</b></th>
                      <th width="*" style="padding-bottom:0.6rem"><b class="text-secondary">Description</b></th>
                      <th width="60%" style="padding-bottom:0.6rem"><b class="text-secondary">Value</b></th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr style="background-color:#f0f0ff">
                      <td style="padding-bottom:0.6rem"><b class="text-secondary">1.</b></td>
                      <td><b class="text-secondary">Base settings:</b></td>
                      <td></td>
                    </tr>

                    <tr>
                      <td></td>
                      <td>String</td>
                      <td>
                      	<div class="form-group">
        		            <input type="text" class="form-control" placeholder="Enter value" style="width:50%">
                        </div>
                      </td>
					</tr>

                    <tr>
                      <td></td>
                      <td>Password</td>
                      <td>
                      	<div class="form-group">
        		            <input type="password" class="form-control" placeholder="Enter password" style="width:50%">
                        </div>
                      </td>
					</tr>

                    <tr>
                      <td></td>
                      <td>Text</td>
                      <td>
                      	<div class="form-group">
                      		<textarea class="form-control" rows="3" placeholder="Enter text" style="width:50%"></textarea>
                        </div>
                      </td>
					</tr>
















                    <tr>
                      <td></td>
                      <td>Boolean</td>
                      <td>
                      	<div class="form-group">
                        	<div class="custom-control custom-switch custom-switch-off-muted custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="sw-1-1-a" value="1"><label class="custom-control-label" for="sw-1-1-a" style="cursor:pointer"></label>
                            </div>
                        </div>
                      </td>
					</tr>

                    <tr>
                      <td></td>
                      <td>Selection</td>
                      <td>
                      	<div class="form-group">
                      		<select class="custom-select rounded-0" style="width:50%">
                                <option>Value 1</option>
                                <option>Value 2</option>
                                <option>Value 3</option>
                  			</select>
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




















