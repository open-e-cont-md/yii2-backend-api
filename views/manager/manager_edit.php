<?php
//use yii\helpers\Html;
use openecontmd\backend_api\models\Terms;
//use yii\bootstrap\ActiveForm;
//use common\models\AdmintoForm;
use openecontmd\backend_api\models\Helper;
use openecontmd\backend_api\models\Content;

//$params['class'] = 'form-control';
//$params['autocomplete'] = 'off';

Yii::$app->language = 'en';

$pi = Yii::$app->request->pathInfo;
$branch_header = Content::getBranch($this->context->action->controller->module->controller->module->requestedRoute);
//echo "<pre>"; var_dump($pi, $branch_header); echo "</pre>"; exit;

/*
$pa = explode('/', $pi);
$branch = $this->context->action->controller->module->controller->module->requestedRoute;
if ($pa[0] == 'business')
{
    $branch = 'business/*';
    $branch_header = Terms::translate($branch, 'branch').' - '.$pa[1];
}
else
{
    $branch_header = Terms::translate($branch, 'branch');
}
$this->title = $branch_header;
*/

$country_list = Yii::$app->db->createCommand("SELECT ANSI2 AS value, json_get(CountryCaption, '".Yii::$app->language."') AS caption FROM ut_country ORDER BY json_get(CountryCaption, '".Yii::$app->language."')")->queryAll();
$country_list[-1] = array('value'=>null, 'caption'=>'---');
ksort($country_list);

$is_principal = (isset($context['user']) && $context['user']->is_principal == '1');
//echo "<pre>"; var_dump($context, isset($context['user']), $context['user'] == '1', $context['user']->is_principal); echo "</pre>"; exit;
$is_same = isset($context['user']) && ($context['user']->email == \Yii::$app->user->identity->email);
//echo "<pre>"; var_dump($is_principal, $is_same); echo "</pre>"; exit;

//if ( !isset($context['user']->position) || ($context['user']->position == '') ) $context['user']->position = '{"ru":"","ro":"","en":""}';
//echo "<pre>"; var_dump($context); echo "</pre>"; exit;

$this->registerJs('var buyers={};', 1);

?>

<div class="container-fluid">

<?/**?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <div class="d-block d-sm-none">XS</div>
                <div class="d-none d-sm-block d-md-none">SM</div>
                <div class="d-none d-md-block d-lg-none">MD</div>
				<div class="d-none d-lg-block d-xl-none">LG</div>
				<div class="d-none d-xl-block d-xxl-none">XL</div>
				<div class="d-none d-xxl-block">XXL</div>
				</div>
			</div>
		</div>
	</div>
<?/**/?>

    <div class="row">
        <div class="col-12 mt-1">
        	<div class="d-block d-lg-none"><h4 class="header-title mb-2"><?= $branch_header ?></h4></div>
		</div>
	</div>

<form id="edit_form" action="" method="post" enctype="multipart/form-data">
<?// $form = ActiveForm::begin(['id' => 'edit-form', 'options' => ['class' => 'needs-validation', 'novalidate' => true, 'data-parsley-validate' => false]]); ?>
<input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
<input type="hidden" name="client" value="<?//= $context['client']->alias ?>" />
<input type="hidden" name="client_id" value="<?//= $context['client']->client_id ?>" />
<input type="hidden" name="is_principal" value="<?= $is_principal ? 1 : 0 ?>" />
<input type="hidden" name="mode" value="update" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
           			<a href="<?//= Yii::$app->language ?>/manager">
                		<button type="button" class="btn btn-info rounded-pill waves-effect waves-light me-3" style="margin-right:10px">
                    		<span class="btn-label"><i class="mdi mdi-chevron-double-left"></i></span><span class="me-1"><?= Terms::translate('go_to_list', 'button') ?></span>
                    	</button>
    				</a>
                    <?//= Html::resetButton('Сбросить',   ['class' => 'btn btn-white   rounded-pill btn-outline-secondary me-4 mt-1 mb-1', 'name' => 'cancel-button']) ?>
		            <?//= Html::submitButton('Сохранить', ['class' => 'btn btn-primary rounded-pill me-4 mt-1 mb-1', 'name' => 'submit-button']) ?>
					<button type="reset" name="cancel-button" class="btn btn-secondary text-nowrap rounded-pill waves-effect waves-light me-2" style="margin-right:10px">
                    	<?= Terms::translate('reset', 'button') ?>&nbsp;<span class="btn-label-right"><i class="mdi mdi-close"></i></span>
     				</button>
					<button type="submit" class="btn btn-success text-nowrap rounded-pill waves-effect waves-light me-4">
                    	<?= Terms::translate('save', 'button') ?>&nbsp;<span class="btn-label-right"><i class="mdi mdi-download"></i></span>
     				</button>
                </div>
            </div>
		</div>
	</div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-2"><?= Terms::translate('credentials', 'manager') ?></h4>
<?/**?>
            <ul class="nav nav-pills navtab-bg mt-1">
                <li class="nav-item">
                    <a href="#profile-d1" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                        <?= Terms::translate('main_settings', 'subdivision') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#profile-d2" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                        <?= Terms::translate('change_password', 'manager') ?>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane show active ms-1" id="profile-d1">
<?/**/?>
                    <div class="row">

                    	<div class="form-group input col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        	<div class="form-group input col-12">
                        		<label class="form-label" for="message-translation_alias"><?= Terms::translate('firstname', 'subdivision') ?></label>
                            	<?= \yii\helpers\Html::input('text', 'first_name', $context['user']->first_name, ['class' => 'form-control', 'autocomplete' => 'off'])?>
                            	<span class="help-block"></span>
                        	</div>
                        	<div class="form-group input col-12 mt-2">
                        		<label class="form-label" for="message-translation_alias"><?= Terms::translate('lastname', 'subdivision') ?></label>
                            	<?= \yii\helpers\Html::input('text', 'last_name', $context['user']->last_name, ['class' => 'form-control', 'autocomplete' => 'off'])?>
                            	<span class="help-block"></span>
                        	</div>
                    	</div>

                        <div class="form-group input col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                       		<label class="form-label"><?= Terms::translate('position', 'subdivision') ?></label>
                            <div class="input-group">
                                <span class="input-group-text" id="tip-caption[ru]"><img alt="RU" src="/cabinet/web/flags_iso_24/RU.png" style="width:24px;"></span>
                                <input type="text" name="position[ru]" value="<?//= ($context['user'] ? htmlspecialchars(Helper::json_decode_safe($context['user']->position)->ru, ENT_NOQUOTES) : '') ?>" class="form-control" placeholder="<?= Terms::translate('version_ru', 'subdivision') ?>" aria-label="Линк" aria-describedby="tip-caption[ru]">
                                <?//= \yii\helpers\Html::input('text', "caption[ru]", ($b ? json_decode($b['caption'])->ro : ''), ['class' => 'form-control', 'autocomplete' => 'off'])?>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text" id="tip-caption[ro]"><img alt="RO" src="/cabinet/web/flags_iso_24/MD.png" style="width:24px;"></span>
                                <input type="text" name="position[ro]" value="<?//= ($context['user'] ? htmlspecialchars(json_decode($context['user']->position)->ro, ENT_QUOTES) : '') ?>" class="form-control" placeholder="<?= Terms::translate('version_ro', 'subdivision') ?>" aria-label="Линк" aria-describedby="tip-caption[ro]">
                                <?//= \yii\helpers\Html::input('text', "caption[ro]", ($b ? json_decode($b['caption'])->ru : ''), ['class' => 'form-control', 'autocomplete' => 'off'])?>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text" id="tip-caption[en]"><img alt="EN" src="/cabinet/web/flags_iso_24/GB.png" style="width:24px;"></span>
                                <input type="text" name="position[en]" value="<?//= ($context['user'] ? htmlspecialchars(Helper::json_decode_safe($context['user']->position)->en, ENT_NOQUOTES) : '') ?>" class="form-control" placeholder="<?= Terms::translate('version_en', 'subdivision') ?>" aria-label="Линк" aria-describedby="tip-caption[en]">
                                <?//= \yii\helpers\Html::input('text', "caption[en]", ($b ? json_decode($b['caption'])->en : ''), ['class' => 'form-control', 'autocomplete' => 'off'])?>
                            </div>
                        </div>

                    	<div class="form-group input col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    		<label class="form-label"><?= Terms::translate('email', 'subdivision') ?> &nbsp;
                    		<? if ($context['user']->status == 1) { ?>
                    			<span class="text-success"><i style="font-size: 1rem; cursor:help" class="far fa-shield-check"></i> (<?= Terms::translate('confirmed', 'cabinet') ?>)</span>
<? } else { ?>
								<span class="text-danger"><i style="font-size: 1rem; cursor:help" class="far fa-shield-xmark"></i> (<?= Terms::translate('not_confirmed', 'cabinet') ?>)</span>
<? } ?>
                    		</label>
                        	<?//= \yii\helpers\Html::input('text', 'email', $context['client']->alias, ['class' => 'form-control mb-1', 'autocomplete' => 'off', 'readonly' => 'on'])?>
                        	<?//= \yii\helpers\Html::input('text', 'email', $context['user']->email, ['class' => 'form-control'.(!$is_principal ? ' disabled' : ''), 'autocomplete' => 'off', 'readonly' => !$is_principal])?>
                        	<?= \yii\helpers\Html::input('text', 'email', $context['user']->email, ['class' => 'form-control disabled', 'autocomplete' => 'off', 'readonly' => !$is_principal])?>
<?/**?>
        	<span class="input-group-text" style="width:3rem;height:2.4rem;"
    		><i style="font-size: 1.1rem; cursor:help" class="far fa-shield-check text-success"
	    		title="E-Mail подтвержден!"></i></span>
<?/**/?>
                        	<span class="help-block"></span>
                        	<label class="form-label mt-2"><?= Terms::translate('mobile', 'subdivision') ?> &nbsp;
                    			<span class="text-danger"><i style="font-size: 1rem; cursor:help" class="far fa-shield-xmark"></i> (<?= Terms::translate('not_confirmed', 'cabinet') ?>)</span>
                    		</label>
                        	<?= \yii\helpers\Html::input('text', 'mobile', $context['user']->mobile, ['class' => 'form-control', 'autocomplete' => 'off'])?>
<?/**?>
        	<span class="input-group-text" style="width:3rem;height:2.4rem;"
    		><i style="font-size: 1.1rem; cursor:help" class="far fa-shield-minus text-danger"
	    		title="Мобильный телефон не подтвержден!"></i></span>
<?/**/?>
                        	<span class="help-block"></span>
<?/**?>
    		<button id="phone_check" type="button" class="btn btn-primary rounded-pill waves-effect waves-light me-3 mt-3">
              	<span class="ms-1">Check phone<?//= Terms::translate('save', 'button') ?></span><span class="btn-label-right"><i class="mdi mdi-check"></i></span>
    		</button>
<?/**/?>
                    	</div>

<?/**?>
    		<div class="form-group input col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="d-flex align-items-center">
                        <p class="text"><b><?= Terms::translate('subdivision_accessibility', 'manager') ?></b></p>
                </div>
<? if ($is_principal && $is_same) { if (isset($context['business'])) { ?>
                <div class="row">
				<? foreach ($context['business'] as $k => $v) { ?>
            		<div class="form-group mb-1">
            			<input name="business[<?= $v['business_token'] ?>]" value="1" type="hidden">
	            		<input name="business[<?= $v['business_token'] ?>]" value="1" type="checkbox" checked data-plugin="switchery" data-color="#1bb99a" data-secondary-color="#ff5d48" data-size="small" disabled />
                        &nbsp; <?/**?><b><?= $v['alias'] ?></b> - <?/**?><b><?= json_decode($v['caption'])->{Yii::$app->language} ?></b>
                    </div>
				<? } ?>
                </div>
<? } } else { $l = explode(',', $context['user']['business_alias_list']); ?>
                <div class="row">
				<? foreach ($context['business'] as $k => $v) { if (in_array($v['business_token'], $l) ) { ?>
            		<div class="form-group mb-2">
            			<input name="business[<?= $v['business_token'] ?>]" value="0" type="hidden">
	            		<input name="business[<?= $v['business_token'] ?>]" value="1" type="checkbox" checked data-plugin="switchery" data-color="#1bb99a" data-secondary-color="#ff5d48" data-size="small" disabled />
                        &nbsp; <?/**?><b><?= $v['alias'] ?></b> - <?/**?><b><?= json_decode($v['caption'])->{Yii::$app->language} ?></b>
                    </div>
				<? } } ?>
                </div>

<? } ?>
    		</div>
<?/**?>
			</div>

		</div>

		<div class="tab-pane show ms-1" id="profile-d2">
		    <div class="row">
                    <div class="col-lg-6">
                    	<div class="form-group input col-xl-8 col-md-8 col-lg-12 col-sm-12">
                    		<label class="form-label"><?= Terms::translate('current_password', 'manager') ?></label>
                        	<?//= \yii\helpers\Html::input('text', 'email', $context['client']->alias, ['class' => 'form-control mb-1', 'autocomplete' => 'off', 'readonly' => 'on'])?>
                        	<?= \yii\helpers\Html::input('password', 'password', '', ['class' => 'form-control', 'autocomplete' => 'new-password'])?>
                        	<span class="help-block"><?= Terms::translate('current_password_remark', 'manager') ?></span>
                    	</div>
                    </div>
                    <div class="col-lg-6">
                    	<div class="form-group input mt-1 col-xl-8 col-md-8 col-lg-12 col-sm-12">
                    		<label class="form-label" for="message-translation_alias"><?= Terms::translate('new_password', 'manager') ?></label>
                        	<?= \yii\helpers\Html::input('text', 'new_password_1', '', ['class' => 'form-control', 'autocomplete' => 'off'])?>
                        	<span class="help-block"></span>
                    	</div>
                    	<div class="form-group input mt-1 col-xl-8 col-md-8 col-lg-12 col-sm-12">
                    		<label class="form-label" for="message-translation_alias"><?= Terms::translate('new_password', 'manager') ?></label>
                        	<?= \yii\helpers\Html::input('text', 'new_password_2', '', ['class' => 'form-control', 'autocomplete' => 'off'])?>
                        	<span class="help-block"><?= Terms::translate('new_password_remark', 'manager') ?></span>
                    	</div>
                    </div>
			</div>
		</div>
<?/**/?>
		</div>
		</div>

		</div>

<?/* if ($is_principal && $is_same) { if (isset($context['business'])) { ?>
	<div class="row">
        <div class="col-12">
            <div class="card border-1 border-info">
                <div class="card-body d-flex">
                    <div class="flex-shrink-0 avatar-sm">
                        <div class="icon"><i class="fas fa-info-circle text-info img-fluid rounded-circle" style="font-size: 1.8em"></i></div>
                    </div>
                    <div class="flex-grow-1">
                    	<h4 class="header-title mb-0 mt-0"><?= Terms::translate('info', 'alert') ?></h4>
                    	<p class="mb-0 mt-1"><?= Terms::translate('senior_manager_info', 'manager') ?></p>
                    	<p class="mb-0 mt-1"><?= Terms::translate('subdivision_access_remark', 'manager') ?></p>
                    </div>
				</div>
			</div>
		</div>
	</div>
<? } else { ?>
	<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex">
                    <div class="flex-shrink-0 avatar-sm me-2">
                        <div class="icon"><i class="fas fa-briefcase text-danger img-fluid rounded-circle mt-0 mb-0" style="font-size: 1.8em"></i></div>
                    </div>
                    <div class="flex-grow-1">
                    	<h4 class="header-title"><?= Terms::translate('subdivision_accessibility', 'manager') ?></h4>
                    	<p class="mb-0 mt-1">Сейчас у ва нет ни одного подразделения. После создания подразделений вы сможете управлять их доступностью для менеджеров.
                    	<?//= Terms::translate('subdivision_access_remark', 'manager') ?>
                    	<p><a href="<?//= (Yii::$app->language != 'ro') ? Yii::$app->language.'/' : '' ?>business/new">
           					<button type="button" class="btn btn-primary text-nowrap rounded-pill waves-effect waves-light me-4 mt-3">
                	    		Создать подразделение<?//= Terms::translate('save', 'button') ?>&nbsp;<span class="btn-label-right"><i class="mdi mdi-plus"></i></span>
     						</button>
						</a></p>
                    </div>
				</div>
			</div>
		</div>
	</div>
<? } } */?>

<?/**?>
<div class="row row-flex">
<? if ($is_principal && $is_same) {
/*    if (isset($context['business'])) { ?>
    <div class="col-xl-4 col-md-6 row-flex">
        <div class="card">
            <div class="card-body widget-user">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 avatar-lg me-3">
                        <div class="icon"><i class="fas fa-user text-muted img-fluid rounded-circle" style="font-size: 5em"></i></div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-danger mb-1"><b><?= Terms::translate('senior_manager', 'cabinet') ?></b></p>
                        <h3 class="mt-0 mb-0"><?= ($context['user']->first_name != '' || $context['user']->last_name != '') ? $context['user']->first_name .' '. $context['user']->last_name : '&nbsp;<br>' ?></h3>
                        <i class="mt-0 mb-3"><?= (json_decode($context['user']->position)->{Yii::$app->language} != '') ? json_decode($context['user']->position)->{Yii::$app->language} : '&nbsp;<br>' ?></i>
                        <p class="text mb-2 font-14 text-truncate">E-Mail: <b><?= $context['user']->email ?></b></p>
                    </div>
                </div>
                <div class="row">
				<? foreach ($context['business'] as $k => $v) { ?>
            		<div class="form-group mb-1">
            			<input name="business[<?= $v['business_token'] ?>]" value="1" type="hidden">
	            		<input name="business[<?= $v['business_token'] ?>]" value="1" type="checkbox" checked data-plugin="switchery" data-color="#1bb99a" data-secondary-color="#ff5d48" data-size="small" disabled />
                        &nbsp; <b><?= json_decode($v['caption'])->{Yii::$app->language} ?></b>
                    </div>
				<? } ?>
                </div>
            </div>
        </div>
    </div>
<? } ?>

<?/* foreach ($context['others'] as $uk => $uv) { if ( !isset($uv['position']) || ($uv['position'] == '') ) $uv['position'] = '{"ru":"","ro":"","en":""}';
    $l = explode(',', $uv['business_alias_list']); ?>
    <div class="col-xl-4 col-md-6 row-flex">
        <div class="card">
            <div class="card-body widget-user">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 avatar-lg me-3">
                        <div class="icon"><i class="fas fa-user text-muted img-fluid rounded-circle" style="font-size: 5em"></i></div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-1"><b><?= Terms::translate('manager', 'cabinet') ?></b></p>
                        <h3 class="mt-0 mb-0"><?= ($uv['first_name'] != '' || $uv['last_name'] != '') ? $uv['first_name'] .' '. $uv['last_name'] : '&nbsp;<br>' ?></h3>
                        <i class="mt-0 mb-3"><?= (json_decode($uv['position'])->{Yii::$app->language} != '') ? json_decode($uv['position'])->{Yii::$app->language} : '&nbsp;<br>' ?></i>
                        <p class="text mb-2 font-14 text-truncate">E-Mail: <b><?= $uv['email'] ?></b></p>
                    </div>
				</div>
                <div class="row">
				<? foreach ($context['business'] as $k => $v) { ?>
            		<div class="form-group mb-1">
            			<input name="managers[<?= $uv['email'] ?>][<?= $v['business_token'] ?>]" value="0" type="hidden">
            			<input name="managers[<?= $uv['email'] ?>][<?= $v['business_token'] ?>]" value="1" type="checkbox" <?= in_array($v['business_token'], $l) ? 'checked' : '' ?> data-plugin="switchery" data-color="#1bb99a" data-secondary-color="#98a6ad" data-size="small" />
                        <b><?= json_decode($v['caption'])->{Yii::$app->language} ?></b>
                    </div>
				<? } ?>
                </div>
            </div>
        </div>
    </div>
<? } ?>


<? }/* else { $l = explode(',', $context['user']['business_alias_list']); ?>
    <div class="col-xl-4 col-md-6 row-flex">
        <div class="card">
            <div class="card-body widget-user">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 avatar-lg me-3">
                        <div class="icon"><i class="fas fa-user text-muted img-fluid rounded-circle" style="font-size: 5em"></i></div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text"><b><?= Terms::translate('subdivision_accessibility', 'manager') ?></b></p>
                        <h3 class="mt-0 mb-0"><?= ($context['user']->first_name != '' || $context['user']->last_name != '') ? $context['user']->first_name .' '. $context['user']->last_name : '&nbsp;<br>' ?></h3>
                        <i class="mt-0 mb-3"><?= (json_decode($context['user']->position)->{Yii::$app->language} != '') ? json_decode($context['user']->position)->{Yii::$app->language} : '&nbsp;<br>' ?></i>
                        <p class="text mb-2 font-14 text-truncate">E-Mail: <b><?= $context['user']->email ?></b></p>

                    </div>
                </div>
                <div class="row">
				<? foreach ($context['business'] as $k => $v) { if (in_array($v['business_token'], $l) ) { ?>
            		<div class="form-group mb-1">
            			<input name="business[<?= $v['business_token'] ?>]" value="0" type="hidden">
	            		<input name="business[<?= $v['business_token'] ?>]" value="1" type="checkbox" checked data-plugin="switchery" data-color="#1bb99a" data-secondary-color="#ff5d48" data-size="small" disabled />
                        &nbsp; <b><?= json_decode($v['caption'])->{Yii::$app->language} ?></b>
                    </div>
				<? } } ?>
                </div>
            </div>
        </div>
    </div>
<? } ?>

</div>
<?/**/?>
</div>
</div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
           			<a href="<?//= Yii::$app->language ?>/manager">
                		<button type="button" class="btn btn-info rounded-pill waves-effect waves-light me-3" style="margin-right:10px">
                    		<span class="btn-label"><i class="mdi mdi-chevron-double-left"></i></span><span class="me-1"><?= Terms::translate('go_to_list', 'button') ?></span>
                    	</button>
    				</a>
<?/**?>                	&nbsp; <span class="text-danger fs-4">*</span> - <?= Terms::translate('mandatory', 'cabinet') ?><br><br><?/**/?>
                    <?//= Html::resetButton('Сбросить',   ['class' => 'btn btn-white   rounded-pill btn-outline-secondary me-4 mt-1 mb-1', 'name' => 'cancel-button']) ?>
		            <?//= Html::submitButton('Сохранить', ['class' => 'btn btn-primary rounded-pill me-4 mt-1 mb-1', 'name' => 'submit-button']) ?>
					<button type="reset" name="cancel-button" class="btn btn-secondary text-nowrap rounded-pill waves-effect waves-light me-2" style="margin-right:10px">
                    	<?= Terms::translate('reset', 'button') ?>&nbsp;<span class="btn-label-right"><i class="mdi mdi-close"></i></span>
     				</button>
					<button type="submit" class="btn btn-success text-nowrap rounded-pill waves-effect waves-light me-4">
                    	<?= Terms::translate('save', 'button') ?>&nbsp;<span class="btn-label-right"><i class="mdi mdi-download"></i></span>
     				</button>
                </div>
            </div>
		</div>
	</div>

</form>

	</div>


<?/**?>
<br clear="all">
<pre><?php var_dump($context['business']) ?></pre>
<section class="content">
<pre><?php var_dump($context) ?></pre>
</section>
<?/**/?>