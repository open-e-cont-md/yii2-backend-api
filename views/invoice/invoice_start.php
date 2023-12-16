<?php
//use yii\helpers\Html;
use openecontmd\backend_api\models\Helper;
use openecontmd\backend_api\models\Invoice;
use openecontmd\backend_api\models\Terms;

$this->title = str_replace(['<br>', '<br/>'], ' ', 'Добавление счёта на оплату'); // Yii::t('apl','sign_in');
$params['class'] = 'form-control';
$params['autocomplete'] = 'off';

/*   PLUGINS   */
//$bundlea = \hail812\adminlte3\assets\AdminLteAsset::register($this);
$bundle = \hail812\adminlte3\assets\PluginAsset::register($this);
$jsbundle = \openecontmd\backend_api\assets\BackendAsset::register($this);
//echo "<pre>"; var_dump($jsbundle); echo "</pre>"; exit;

//$bundle->css[] = 'jsgrid/jsgrid.min.css';
//$bundle->css[] = 'jsgrid/jsgrid-theme.min.css';
$bundle->css[] = 'datatables-bs4/css/dataTables.bootstrap4.min.css';
$bundle->css[] = 'datatables-responsive/css/responsive.bootstrap4.min.css';
$bundle->css[] = 'datatables-buttons/css/buttons.bootstrap4.min.css';

$bundle->js[] = 'datatables/jquery.dataTables.min.js';
$bundle->js[] = 'datatables-bs4/js/dataTables.bootstrap4.min.js';
$bundle->js[] = 'datatables-responsive/js/dataTables.responsive.min.js';
$bundle->js[] = 'datatables-responsive/js/responsive.bootstrap4.min.js';
$bundle->js[] = 'datatables-buttons/js/dataTables.buttons.min.js';
$bundle->js[] = 'datatables-buttons/js/buttons.bootstrap4.min.js';
$bundle->js[] = 'datatables-buttons/js/buttons.html5.min.js';
$bundle->js[] = 'datatables-buttons/js/buttons.print.min.js';
$bundle->js[] = 'datatables-buttons/js/buttons.colVis.min.js';

$jsbundle->js[] = 'js/jquery.cookie.min.js';

$country_list = Yii::$app->db->createCommand("SELECT ANSI2 AS value, json_get(CountryCaption, '".Yii::$app->language."') AS caption FROM ut_country ORDER BY json_get(CountryCaption, '".Yii::$app->language."')")->queryAll();
$country_list[-1] = array('value'=>null, 'caption'=>'---');
ksort($country_list);
$status_list = [
    'draft' => 'Draft',
//    'suspended' => 'Suspended',
    'actual' => 'Actual',
//    'sended' => 'Sended to payer',
//    'part' => 'Partial paid',
//    'full' => 'Full paid',
//    'reverse' => 'Reverse',
//    'rejected' => 'Rejected',
//    'archived' => 'Archived'
];
$currency_list = ['' => 'MDL', 'MDL' => 'MDL', 'EUR' => 'EUR', 'USD' => 'USD'];
$sb = isset($context['selected_business']) ? $context['selected_business'] : null;
$b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;
$customer_list = Invoice::getCustomers($context['client']->alias);

$ipattern_list = Yii::$app->db->createCommand("
SELECT
/*       IF (`client_alias` != '', CONCAT(`client_alias`, '_', `alias`), `alias`) AS value, */
       inner_hash AS value,
       IF (`client_alias` != '', CONCAT(`client_alias`, ': ', json_get( `caption`, '".Yii::$app->language."')), CONCAT(' ', json_get( `caption`, '".Yii::$app->language."'))) AS caption
FROM ut_invoice_pattern
WHERE (`ParentID` = '6f6da5f37f647d16cd203c34ac13a681') AND
      ((client_alias = '{$context['user']['client_alias']}') OR  (client_alias = '')) AND
      (is_active = 1) ORDER BY client_alias, sort_order")->queryAll();
$language_list = ['ro' => 'Romanian', 'ru' => 'Русский', 'en' => 'English'];

$business_prefix = (isset($business_prefix) && $business_prefix != '') ? /*'-'.*/$business_prefix : '';
//var_dump($business_prefix);exit;
$invoice['seq_num'] = isset($invoice['seq_num']) ? $invoice['seq_num'] : $b['prefix'].$business_prefix.'-1';
//$invoice['seq_num'] = $b['prefix'].$invoice['seq_num'];
//echo "<pre>"; var_dump($customer_list); echo "</pre>"; exit;

$gprofile = isset($context['client']->profile_json) ? json_decode($context['client']->profile_json) : (object)null;
$bprofile = isset($b['profile_json']) ? json_decode($b['profile_json']) : json_decode('{"global_registration":"1","idno":"","no_tva":"1","tva":"","tva_rate":"20","tva_calc":"over","global_bank":"1","bank_name":"","bank_address":"","mdl_account":"","bank_code":"","global_juridical":"1","juridical_country_code":"MD","juridical_city":"","juridical_address":"","juridical_postal_index":"","global_contact":"1","country_code":"MD","city":"","address":"","postal_index":" "}');
$gprofile = $bprofile;

if (!isset($bprofile->global_registration)) $bprofile->global_registration = '1';
if ($bprofile->global_registration == '1') {
    $bprofile->idno = $gprofile->idno;
    $bprofile->tva = $gprofile->tva;
    $bprofile->no_tva = $gprofile->no_tva;
    $bprofile->tva_rate = $gprofile->tva_rate;
    $bprofile->tva_calc = $gprofile->tva_calc;
}

if (!isset($bprofile->global_bank)) $bprofile->global_bank = '1';
if ($bprofile->global_bank == '1') {
    $bprofile->bank_name = $gprofile->bank_name;
    $bprofile->bank_address = $gprofile->bank_address;
    $bprofile->mdl_account = $gprofile->mdl_account;
    $bprofile->bank_code = $gprofile->bank_code;
}

if (!isset($bprofile->global_juridical)) $bprofile->global_juridical = '1';
if ($bprofile->global_juridical == '1') {
    $bprofile->juridical_country_code = $gprofile->juridical_country_code;
    $bprofile->juridical_city = $gprofile->juridical_city;
    $bprofile->juridical_address = $gprofile->juridical_address;
    $bprofile->juridical_postal_index = $gprofile->juridical_postal_index;
}

if (!isset($bprofile->global_contact)) $bprofile->global_contact = '1';
if ($bprofile->global_contact == '1') {
    $bprofile->country_code = $gprofile->country_code;
    $bprofile->city = $gprofile->city;
    $bprofile->address = $gprofile->address;
    $bprofile->postal_index = $gprofile->postal_index;
}
//echo "<pre>"; var_dump($b, $bprofile); echo "</pre>"; exit;
/*
$this->registerJs('
$("#due_on").flatpickr({ minDate:"'.date("d.m.Y", time()).'", maxDate:"'.date("d.m.Y", time() + 3600*24*365).'" });
$("#issue_date").flatpickr({ minDate:"'.date("d.m.Y", time()).'", maxDate:"'.date("d.m.Y", time() + 3600*24*365).'" });
', 4);
*/
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
.selectize-input.full {
    border-bottom: solid 1px red;
}
/*
.nav-pills .nav-link {
    background-color:#f7f7f7;
    border: solid 2px #f7f7f7;
    color: #6c757d;
}
.nav-pills .nav-link.active {
    background:none;
    border: solid 2px #71b6f9;
    color: #6c757d;
}
*/
.autocomplete-suggestions {
    background-color: #ffe;
}
-->
</style>

<script type="text/javascript">
var curr = [];
<? foreach ($customer_list as $k => $v) { ?>
curr[<?= $k ?>] = '<?= $v['currency'] ?>';
<? } ?>

function checkTotal() {
	var tot = 0.0,
		tva = 0.0;

	$.each($('[id^=total_price_]').not('.total_price_tmp'), function (index, value) {
		tot = tot + parseFloat('0'+$(value).val().replace(/ /g,''));
		if ( $(value).val() != '' )
			t = parseFloat(tvaCalc($(value).val().replace(/ /g,''), $("#tva_rate").val(), $("#tva_calc").val()));
		else
			t = 0.0;
		$('#total_tva_' + index).val(t);
		tva = tva + t;
	});

	if ($('#is_tva').val() == '0') {
		if ($('#tva_calc').val() == 'over') {
    		$('#amount').val(ReplaceNumberWithCommas(parseFloat(tot+tva).toFixed(2)));
    		$('#tva_amount').val(ReplaceNumberWithCommas(parseFloat(tva).toFixed(2)));
    		$('#wtva_amount').val(ReplaceNumberWithCommas(parseFloat(tot).toFixed(2)));
		} else {
    		$('#amount').val(ReplaceNumberWithCommas(parseFloat(tot).toFixed(2)));
    		$('#tva_amount').val(ReplaceNumberWithCommas(parseFloat(tva).toFixed(2)));
    		$('#wtva_amount').val(ReplaceNumberWithCommas(parseFloat(tot-tva).toFixed(2)));
		}
	} else {
		$('#amount').val(ReplaceNumberWithCommas(parseFloat(tot).toFixed(2)));
		$('#tva_amount').val(ReplaceNumberWithCommas(parseFloat(0).toFixed(2)));
		$('#wtva_amount').val(ReplaceNumberWithCommas(parseFloat(tot).toFixed(2)));
	}

	$.each($('.seq'), function (index, value) {
		$(value).html(String(index+1)+'.');
	});

	$.each($('[class*=hide_]'), function (index, value) {
		if (index > 0) $(value).show(200); else $(value).hide(200);
	});
}

function tvaCalc(summ, rate, calc) {
	if ($('#is_tva').val() == '0') {
		if (calc == 'inner')
			return (summ.replace(' ','') * ( Number(rate) / (100.0 + Number(rate) ) )).toFixed(2);
		else
			return (summ.replace(' ','') * ( Number(rate) / 100.0 )).toFixed(2);
	} else
		return 0.0;
}

function ReplaceNumberWithCommas(yourNumber) {
    var n= yourNumber.toString().split(".");
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    return n.join(".");
}

function format(inputDate) {
	  let date, month, year;

	  date = inputDate.getDate();
	  month = inputDate.getMonth() + 1;
	  year = inputDate.getFullYear();

	    date = date
	        .toString()
	        .padStart(2, '0');

	    month = month
	        .toString()
	        .padStart(2, '0');

	  return `${date}.${month}.${year}`;
}
/*
function changeDueOn() {
	$('#due_on').val(format(
		new Date(
			parseInt($('#issue_date').val().substring(6,10)),
			parseInt($('#issue_date').val().substring(3,5))-1,
			parseInt($('#issue_date').val().substring(0,2)) + parseInt($('#due_days').val())))
		).trigger('changeDate');
	$('#due_on').flatpickr('refresh');
}
*/
var pattern_goal = [], pattern_remark = [], pattern_item = [];
pattern_goal['ru'] = '<?= Helper::json_decode_safe($b['pattern_goal'])->ru ?>';
pattern_goal['ro'] = '<?= Helper::json_decode_safe($b['pattern_goal'])->ro ?>';
pattern_goal['en'] = '<?= Helper::json_decode_safe($b['pattern_goal'])->en ?>';
pattern_remark['ru'] = '<?= Helper::json_decode_safe($b['pattern_remark'])->ru ?>';
pattern_remark['ro'] = '<?= Helper::json_decode_safe($b['pattern_remark'])->ro ?>';
pattern_remark['en'] = '<?= Helper::json_decode_safe($b['pattern_remark'])->en ?>';
pattern_item['ru'] = '<?= Helper::json_decode_safe($b['pattern_item'])->ru ?>';
pattern_item['ro'] = '<?= Helper::json_decode_safe($b['pattern_item'])->ro ?>';
pattern_item['en'] = '<?= Helper::json_decode_safe($b['pattern_item'])->en ?>';

</script>

<?/**?>
<script type="text/javascript" src="/customer/auto/<?= $context['client']->client_id ?>"></script>

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
<?// var_dump($result) ?>
	</div>
<?/**/?>

<div class="container-fluid g-0"> <!-- container-fluid -->
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
<input type="hidden" name="business" value="<?= $b['business_token'] ?>" />
<input type="hidden" name="mode" value="create" />
<input type="hidden" name="inner_hash" value="<?= md5(time()) ?>" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">

					<a href="/<?= Yii::$app->language ?>/outgoing<?//= $context['business'][$sb]['business_token'] ?>">
                		<button type="button" class="btn btn-primary rounded-pill waves-effect waves-light me-2">
                    		<span class="btn-label"><i class="mdi mdi-chevron-double-left"></i></span><?= Terms::translate('go_to_list', 'button') ?>
                    	</button>
					</a> &nbsp; &nbsp;
<?/**/?>
					<button type="reset" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-2">
                    	<?= Terms::translate('reset', 'button') ?>&nbsp;<span class="btn-label-right"><i class="mdi mdi-close"></i></span>
     				</button> &nbsp; &nbsp;
					<button type="submit" class="btn btn-success rounded-pill waves-effect waves-light me-2">
                    	<?= Terms::translate('save', 'button') ?><?//= Yii::t('apl', 'update_popup') ?><span class="btn-label-right"><i class="mdi mdi-download"></i></span>
     				</button>
<?/**/?>
     				<?//= common\models\Helper::GUID(); ?>

                      		<select class="custom-select rounded-0" style="margin-left:100px; width:auto">
                                <option>MOLDOVA / Republic of Moldova open.e-Cont.md Specification</option>
                                <option>PEPPOL / Business Interoperability Specifications (BIS)</option>
                                <option>CIUS-RO e-Factura / Sistemul national privind factura electronica RO e-Factura pentru institutii publice</option>
                                <option>CIUS-AT-GOV / Austrian Governmental Core Invoice Usage Specification</option>
                                <option>CIUS-AT-NAT / Austrian National Core Invoice Usage Specification</option>
                                <option>CIUS-ES-FACE / Punto General de Entrada de Facturas Electrónicas de la AGE / Spanish Government</option>
                                <option>CIUS-IT / Agenzia delle Entrate (AdE) / Italia</option>
                                <option>NLCIUS / Standaardisatieplatform e-factureren /  Netherlands</option>
                  			</select>
                        </div>
<?/*

http://api-docs.open.e-cont.tst/test/peppol

MOLDOVA / Republic of Moldova open.e-Cont.md Specification

CIUS-RO e-Factura / Sistemul national privind factura electronica RO e-Factura pentru institutii publice / Ministerul Finanțelor

CIUS-AT-GOV / Austrian Governmental Core Invoice Usage Specification / Governed by the Austrian Ministry of Finance (BMF)

CIUS-AT-NAT / Austrian National Core Invoice Usage Specification / Governed by the Austrian Ministry of Finance (BMF)

CIUS-ES-FACE / Punto General de Entrada de Facturas Electrónicas de la AGE / Spanish Government

CIUS-IT / Agenzia delle Entrate (AdE) / Italia

NLCIUS / Standaardisatieplatform e-factureren /  Netherlands

PEPPOL / Business Interoperability Specifications (BIS)

*/?>










                </div>
            </div>
		</div>
	</div>

<div class="card">
	<div class="card-body">
		<div class="row">

    <div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
    	<label class="form-label" for="message-translation_alias"><?= Terms::translate('invoice_number', 'invoice') ?> (<b class="text-danger">*</b>)</label>
    	<div class="input-group">
    <?/**?>	<span class="input-group-text" style="width:3rem"><i style="font-size: 1.2rem;" class="fa-regular fa-key-skeleton"></i></span><?/**/?>
    	<?= \yii\helpers\Html::input('text', 'outer_number', $invoice['seq_num'], ['class' => 'form-control has-feedback-left', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.4rem;border-bottom: solid 1px red'])?>
<?/**?>
    	<span class="input-group-text" style="width:3rem;height:2.4rem;" onclick="" title="Номер обновлен/проверен!"
    	    data-plugin="tippy" data-tippy-interactive="true" data-tippy-trigger="click"
    		><i style="font-size: 1.1rem; cursor:pointer;" class="fa-regular fa-refresh text-primary"></i></span>
<?/**/?>
    	</div>
    </div>

	<div class="form-group input mb-2 col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
        <label class="form-label"><?= Terms::translate('status', 'cabinet') ?> (<b class="text-danger">*</b>)</label> <br/>
        <select class="custom-select rounded-0" id="status_select" name="status" disabled onchange="$(this).css('background-color', 'white')">
        	<? foreach ($statuses as $k => $v) { ?>
    		<option value="<?= $v->alias //$k ?>" <?= ($k == 'draft') ? ' selected' : '' ?>><?= json_decode($v->caption)->{Yii::$app->language} ?></option>
    		<? } ?>
        </select>
    </div>
    <div class="form-group input mb-2 col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
        <label class="form-label" for="message-translation_alias"><?= Terms::translate('created', 'invoice') ?></label>
    	<input type="text" class="form-control"
    	name="moment" value="<?= date("H:i d.m.Y", time()) ?>">
    </div>
    <div class="form-group input mb-2 col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('invoice_date', 'invoice') ?> (<b class="text-danger">*</b>)</label>
    	<input type="text" class="form-control basic-datepicker flatpickr-input"
    	id="issue_date" name="issue_date" value="<?= date("d.m.Y", time()) ?>" style="border-bottom: solid 1px red"
    	placeholder="DD.MM.YYYY" data-date-format="d.m.Y" autocomplete="off" onchange="changeDueOn();">
    </div>
	<div class="form-group input mb-2 col-xxl-1 col-xl-1 col-lg-1 col-md-4 col-sm-6 col-xs-6">
        <label class="form-label"><?= Terms::translate('actual', 'cabinet') ?></label> <br/>
        <select class="custom-select rounded-0" name="due_days" class="due_days" id="due_days" onchange="changeDueOn();">
		<? for ( $i = 1; $i <= 15; $i++ ) { ?>
    		<option value="<?= $i ?>"<?= ($i == $context['business'][$context['selected_business']]['warn_days']) ? 'selected' : '' ?>><?= $i ?>д.</option>
		<? } ?>
        </select>
    </div>
    <div class="form-group input mb-2 col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('valid_untill', 'invoice') ?> (<b class="text-danger">*</b>)</label>
    	<input type="text" class="form-control basic-datepicker flatpickr-input" style="border-bottom: solid 1px red"
    	id="due_on" name="due_on" value="<?= date("d.m.Y", time() + intval($context['business'][$context['selected_business']]['warn_days']) * 24 * 60 * 60) ?>"
    	placeholder="DD.MM.YYYY" data-date-format="d.m.Y" autocomplete="off">
    </div>

<?/**?>
    <div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('payment_date', 'invoice') ?></label>
    	<input type="text" class="form-control basic-datepicker flatpickr-input"
    	name="paid_date" value="" readonly="true" disabled="true"
    	placeholder="DD.MM.YYYY" data-date-format="d.m.Y" autocomplete="off">
    </div>
<?/**?>
    <div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('delivery_date', 'invoice') ?></label>
    	<input type="text" class="form-control basic-datepicker flatpickr-input"
    	name="delivery_date" value="<?= date("d.m.Y", time()) ?>"
    	placeholder="DD.MM.YYYY" data-date-format="d.m.Y" autocomplete="off">
    </div>
<?/**/?>

<?/**?>
	<div class="form-group input mb-2 col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('buyer', 'cabinet') ?> (<b class="text-danger">*</b>)</label>
        <select class="form-select" id="buyer_id" name="buyer_id" style="border-bottom: solid 1px red"
        	onchange="$('.curr').html('MDL'); /*$('#curr_list').val(curr[$(this).val()]).trigger('change'); $('.curr').html(curr[$(this).val()]);*">
        	<option value="0" data-display="<?= Terms::translate('buyer', 'cabinet') ?>">- - -</option>
        	<? foreach ($customer_list as $k => $v) { ?>
    		<option value="<?= $k ?>"><?= $v['caption'] ?></option>
    		<? } ?>
        </select>
    </div>
<?/**/?>

    <div class="col-md-6">
        <label class="form-label"><?= Terms::translate('buyer', 'cabinet') ?> (<b class="text-danger">*</b>)</label>
        <input type="text" name="buyers" id="buyers-ajax" value="" class="form-control" style="height:2.4rem;border-bottom: solid 1px red" />
<?/**/?>        <input type="hidden" name="buyer_id" id="selection-ajax" value="" /><?/**/?>
<?/**?>        <input type="text" name="buyers" id="buyers-ajax-x" value="" class="form-control" /><?/**/?>
		<div class="help-block mb-2"><?= Terms::translate('first_simbols', 'cabinet') ?></div>
    </div>

    <div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
    	<label class="form-label" for="buyer_caption"><?= Terms::translate('change_caption', 'cabinet') ?></label>
    	<?= \yii\helpers\Html::input('text', 'buyer_caption', '', ['id' => 'invoice_buyer_caption', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
    	<div class="help-block"></div>
    </div>
    <div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
    	<label class="form-label" for="buyer_name"><?= Terms::translate('contact', 'cabinet') ?></label>
    	<?= \yii\helpers\Html::input('text', 'buyer_name', '', ['id' => 'invoice_buyer_name', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
    	<div class="help-block"></div>
    </div>

    <div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
    	<label class="form-label" for="idno"><?= Terms::translate('idno_code', 'cabinet') ?></label>
    	<?= \yii\helpers\Html::input('text', 'buyer_idno', '', ['id' => 'invoice_idno', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
    	<div class="help-block"></div>
    </div>
    <div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
    	<label class="form-label" for="tva"><?= Terms::translate('tva_code', 'cabinet') ?></label>
    	<?= \yii\helpers\Html::input('text', 'buyer_tva', '', ['id' => 'invoice_tva', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
    	<div class="help-block"></div>
    </div>

    <div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
    	<label class="form-label" for="email"><?= Terms::translate('email', 'cabinet') ?> (<b class="text-danger">*</b>)</label>
    	<?= \yii\helpers\Html::input('text', 'buyer_email', '', ['id' => 'invoice_email', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem;border-bottom: solid 1px red'])?>
    	<div class="help-block"></div>
    </div>
    <div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
    	<label class="form-label" for="phone"><?= Terms::translate('phone', 'cabinet') ?></label>
    	<?= \yii\helpers\Html::input('text', 'buyer_phone', '', ['id' => 'invoice_phone', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
    	<div class="help-block"></div>
    </div>

<?/**?>
<div class="form-group input mb-2 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
	<label class="form-label" for="topic"><?= Terms::translate('main_remark', 'cabinet') ?></label>
	<?= \yii\helpers\Html::textarea('topic', '', ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
	<div class="help-block"></div>
</div>
<?/**/?>

<div class="form-group mt-2 mb-2 col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>
<div class="form-group input col-xxl-2 col-xl-2 col-md-2 col-lg-12 col-sm-12">
    <label class="form-label"><?= Terms::translate('invoice_lang', 'cabinet') ?></label>
    <select class="custom-select rounded-0" id="pattern_language" name="invoice[pattern_language]">
    	<? foreach ($language_list as $k => $v) { ?>
		<option value="<?= $k ?>" <?= (isset($bprofile->preferred_language) && $k == $bprofile->preferred_language) ? 'selected' : '' ?>><?= $v ?></option>
		<? } ?>
    </select>
</div>
<div class="form-group mt-3 col-lg-2 col-md-2 col-sm-12 col-xs-12">
	<button class="btn btn-primary btn-sm ms-1 waves-effect waves-light" type="button" style="height:38px; margin-top:4px;"
	onclick="
		$('#invoice_topic').val(pattern_goal[$('#pattern_language').val()]).css('background-color', '#def');
		$('#invoice_remark').val(pattern_remark[$('#pattern_language').val()]).css('background-color', '#def');
		$('#invoice_item').val(pattern_item[$('#pattern_language').val()]).css('background-color', '#def');">
	<?= Terms::translate('template', 'cabinet') ?></button>
</div>
<div class="form-group input col-xxl-4 col-xl-4 col-md-3 col-lg-12 col-sm-12">
    <label class="form-label"><?= Terms::translate('invoice_template', 'subdivision') ?></label> <br/>
    <select class="custom-select rounded-0" name="invoice[invoice_pattern]"<?/**?> readonly="1" disabled="1"<?/**/?>>
    	<? foreach ($ipattern_list as $v) { ?>
		<option value="<?= $v['value'] ?>" <?= (isset($bprofile->invoice_pattern) && $v['value'] == $bprofile->invoice_pattern) ? ' selected' : '' ?>><?= $v['caption'] ?></option>
		<? } ?>
    </select>
</div>
<div class="form-group input col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
    <label class="form-label"><?= Terms::translate('currency', 'invoice') ?></label>
    <input type="hidden" name="currency" value="MDL">
    <select class="custom-select rounded-0" name="currency" id="curr_list" onchange="$('.curr').html('MDL'); /*$('.curr').html($(this).val());*/" disabled="true">
    <? foreach ($currency_list as $k => $v) { ?>
		<option value="<?= $k ?>" <?= $k == 'MDL' ? ' selected' : '' ?>><?= $v ?></option>
	<? } ?>
    </select>
</div>

<div class="form-group mt-2 col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 0px #cccccc"></div>
<div class="form-group mt-2 col-md-2 col-sm-12 col-xs-12">
<input id="is_tva" name="invoice[is_tva]" value="<?//= $bprofile->no_tva ?><?= $bprofile->no_tva == '0' ? '0' : '1' ?>" type="hidden">
	<input name="invoice[no_tva]" value="1" type="hidden">
	<label class="form-label"><?= Terms::translate('payer_tva', 'invoice') ?>:</label> &nbsp;
	<input name="invoice[no_tva]" id="yes_tva" value="0" <?= $bprofile->no_tva == '0' ? 'checked' : '' ?>
		type="checkbox" data-plugin="switchery" data-color="#1bb99a" data-secondary-color="#ff5d48" data-size="small"
		onchange="if($(this).is(':checked')) { $('.show_tva').show(300); $('#is_tva').val(0); } else { $('.show_tva').hide(300); $('#is_tva').val(1); } checkTotal();">
</div>
<div class="form-group col-md-4 col-sm-12 col-xs-12 show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
	<ul class="nav nav-pills navtab-bg mt-1">
		<label class="form-label mt-1"><?= Terms::translate('tva_rate', 'cabinet') ?>: &nbsp;</label>
		<li class="nav-item"><a href="#tva-1" data-bs-toggle="tab" class="nav-link <?= $bprofile->tva_rate == '20' ? 'active' : '' ?>" onclick="$('#tva_rate').val(20); checkTotal();">20%</a></li>
		<li class="nav-item"><a href="#tva-2" data-bs-toggle="tab" class="nav-link <?= $bprofile->tva_rate == '12' ? 'active' : '' ?>" onclick="$('#tva_rate').val(12); checkTotal();">12%</a></li>
		<li class="nav-item"><a href="#tva-3" data-bs-toggle="tab" class="nav-link <?= $bprofile->tva_rate == '8' ? 'active' : '' ?>" onclick="$('#tva_rate').val(8); checkTotal();">8%</a></li>
	</ul>
</div>
<div class="form-group col-md-4 col-sm-12 col-xs-12 show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
	<ul class="nav nav-pills navtab-bg mt-1">
		<label class="form-label mt-1"><?= Terms::translate('tva_calc', 'cabinet') ?>: &nbsp;</label>
		<li class="nav-item"><a href="#tva-4" data-bs-toggle="tab" class="nav-link <?= $bprofile->tva_calc == 'inner' ? 'active' : '' ?>" onclick="$('#tva_calc').val('inner'); checkTotal();"><?= Terms::translate('tva_inner', 'cabinet') ?></a></li>
		<li class="nav-item"><a href="#tva-5" data-bs-toggle="tab" class="nav-link <?= $bprofile->tva_calc == 'over' ? 'active' : '' ?>" onclick="$('#tva_calc').val('over'); checkTotal();"><?= Terms::translate('tva_over', 'cabinet') ?></a></li>
	</ul>
</div>
<div class="form-group col-md-1 col-sm-12 col-xs-12 show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
	<input id="tva_rate" name="invoice[tva_rate]" value="<?= $bprofile->tva_rate ?>" type="hidden">
	<input id="tva_calc" name="invoice[tva_calc]" value="<?= $bprofile->tva_calc ?>" type="hidden">
</div>



<div class="form-group mt-2 mb-2 col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>

<div class="row col-12 ms-1">
<div class="form-group input drow col-md-1 col-sm-2 col-xs-4" style="width:3%;font-weight:bold;">
    <label class="form-label">#</label>
</div>
<div class="form-group input drow col-md-5 col-sm-8 col-xs-4">
    <label class="form-label"><?= Terms::translate('description', 'invoice') ?></label>
</div>
<div class="form-group input drow col-md-2 col-sm-4 col-xs-4">
    <label class="form-label"><?= Terms::translate('unit_price', 'invoice') ?></label>
</div>
<div class="form-group input drow col-md-1 col-sm-2 col-xs-4">
    <label class="form-label"><?= Terms::translate('quantity', 'cabinet') ?></label>
</div>
<div class="form-group input drow col-md-1 col-sm-2 col-xs-4">
    <label class="form-label">&nbsp;</label>
</div>
<div class="form-group input drow col-md-2 col-sm-4 col-xs-4">
    <label class="form-label"><?= Terms::translate('total_amount', 'cabinet') ?></label>
</div>
<div class="form-group col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>
<br clear="all">

<div id="row_space">
<? $i = 0; $total = 0.0; ?>

<div class="row" id="row_<?= $i ?>">
<div class="form-group input drow seq col-md-1 col-sm-2 col-xs-4" style="width:3%;padding-top:10px;font-weight:bold;">1</div>
<div class="form-group input drow col-md-5 col-sm-8 col-xs-4">
    <?//= \yii\helpers\Html::input('text', 'name_'.$i, $v->{"@Name"}, ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false])?>
    <?= \yii\helpers\Html::textarea('name_'.$i, '', ['id' => 'invoice_item', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.4rem'])?>
</div>

<div class="form-group input drow col-md-2 col-sm-4 col-xs-4">
	<div class="input-group">
    <?= \yii\helpers\Html::input('text', 'unit_price_'.$i, '', ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false,
        'id' => 'unit_price_'.$i, 'pattern' => '\d+(\.\d{,2})?',
        'onchange' => '$("#total_price_'.$i.'").val(ReplaceNumberWithCommas(parseFloat($("#unit_price_'.$i.'").val().replace(" ", "") * parseInt("0"+$("#qnt_'.$i.'").val().replace(" ", "")) ).toFixed(2))); checkTotal();' ]) ?>
	<span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;">MDL</span>
	</div>
</div>

<div class="form-group input drow col-md-1 col-sm-2 col-xs-4">
    <?= \yii\helpers\Html::input('text', 'qnt_'.$i, 1, ['id' => 'qnt_'.$i, 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false,
        'type' => 'number', 'pattern' => '[0-9]', 'min' => '1', 'max' => '1000000', 'style' => 'padding-right:2px',
        'onchange' => '$("#total_price_'.$i.'").val(ReplaceNumberWithCommas(parseFloat($("#unit_price_'.$i.'").val().replace(" ", "") * parseInt("0"+$("#qnt_'.$i.'").val().replace(" ", "")) ).toFixed(2))); checkTotal();' ]) ?>
</div>
<div class="form-group input drow col-md-1 col-sm-2 col-xs-4">
    <?= \yii\helpers\Html::input('text', 'unit_'.$i, 'pcs', ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false])?>
</div>

<div class="form-group input drow col-md-2 col-sm-4 col-xs-4">
	<div class="input-group">
	<?= \yii\helpers\Html::input('hidden', 'total_tva_'.$i, '', ['id' => 'total_tva_'.$i])?>
    <?= \yii\helpers\Html::input('text', 'total_price_'.$i, '', ['class' => 'form-control text-end', 'autocomplete' => 'off', 'readonly' => true, 'id' => 'total_price_'.$i])?>
    <span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;">MDL</span>
    </div>
</div>

<div class="form-group input drow hide_<?= $i ?> col-md-1 col-sm-2 col-xs-4" style="width:3%;padding:4px 0 0 4px;font-weight:bold;display:<?= $i > 0 ? 'block' : 'none' ?>">
    <button class="btn btn-default" style="width: 2.8em; margin-top:-2px" title="Удалить строку" onclick="$('#row_<?= $i ?>').remove(); checkTotal(); return false;">
		<i class="fa fa-times-circle" style="color:red;font-size:1.2em;color:#d9534f"></i>
	</button>
</div>

<div class="form-group col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>

</div>

</div>

<script type="text/javascript">
var ii = <?= $i ?>;
</script>

<div class="form-group input drow col-md-1 col-sm-2 col-xs-4" style="width:3%;font-weight:bold;">
    <label class="control-label">&nbsp;</label>
</div>
<div class="form-group input drow col-xxl-5 col-xl-5 col-lg-7 col-md-7 col-sm-7 col-xs-8">
	<button type="button" class="btn btn-outline-primary rounded-pill waves-effect waves-light me-2 mt-2 mb-1" style="width:auto"
    	onclick="ii++; if (ii > 19) { alert('<?= Terms::translate('lines_limit', 'cabinet') ?>'); } else { $('#row_space').append($.parseHTML($('#row_pattern').clone().html().replace(/@@/g, ii).replace(/##/g, ii+1).replace(/ total_price_tmp/g, '').replace(/seq_tmp/g, 'seq'))); } checkTotal(); return false;">
		<span class="btn-label"><i class="mdi mdi-plus"></i></span><?= Terms::translate('add_line', 'cabinet') ?>
	</button>
</div>
    <div class="form-group input drow col-xxl-4 col-xl-2 col-lg-2 col-md-3 col-sm-4 col-xs-4 text-end">
        <div class="input-group show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
        	<input class="form-control text-end" readonly value="<?= Terms::translate('total_to_pay_w_tva', 'invoice') ?>:" style="margin-top:2px;font-weight:bold;border:none">
        </div>
        <div class="input-group show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
        	<input class="form-control text-end" readonly value="<?= Terms::translate('total_tva', 'invoice') ?>:" style="margin-top:2px;font-weight:bold;border:none">
        </div>
        <div class="input-group">
        	<input class="form-control text-end" readonly value="<?= Terms::translate('total_to_pay', 'invoice') ?>:" style="margin-top:2px;font-weight:bold;border:none">
        </div>
    </div>
	<div class="form-group input drow col-xxl-2 col-xl-2 col-lg-2 col-md-2 col-sm-4 col-xs-4">
		<div class="input-group show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
    		<?= \yii\helpers\Html::input('text', 'wtva_amount', number_format(0, 2, '.', ' '), ['class' => 'form-control text-end', 'id' => 'wtva_amount', 'autocomplete' => 'off', 'readonly' => true, 'style' => 'font-weight:bold'])?>
    		<span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;">MDL<?//= $invoice[0]->currency ?></span>
    	</div>
		<div class="input-group show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
    		<?= \yii\helpers\Html::input('text', 'tva_amount', number_format(0, 2, '.', ' '), ['class' => 'form-control text-end', 'id' => 'tva_amount', 'autocomplete' => 'off', 'readonly' => true, 'style' => 'font-weight:bold'])?>
    		<span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;">MDL<?//= $invoice[0]->currency ?></span>
    	</div>
		<div class="input-group">
    		<?= \yii\helpers\Html::input('text', 'amount', number_format(0, 2, '.', ' '), ['class' => 'form-control text-end', 'id' => 'amount', 'autocomplete' => 'off', 'readonly' => true, 'style' => 'font-weight:bold'])?>
    		<span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;">MDL<?//= $invoice[0]->currency ?></span>
    	</div>
	</div>
</div>

<div class="form-group mt-2 mb-2 col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>

    <div class="form-group input mt-1 mb-2 col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="form-label" for="currency"><?= Terms::translate('manager_remark', 'cabinet') ?></label>
		<?= \yii\helpers\Html::textarea('remark', '', ['id' => 'invoice_remark', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:4.5rem'])?>
		<div class="help-block"></div>
	</div>
    <div class="form-group input mt-1 mb-2 col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="form-label" for="currency"><?= Terms::translate('manager_topic', 'cabinet') ?></label>
		<?= \yii\helpers\Html::textarea('topic', '', ['id' => 'invoice_topic', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:4.5rem'])?>
		<div class="help-block"></div>
	</div>

<?/**?>
    <div class="form-group input mb-2 col-xxl-10 col-xl-10 col-lg-6 col-md-6 col-sm-12 col-xs-12">
    	<label class="form-label" for="remark"><?= Terms::translate('manager_remark', 'cabinet') ?></label>
    	<?= \yii\helpers\Html::textarea('remark', '', ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:4.5rem'])?>
    	<div class="help-block"></div>
    </div>
	<div class="form-group input mb-2 col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('currency', 'invoice') ?></label>
        <input type="hidden" name="currency" value="MDL">
        <select class="selectize-select" name="currency" id="curr_list" onchange="$('.curr').html('MDL'); /*$('.curr').html($(this).val());*" disabled="true">
        <? foreach ($currency_list as $k => $v) { ?>
    		<option value="<?= $k ?>" <?= $k == 'MDL' ? ' selected' : '' ?>><?= $v ?></option>
    	<? } ?>
        </select>
    </div>
<?/**/?>
		</div>
	</div>
</div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
					<a href="/<?= Yii::$app->language ?>/invoices/outgoing/<?= $context['business'][$sb]['business_token'] ?>">
                		<button type="button" class="btn btn-primary rounded-pill waves-effect waves-light me-2">
                    		<span class="btn-label"><i class="mdi mdi-chevron-double-left"></i></span><?= Terms::translate('go_to_list', 'button') ?>
                    	</button>
					</a>
<?/**/?>
					<button type="reset" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-2">
                    	<?= Terms::translate('reset', 'button') ?>&nbsp;<span class="btn-label-right"><i class="mdi mdi-close"></i></span>
     				</button>
					<button type="submit" class="btn btn-success rounded-pill waves-effect waves-light me-2">
                    	<?= Terms::translate('save', 'button') ?><?//= Yii::t('apl', 'update_popup') ?><span class="btn-label-right"><i class="mdi mdi-download"></i></span>
     				</button>
<?/**/?>
     				<?//= common\models\Helper::GUID(); ?>
                </div>
            </div>
		</div>
	</div>

</form>
</div>



<div id="row_pattern" style="display:none">
<div class="row" id="row_@@">
<div class="form-group input drow seq_tmp col-md-1 col-sm-2 col-xs-4" style="width:3%;padding-top:4px;font-weight:bold;">##</div>
<div class="form-group input drow col-md-5 col-sm-8 col-xs-4">
    <textarea class="form-control" name="name_@@" autocomplete="off" style="height:2.4rem"></textarea>
</div>
<div class="form-group input drow col-md-2 col-sm-4 col-xs-4">
	<div class="input-group">
    <input type="text" class="form-control" name="unit_price_@@" id="unit_price_@@" value="" autocomplete="off"
    	onchange="$('#total_price_@@').val(ReplaceNumberWithCommas(parseFloat($('#unit_price_@@').val().replace(' ', '') * $('#qnt_@@').val().replace(' ', '')).toFixed(2))); checkTotal();">
    <span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;">MDL</span>
    </div>
</div>
<div class="form-group input drow col-md-1 col-sm-2 col-xs-4">
    <input type="number" class="form-control" name="qnt_@@" id="qnt_@@" value="1" autocomplete="off" min="1" max="1000000" style="padding-right:2px"
    	onchange="$('#total_price_@@').val(ReplaceNumberWithCommas(parseFloat($('#unit_price_@@').val().replace(' ', '') * $('#qnt_@@').val().replace(' ', '')).toFixed(2))); checkTotal();">
</div>
<div class="form-group input drow col-md-1 col-sm-2 col-xs-4">
    <input type="text" class="form-control" name="unit_@@" value="pcs" autocomplete="off">
</div>
<div class="form-group input drow col-md-2 col-sm-4 col-xs-4">
	<div class="input-group">
    <input type="hidden" class="form-control total_tva_tmp" name="total_tva_@@" id="total_tva_@@" value="" autocomplete="off" readonly="on">
    <input type="text" class="form-control total_price_tmp text-end" name="total_price_@@" id="total_price_@@" value="" autocomplete="off" readonly="on">
    <span class="input-group-text curr" style="width:3rem;height:2.3rem;padding: 2px 0 0 0.5rem;font-weight:bold;">MDL</span>
    </div>
</div>

<?/**/?>
<div class="form-group input drow hide_@@ col-md-1 col-sm-2 col-xs-4" style="width:3%;font-weight:bold">
	<button type="button" class="btn btn-outline-danger rounded-pill waves-effect waves-light ms-2" style="width:2.4rem"
		title="Удалить строку" onclick="$('#row_@@').remove(); checkTotal(); return false;"
		><i class="fa fa-times" style="font-size:1rem;margin-left:-1px;"></i></button>
</div>
<?/**?>
<div class="form-group input drow hide_@@ col-md-1 col-sm-2 col-xs-4" style="width:3%;padding:4px 0 0 4px;font-weight:bold;">
    <button class="btn btn-default" style="width: 2.8em; margin-top:-2px" title="Удалить строку" onclick="$('#row_@@').remove(); checkTotal(); return false;">
		<i class="fa fa-times-circle" style="color:red;font-size:1.2em;color:#d9534f"></i>&nbsp;
	</button>
</div>
<?/**/?>
<div class="form-group col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>
</div>
</div>

<?/**?>
<br clear="all">
<section class="content">
<pre><?php var_dump($context) ?></pre>
</section>
<?/**/?>