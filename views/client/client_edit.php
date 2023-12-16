<?php
//use yii\helpers\Html;
use openecontmd\backend_api\models\Terms;

//Yii::$app->language = 'en';

$this->title = 'Редактирование покупателя';
$params['class'] = 'form-control';
$params['autocomplete'] = 'off';

$country_list = Yii::$app->db->createCommand("SELECT ANSI2 AS value, json_get(CountryCaption, '".Yii::$app->language."') AS caption FROM ut_country ORDER BY json_get(CountryCaption, '".Yii::$app->language."')")->queryAll();
$country_list[-1] = array('value'=>null, 'caption'=>'---');
ksort($country_list);

$language_list = ['ro' => 'Romanian', 'ru' => 'Русский', 'en' => 'English'];

if ( !isset($customer->caption_long) || ($customer->caption_long == '') ) $customer->caption_long = '{"ru":"","ro":"","en":""}';
//var_dump($context['business'][0]['alias']);

?>
<style>
<!--
.select2-container--default .select2-selection--single .select2-selection__rendered {
    padding-top: 8px;
    line-height: 15px;  /*38px;*/
}
.select2-dropdown {
    max-height: 400px; /* Your value here */
    overflow: scroll;
}
.select2-results {
    max-height: 100%;
}
.drow {
    padding: 2px;
}

-->
</style>

<div class="container-fluid g-0"> <!-- container-fluid -->
<?/**?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <div class="d-block d-sm-none">XS</div>
                <div class="d-none d-sm-block d-md-none">SM</div>
                <div class="d-none d-md-block d-lg-none">MD</div>
				<div class="d-none d-lg-block d-xl-none">LG</div>
				<div class="d-none d-xl-block">XL</div>
				</div>
			</div>
		</div>
<?// var_dump($result) ?>
	</div>
<?/**/?>

<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
<input type="hidden" name="prev_email" value="<?= $customer->contact_email ?>" />
<input type="hidden" name="prev_idno" value="<?= $customer->idno ?>" />
<input type="hidden" name="unique_key" value="<?= $customer->unique_key ?>" />
<input type="hidden" name="is_individual" value="<?= $customer->is_individual ?>" />
<input type="hidden" id="mode" name="mode" value="update" />
<?//= \yii\helpers\Html::input('hidden', 'inner_hash', md5(time()), ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => true])?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<a href="/<?= Yii::$app->language ?>/client">
            		<button type="button" class="btn btn-info rounded-pill waves-effect waves-light me-3" style="margin-right:20px">
                		<span class="btn-label"><i class="mdi mdi-chevron-double-left"></i></span><span class="me-1"><?= Terms::translate('go_to_list', 'button') ?></span>
                	</button>
				</a>
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
		</div>
	</div>
</div>



<div class="row">

          <div class="col-12 col-sm-12">
            <div class="card card-primary card-outline card-outline-tabs">

              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-client-tab" role="tablist">
                  <li class="nav-item">
                    <? if ($customer->is_individual != '1') { ?>
                        <a class="nav-link active" id="home-b1" data-toggle="pill" href="#home-b1" role="tab" aria-controls="home-b1" aria-selected="true">
                            <?= Terms::translate('persona_juridica', 'cabinet') ?>
                        </a>
                    <? } else { ?>
                    	<a class="nav-link" id="home-b1" data-toggle="pill" href="" role="tab" aria-controls="home-b1" aria-selected="false" disabled="true">
                            <?= Terms::translate('persona_juridica', 'cabinet') ?>
                        </a>
                    <? } ?>
                  </li>
                  <li class="nav-item">
            <? if ($customer->is_individual == '1') { ?>
                <a class="nav-link active" id="profile-b1" data-toggle="pill" href="#profile-b1" role="tab" aria-controls="profile-b1" aria-selected="true">
                    <?= Terms::translate('persona_fizica', 'cabinet') ?>
                </a>
            <? } else { ?>
                <a class="nav-link" id="profile-b1" data-toggle="pill" href="" role="tab" aria-controls="profile-b1" aria-selected="false" disabled="true">
                    <?= Terms::translate('persona_fizica', 'cabinet') ?>
                </a>
            <? } ?>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-client-tabContent">
                  <div class="tab-pane fade <?= ($customer->is_individual != '1') ? ' show active' : '' ?>" id="home-b1" role="tabpanel" aria-labelledby="home-b1">

<div class="row">

    	<div class="form-group input col-xxl-2 col-xl-2 col-lg-2 col-md-3 col-sm-3 col-xs-12 mb-2">
    		<label class="form-label" for="message-translation_alias">IDNO</label>
<?/**/?>
    		<div class="input-group">
        	<?= \yii\helpers\Html::input('text', 'idno', $customer->idno, ['id' => 'customer_idno', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false,
        	    'onkeyup' => '
    $("#idno_check").prop("disabled", false);
    $("#idno_icon").removeClass("fa-check").removeClass("text-success").addClass("fa-question-circle").addClass("text-danger");
    $("#idno_icon").prop("title", $("#idno_icon").data("notverified"));
    $("#idno_verified").val(0);'])?>
<?/**?>
        	<span class="input-group-text" style="width:3rem;height:2.4rem;"
    		><i id="idno_icon" style="font-size: 1.1rem; cursor:help" class="fa-regular <?= $customer->idno_verified == '1' ? 'fa-check text-success' : 'fa-question-circle text-danger' ?>"
	    		data-verified="IDNO подтвержден!" data-notverified="IDNO не подтвержден!"
	    		title="<?= $customer->idno_verified == '1' ? 'IDNO подтвержден!' : 'IDNO не подтвержден!' ?>"></i></span>
<?/**/?>
    		</div>
<?/**/?>
    		<input id="idno_verified" name="idno_verified" type="hidden" value="<?= $customer->idno_verified ?>">
        	<?//= $customer->idno_verified == '1' ? '<div class="help-block text-success fs-5">IDNO подтвержден!</div>' : '<div class="help-block text-danger fs-5">IDNO не подтвержден!</div>' ?>
    	</div>
<?/**?>
    	<div class="form-group input col-xxl-2 col-xl-2 col-lg-2 col-md-3 col-sm-3 col-xs-12 mb-2" style="padding-top:5px">
    		<button id="idno_check" type="button" class="btn btn-info rounded-pill waves-effect waves-light me-3 mt-3" <?= $customer->idno_verified == '1' ? 'disabled="true"' : '' ?>>
              	<span class="ms-1"><?= Terms::translate('verify', 'cabinet') ?></span><span class="btn-label-right"><i class="mdi mdi-check"></i></span>
    		</button>
    	</div>
<?/**/?>
    	<div class="form-group input col-xxl-2 col-xl-2 col-lg-2 col-md-3 col-sm-3 col-xs-12">
    		<label class="form-label" for="message-translation_alias">TVA</label>
        	<?= \yii\helpers\Html::input('text', 'tva', $customer->tva, ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false])?>
        	<div class="help-block"></div>
    	</div>
	</div>


    <div class="row">
        <div class="form-group input mb-1 col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12">
          	<div class="form-group input mb-2 col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="form-label" for="message-translation_alias"><?= Terms::translate('short_company', 'subdivision') ?> (<b class="text-danger">*</b>)</label>
                <?= \yii\helpers\Html::input('text', 'caption', $customer->caption, ['id' => 'customer_caption', 'class' => 'form-control', 'autocomplete' => 'off', 'style' => 'border-bottom: solid 1px red'])?>
                <div class="help-block"></div>
        	</div>

        	<div class="form-group input mb-2 col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        		<label class="form-label" for="customer_juridical"><?= Terms::translate('juridical_type', 'cabinet') ?></label>
            	<?= \yii\helpers\Html::input('text', 'juridical_type', $customer->juridical_type, ['id' => 'customer_juridical', 'class' => 'form-control', 'autocomplete' => 'off'])?>
            	<div class="help-block"></div>
        	</div>
        </div>
        <div class="form-group input mb-1 col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        	<label class="form-label"><?= Terms::translate('fullest_organization', 'cabinet') ?></label>
            <div class="input-group">
                <span class="input-group-text" id="tip-caption[ru]"><img alt="RU" src="/cabinet/web/flags_iso_24/RU.png" style="width:24px;"></span>
                <input type="text" name="caption_long[ru]" value="<?= htmlspecialchars(json_decode($customer->caption_long)->ru, ENT_QUOTES) ?>" class="form-control caption_long" placeholder="<?= Terms::translate('version_ru', 'subdivision') ?>" aria-label="Линк" aria-describedby="tip-caption[ru]">
                <?//= \yii\helpers\Html::input('text', "caption[ru]", ($b ? json_decode($b['caption'])->ro : ''), ['class' => 'form-control', 'autocomplete' => 'off'])?>
            </div>
            <div class="input-group">
                <span class="input-group-text" id="tip-caption[ro]"><img alt="RO" src="/cabinet/web/flags_iso_24/MD.png" style="width:24px;"></span>
                <input type="text" name="caption_long[ro]" value="<?= htmlspecialchars(json_decode($customer->caption_long)->ro, ENT_QUOTES) ?>" class="form-control caption_long" placeholder="<?= Terms::translate('version_ro', 'subdivision') ?>" aria-label="Линк" aria-describedby="tip-caption[ro]">
                <?//= \yii\helpers\Html::input('text', "caption[ro]", ($b ? json_decode($b['caption'])->ru : ''), ['class' => 'form-control', 'autocomplete' => 'off'])?>
            </div>
            <div class="input-group">
                <span class="input-group-text" id="tip-caption[en]"><img alt="EN" src="/cabinet/web/flags_iso_24/GB.png" style="width:24px;"></span>
                <input type="text" name="caption_long[en]" value="<?= htmlspecialchars(json_decode($customer->caption_long)->en, ENT_QUOTES) ?>" class="form-control caption_long" placeholder="<?= Terms::translate('version_en', 'subdivision') ?>" aria-label="Линк" aria-describedby="tip-caption[en]">
                <?//= \yii\helpers\Html::input('text', "caption[en]", ($b ? json_decode($b['caption'])->en : ''), ['class' => 'form-control', 'autocomplete' => 'off'])?>
            </div>
        </div>
    </div>



	<div class="row">
	    <div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <label class="form-label" for="message-translation_alias"><?= Terms::translate('firstname', 'subdivision') ?> (<b class="text-danger">*</b>)</label>
        <?= \yii\helpers\Html::input('text', 'first_name', $customer->first_name, ['id' => 'customer_first_name', 'class' => 'form-control', 'autocomplete' => 'off', 'style' => 'border-bottom: solid 1px red'])?>
        <div class="help-block"></div>
    	</div>

	    <div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label class="form-label" for="message-translation_alias"><?= Terms::translate('lastname', 'subdivision') ?> (<b class="text-danger">*</b>)</label>
            <?= \yii\helpers\Html::input('text', 'last_name', $customer->last_name, ['id' => 'customer_last_name', 'class' => 'form-control', 'autocomplete' => 'off', 'style' => 'border-bottom: solid 1px red'])?>
            <div class="help-block"></div>
    	</div>

    	<div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label class="form-label"><?= Terms::translate('preferred_lang', 'subdivision') ?></label> <br/>
            <input name="preferred_language" type="hidden" value="ro">
            <div class="form-group">
                <select class="custom-select rounded-0" name="preferred_language" id="preferred_language">
        		<? foreach ($language_list as $k => $v) { ?>
        			<option value="<?= $k ?>"
        			<?= ($k == $customer->preferred_language) ? ' selected' : '' ?>><?= $v ?></option>
        		<? } ?>
                </select>
            </div>
        </div>
</div>

<div class="row">
    	<div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label class="form-label"><?= Terms::translate('country', 'cabinet') ?></label> <br/>
            <input name="country_code" type="hidden" value="MD">
            <select class="custom-select rounded-0" name="country_code" id="country_code" disabled="on">
    		<? foreach ($country_list as $v) { ?>
				<option value="<?= $v['value'] ?>" <?= ($v['value'] == 'MD') ? ' selected' : '' ?>><?= $v['caption'] ?></option>
    		<? } ?>
            </select>
    	</div>
    	<div class="form-group input mb-2 col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
    		<label class="form-label" for="message-translation_alias"><?= Terms::translate('postal_address', 'cabinet') ?></label>
        	<?= \yii\helpers\Html::input('text', 'address', $customer->address, ['id' => 'customer_address', 'class' => 'form-control', 'autocomplete' => 'off'])?>
        	<div class="help-block"></div>
    	</div>
<br>
    	<div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
    		<label class="form-label" for="message-translation_alias"><?= Terms::translate('email', 'subdivision') ?> (<b class="text-danger">*</b>)</label>
        	<?= \yii\helpers\Html::input('text', 'contact_email', $customer->contact_email, ['class' => 'form-control', 'autocomplete' => 'off', 'style' => 'border-bottom: solid 1px red'])?>
        	<div class="help-block"></div>
    	</div>

    	<div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
    		<label class="form-label" for="message-translation_alias"><?= Terms::translate('mobile', 'subdivision') ?></label>
        	<?= \yii\helpers\Html::input('text', 'contact_phone', $customer->contact_phone, ['class' => 'form-control', 'autocomplete' => 'off'])?>
        	<div class="help-block"></div>
    	</div>

<div class="form-group input mt-4 ms-4 mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
 <label class="form-label">(<b class="text-danger">*</b>) - <?= Terms::translate('mandatory', 'cabinet') ?></label>
</div>


</div>


                  </div>
                  <div class="tab-pane fade<?= ($customer->is_individual == '1') ? ' show active' : '' ?>" id="profile-b1" role="tabpanel" aria-labelledby="profile-b1">

<div class="row">

    	<div class="form-group input col-xxl-2 col-xl-2 col-lg-2 col-md-3 col-sm-3 col-xs-12 mb-2">
    		<label class="form-label" for="message-translation_alias">IDNP</label>
    		<div class="input-group">
        	<?= \yii\helpers\Html::input('text', 'i_idno', $customer->idno, ['id' => 'customer_idno', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false,
        	    'onkeyup' => '$("#idno_check").prop("disabled", false); $("#idno_icon").removeClass("fa-check").removeClass("text-success").addClass("fa-question-circle").addClass("text-danger"); $("#idno_icon").prop("title", $("#idno_icon").data("notverified")); $("#idno_verified").val(0);'])?>
    		</div>
        	<?//= $customer->idno_verified == '1' ? '<div class="help-block text-success fs-5">IDNO подтвержден!</div>' : '<div class="help-block text-danger fs-5">IDNO не подтвержден!</div>' ?>
    	</div>

	    <div class="form-group input mb-2 col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
        <label class="form-label" for="message-translation_alias"><?= Terms::translate('firstname', 'subdivision') ?> (<b class="text-danger">*</b>)</label>
        <?= \yii\helpers\Html::input('text', 'i_first_name', $customer->first_name, ['id' => 'customer_first_name', 'class' => 'form-control', 'autocomplete' => 'off', 'style' => 'border-bottom: solid 1px red'])?>
        <div class="help-block"></div>
    	</div>

	    <div class="form-group input mb-2 col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
            <label class="form-label" for="message-translation_alias"><?= Terms::translate('lastname', 'subdivision') ?> (<b class="text-danger">*</b>)</label>
            <?= \yii\helpers\Html::input('text', 'i_last_name', $customer->last_name, ['id' => 'customer_last_name', 'class' => 'form-control', 'autocomplete' => 'off', 'style' => 'border-bottom: solid 1px red'])?>
            <div class="help-block"></div>
    	</div>

    	<div class="form-group input mb-2 col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-label"><?= Terms::translate('preferred_lang', 'subdivision') ?></label> <br/>
            <input name="i_preferred_language" type="hidden" value="ro">
            <div class="form-group">
                <select class="custom-select rounded-0" name="preferred_language" id="preferred_language">
        		<? foreach ($language_list as $k => $v) { ?>
        			<option value="<?= $k ?>"
        			<?= ($k == $customer->preferred_language) ? ' selected' : '' ?>><?= $v ?></option>
        		<? } ?>
                </select>
            </div>
        </div>

    	<div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label class="form-label"><?= Terms::translate('country', 'cabinet') ?></label> <br/>
            <input name="i_country_code" type="hidden" value="MD">
            <select class="custom-select rounded-0" name="i_country_code" id="country_code" disabled="on">
    		<? foreach ($country_list as $v) { ?>
				<option value="<?= $v['value'] ?>" <?= ($v['value'] == 'MD') ? ' selected' : '' ?>><?= $v['caption'] ?></option>
    		<? } ?>
            </select>
    	</div>
    	<div class="form-group input mb-2 col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
    		<label class="form-label" for="message-translation_alias"><?= Terms::translate('postal_address', 'cabinet') ?></label>
        	<?= \yii\helpers\Html::input('text', 'i_address', $customer->address, ['id' => 'customer_address', 'class' => 'form-control', 'autocomplete' => 'off'])?>
        	<div class="help-block"></div>
    	</div>
<br>
    	<div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
    		<label class="form-label" for="message-translation_alias"><?= Terms::translate('email', 'subdivision') ?> (<b class="text-danger">*</b>)</label>
        	<?= \yii\helpers\Html::input('text', 'i_contact_email', $customer->contact_email, ['class' => 'form-control', 'autocomplete' => 'off', 'style' => 'border-bottom: solid 1px red'])?>
        	<div class="help-block"></div>
    	</div>

    	<div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
    		<label class="form-label" for="message-translation_alias"><?= Terms::translate('mobile', 'subdivision') ?></label>
        	<?= \yii\helpers\Html::input('text', 'i_contact_phone', $customer->contact_phone, ['class' => 'form-control', 'autocomplete' => 'off'])?>
        	<div class="help-block"></div>
    	</div>

<div class="form-group input mt-4 ms-4 mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
 <label class="form-label">(<b class="text-danger">*</b>) - <?= Terms::translate('mandatory', 'cabinet') ?></label>
</div>


</div>


                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
</div>


<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<a href="/<?= Yii::$app->language ?>/client">
            		<button type="button" class="btn btn-info rounded-pill waves-effect waves-light me-3" style="margin-right:20px">
                		<span class="btn-label"><i class="mdi mdi-chevron-double-left"></i></span><span class="me-1"><?= Terms::translate('go_to_list', 'button') ?></span>
                	</button>
				</a>
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
		</div>
	</div>
</div>

</form>
</div>
