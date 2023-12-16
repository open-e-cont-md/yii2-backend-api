<?php
use yii\helpers\Html;
use openecontmd\backend_api\models\Terms;

$this->title = Terms::translate('manager/index_principal', 'branch');

$names = explode(' ', Yii::$app->user->identity->username);

?>
<div class="card">
    <div class="card-body login-card-body">

        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']) ?>

        <div class="row">

        	<div class="form-group input col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12">
            	<div class="form-group input col-12">
            		<label class="form-label" for="message-translation_alias"><?= Terms::translate('firstname', 'subdivision') ?></label>
                	<?= \yii\helpers\Html::input('text', 'first_name', $names[0] /*$context['user']->first_name*/, ['class' => 'form-control', 'autocomplete' => 'off'])?>
                	<span class="help-block"></span>
            	</div>
            	<div class="form-group input col-12">
            		<label class="form-label" for="message-translation_alias"><?= Terms::translate('lastname', 'subdivision') ?></label>
                	<?= \yii\helpers\Html::input('text', 'last_name', $names[1] /*$context['user']->last_name*/, ['class' => 'form-control', 'autocomplete' => 'off'])?>
                	<span class="help-block"></span>
            	</div>
        	</div>
        </div>

        <?/*= $form->field($model,'username', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) */?>

        <?/*= $form->field($model, 'password', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) */?>

        <div class="row">

            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <?//= Html::submitButton('Save', ['class' => 'btn btn-primary btn-block']) ?>
      			<button type="reset" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-3" style="margin-right:20px">
                	<span class="ms-1"><?= Terms::translate('reset', 'button') ?></span><span class="btn-label-right"><i class="mdi mdi-reply"></i></span>
    			</button>
    			<button type="submit" class="btn btn-success rounded-pill waves-effect waves-light me-3" onclick="$('#mode').val('stay');" style="margin-right:20px">
                	<span class="ms-1"><?= Terms::translate('save', 'button') ?></span><span class="btn-label-right"><i class="mdi mdi-download"></i></span>
    			</button>
				<button type="submit" class="btn btn-info rounded-pill waves-effect waves-light">
					<span class="ms-1"><?= Terms::translate('save_to_list', 'common_form') ?></span><span class="btn-label-right"><i class="mdi mdi-download"></i></span>
				</button>

            </div>

                        <div class="col-xxl-9 col-xl-9 col-lg-6 col-md-6 col-sm-12 col-xs-12">

                <?/*= $form->field($model, 'rememberMe')->checkbox([
                    'template' => '<div class="icheck-primary">{input}{label}</div>',
                    'labelOptions' => [
                        'class' => ''
                    ],
                    'uncheck' => null
                ]) */?>
            </div>

        </div>

        <?php \yii\bootstrap4\ActiveForm::end(); ?>

    </div>
    <!-- /.login-card-body -->
</div>