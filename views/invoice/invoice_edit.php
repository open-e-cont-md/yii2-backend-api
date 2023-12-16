<?php
use yii\helpers\Html;
use openecontmd\backend_api\models\Terms;
use openecontmd\backend_api\models\Invoice;
use openecontmd\backend_api\widgets\AlertToastr;

$this->title = str_replace(['<br>', '<br/>'], ' ', 'Редактирование счёта на оплату'); // Yii::t('apl','sign_in');
$params['class'] = 'form-control';
$params['autocomplete'] = 'off';

/*   PLUGINS   */
//$bundlea = \hail812\adminlte3\assets\AdminLteAsset::register($this);
$bundle = \hail812\adminlte3\assets\PluginAsset::register($this);
$jsbundle = \openecontmd\backend_api\assets\BackendAsset::register($this);
$tsbundle = \hail812\adminlte3\assets\PluginAsset::register($this)->add(['sweetalert2', 'toastr']);

//echo "<pre>"; var_dump($jsbundle); echo "</pre>"; exit;

//$bundle->css[] = 'jsgrid/jsgrid.min.css';
//$bundle->css[] = 'jsgrid/jsgrid-theme.min.css';

$bundle->css[] = 'datatables-bs4/css/dataTables.bootstrap4.min.css';
$bundle->css[] = 'datatables-responsive/css/responsive.bootstrap4.min.css';
$bundle->css[] = 'datatables-buttons/css/buttons.bootstrap4.min.css';
$bundle->css[] = 'daterangepicker/daterangepicker.css';

$bundle->css[] = 'select2/css/select2.min.css';
$bundle->css[] = 'select2-bootstrap4-theme/select2-bootstrap4.min.css';

$bundle->css[] = 'toastr/toastr.min.css';

//$bundle->js[] = 'jquery/jquery.min.js';
//$bundle->js[] = 'bootstrap/js/bootstrap.bundle.min.js';
$bundle->js[] = 'datatables/jquery.dataTables.min.js';
$bundle->js[] = 'datatables-bs4/js/dataTables.bootstrap4.min.js';
$bundle->js[] = 'datatables-responsive/js/dataTables.responsive.min.js';
$bundle->js[] = 'datatables-responsive/js/responsive.bootstrap4.min.js';
$bundle->js[] = 'datatables-buttons/js/dataTables.buttons.min.js';
$bundle->js[] = 'datatables-buttons/js/buttons.bootstrap4.min.js';
$bundle->js[] = 'datatables-buttons/js/buttons.html5.min.js';
$bundle->js[] = 'datatables-buttons/js/buttons.print.min.js';
$bundle->js[] = 'datatables-buttons/js/buttons.colVis.min.js';

$bundle->js[] = 'select2/js/select2.full.min.js';

$bundle->js[] = 'moment/moment.min.js';
$bundle->js[] = 'daterangepicker/daterangepicker.js';

$jsbundle->js[] = 'js/jquery.cookie.min.js';
$tsbundle->js[] = 'toastr/toastr.min.js';


$country_list = Yii::$app->db->createCommand("SELECT ANSI2 AS value, json_get(CountryCaption, '".Yii::$app->language."') AS caption FROM ut_country ORDER BY json_get(CountryCaption, '".Yii::$app->language."')")->queryAll();
$country_list[-1] = array('value'=>null, 'caption'=>'---');
ksort($country_list);
/*
 $status_list = [
 'draft' => 'Draft',
 'suspended' => 'Suspended',
 'actual' => 'Actual',
 'sended' => 'Sended to payer',
 'part' => 'Partial paid',
 'full' => 'Full paid',
 'reverse' => 'Reverse',
 'rejected' => 'Rejected',
 'archived' => 'Archived'
 ];
 */
$currency_list = ['' => 'MDL', 'MDL' => 'MDL', 'EUR' => 'EUR', 'USD' => 'USD'];
$sb = isset($context['selected_business']) ? $context['selected_business'] : null;
$b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;
$customer_list = Invoice::getCustomers($context['client']->alias);
//echo "<pre>"; var_dump($customer_list); echo "</pre>"; exit;
if (isset($customer[$invoice[0]->buyer_id])) {
    $bid = isset($invoice[0]->buyer_id) ? $invoice[0]->buyer_id : 0;
    $bidc = isset($invoice[0]->buyer_id) ? $customer[$invoice[0]->buyer_id]['caption'] : '';
} else {
    $bid = 0;
    $bidc = '';
}
//echo "<pre>"; var_dump($invoice[0]->buyer_id); exit;

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

$gprofile = isset($context['client']->profile_json) ? json_decode($context['client']->profile_json) : (object) null;
$bprofile = isset($b['profile_json']) ? json_decode($b['profile_json']) : json_decode('{"global_registration":"1","idno":"","no_tva":"1","tva":"","tva_rate":"20","tva_calc":"over","global_bank":"1","bank_name":"","bank_address":"","mdl_account":"","bank_code":"","global_juridical":"1","juridical_country_code":"MD","juridical_city":"","juridical_address":"","juridical_postal_index":"","global_contact":"1","country_code":"MD","city":"","address":"","postal_index":" "}');
$gprofile = $bprofile;

if ($bprofile->global_registration == '1') {
    $bprofile->idno = $gprofile->idno;
    $bprofile->tva = $gprofile->tva;
    $bprofile->no_tva = $gprofile->no_tva;
    $bprofile->tva_rate = $gprofile->tva_rate;
    $bprofile->tva_calc = $gprofile->tva_calc;
}
if ($bprofile->global_bank == '1') {
    $bprofile->bank_name = $gprofile->bank_name;
    $bprofile->bank_address = $gprofile->bank_address;
    $bprofile->mdl_account = $gprofile->mdl_account;
    $bprofile->bank_code = $gprofile->bank_code;
}
if ($bprofile->global_juridical == '1') {
    $bprofile->juridical_country_code = $gprofile->juridical_country_code;
    $bprofile->juridical_city = $gprofile->juridical_city;
    $bprofile->juridical_address = $gprofile->juridical_address;
    $bprofile->juridical_postal_index = $gprofile->juridical_postal_index;
}
if ($bprofile->global_contact == '1') {
    $bprofile->country_code = $gprofile->country_code;
    $bprofile->city = $gprofile->city;
    $bprofile->address = $gprofile->address;
    $bprofile->postal_index = $gprofile->postal_index;
}
//echo "<pre>"; var_dump($bprofile); echo "</pre>"; //exit;

//echo "<pre>"; var_dump($bprofile); echo "</pre>"; //exit;
$bprofile->no_tva = in_array($invoice[0]->tva_calc, ['over', 'inner']) ? '0' : '1';
if ( ($invoice[0]->tva_calc != '') && ($invoice[0]->tva_calc != 'none') ) $bprofile->tva_calc = $invoice[0]->tva_calc;
if ( ($invoice[0]->tva_rate != '') && ($invoice[0]->tva_rate != '0') ) $bprofile->tva_rate = $invoice[0]->tva_rate;
if ( ($invoice[0]->invoice_pattern != '') )  $bprofile->invoice_pattern  = $invoice[0]->invoice_pattern;
if ( ($invoice[0]->pattern_language != '') ) $bprofile->preferred_language = $invoice[0]->pattern_language;

$pi = Yii::$app->request->pathInfo;
$flag_copy = substr($pi, 0, 13) == 'outgoing/copy/';
if ($flag_copy) $invoice[0]->status = 'draft';

//echo "<pre>"; var_dump($invoice[0]); echo "</pre>"; exit;
//echo "<pre>"; var_dump($context['business'][$context['selected_business']]['warn_days']); echo "</pre>"; //exit;
//echo "<pre>"; var_dump($context['business']); echo "</pre>"; exit;

/*
$this->registerJs('
$("#due_on").flatpickr({ minDate:"'.date("d.m.Y", time()).'", maxDate:"'.date("d.m.Y", time() + 3600*24*365).'" });
$("#issue_date").flatpickr({ minDate:"'.date("d.m.Y", time()).'", maxDate:"'.date("d.m.Y", time() + 3600*24*365).'" });
', 4);
*/

/*
$this->registerJs('
$(function () {
//Date picker
    $("#reservationdate").daterangepicker({format: "L"});
});
', 4);
*/

/*
$this->registerJsFile( 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
    ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile( "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" );
*/


$this->registerJs('

$(document).ready(function(){

    $("#buyers-ajax").keyup(function(){
        var search = $(this).val();

        if(search != ""){

            $.ajax({
                url: "/customer/ajax/'.$context['client']->client_id.'",
                type: "post",
                data: {search:search},
                dataType: "json",
                success:function(response){

                    var len = response.length;
                    $("#searchResult").empty();
                    for( var i = 0; i<len; i++) {
//                        var id = response[i]["id"];
//                        var name = response[i]["name"];
                        $("#searchResult").append("<li value=\'"+response[i]["id"]+"\'\
    data-caption=\'"+response[i]["caption`"]+"\'\
    data-email=\'"+response[i]["email"]+"\'\
    data-phone=\'"+response[i]["phone"]+"\'\
    data-idno=\'"+response[i]["idno"]+"\'\
    data-tva=\'"+response[i]["tva"]+"\'\
    data-flname=\'"+response[i]["flname"]+"\' >"+response[i]["name"]+"</li>");
                    }
                    $("#searchResult").css("border", "solid 1px #ccc");
                    // binding click event to li
                    $("#searchResult li").bind("click",function(){
                        setText(this);
                        $("#searchResult").css("border", "solid 1px #fff");
                    });

                }
            });
        }

    });

});

// Set Text to search box and get details
function setText(element){
    $("#buyers-ajax").val($(element).text()).css("background-color", "#fff");
    $("#selection-ajax").val($(element).val());
    $("#invoice_email").val($(element).data("email"));
    $("#invoice_phone").val($(element).data("phone"));
    $("#invoice_idno").val($(element).data("idno"));
    $("#invoice_tva").val($(element).data("tva"));
    $("#invoice_buyer_caption").val($(element).data("caption"));
    $("#invoice_buyer_name").val($(element).data("flname"));
    $("#searchResult").empty();
}

', 4);

$this->registerJs('
$("#issue_date").daterangepicker({
    "singleDatePicker": true,
    "autoApply": true,
    "linkedCalendars": false,
    "showCustomRangeLabel": false,
    "minDate": "' .((strtotime($invoice[0]->moment) > 0) ? date("d.m.Y", strtotime($invoice[0]->moment)) : ""). '",
    "startDate": "' .((strtotime($invoice[0]->issue_date) > 0) ? date("d.m.Y", strtotime($invoice[0]->issue_date)) : ""). '",
//    "endDate": "01.01.2099",
    locale: { format: "DD.MM.YYYY" }
    });
    $("#issue_date").on("apply.daterangepicker", function(ev, picker) { changeDueOn(); });
', 4);

$this->registerJs('
$("#due_on").daterangepicker({
    "singleDatePicker": true,
    "autoApply": true,
    "linkedCalendars": false,
    "showCustomRangeLabel": false,
    "minDate": "' .((strtotime($invoice[0]->moment) > 0) ? date("d.m.Y", strtotime($invoice[0]->moment)) : ""). '",
    "startDate": "' .((strtotime($invoice[0]->due_on) > 0) ? date("d.m.Y", strtotime($invoice[0]->due_on)) : ""). '",
//    "endDate": "01.01.2099",
    locale: { format: "DD.MM.YYYY" }
    });
', 4);

/*
$this->registerJs('

$(".js-example-data-ajax").select2({
  ajax: {
    url: "https://api.github.com/search/repositories",
    dataType: "json",
    delay: 250,
    data: function (params) { alert(params);
      return {
        q: params.term, // search term
        page: params.page
      };
    },
    processResults: function (data, params) {
      // parse the results into the format expected by Select2
      // since we are using custom formatting functions we do not need to
      // alter the remote JSON data, except to indicate that infinite
      // scrolling can be used
      params.page = params.page || 1;

      return {
        results: data.items,
        pagination: {
          more: (params.page * 30) < data.total_count
        }
      };
    },
    cache: true
  },
  placeholder: "Search for a repository",
  minimumInputLength: 1,
  templateResult: formatRepo,
  templateSelection: formatRepoSelection
});

function formatRepo (repo) {
  if (repo.loading) {
    return repo.text;
  }

  var $container = $(
    \'<div class="select2-result-repository clearfix">\' +
      \'<div class="select2-result-repository__avatar"><img src="\' + repo.owner.avatar_url + \'" /></div>\' +
      \'<div class="select2-result-repository__meta">\' +
        \'<div class="select2-result-repository__title"></div>\' +
        \'<div class="select2-result-repository__description"></div>\' +
        \'<div class="select2-result-repository__statistics">\' +
          \'<div class="select2-result-repository__forks"><i class="fa fa-flash"></i> </div>\' +
          \'<div class="select2-result-repository__stargazers"><i class="fa fa-star"></i> </div>\' +
          \'<div class="select2-result-repository__watchers"><i class="fa fa-eye"></i> </div>\' +
        \'</div>\' +
      \'</div>\' +
    \'</div>\'
  );

  $container.find(".select2-result-repository__title").text(repo.full_name);
  $container.find(".select2-result-repository__description").text(repo.description);
  $container.find(".select2-result-repository__forks").append(repo.forks_count + " Forks");
  $container.find(".select2-result-repository__stargazers").append(repo.stargazers_count + " Stars");
  $container.find(".select2-result-repository__watchers").append(repo.watchers_count + " Watchers");

  return $container;
}

function formatRepoSelection (repo) {
  return repo.full_name || repo.text;
}


//Initialize Select2 Elements
$(".select2").select2();

$(".customer-ajax").select2({
  ajax: {
    url: "/customer/ajax/'.$context['client']->client_id.'",
    dataType: "json",
    processResults: function (data) {
      // Transforms the top-level key of the response object from "items" to "results"
      return {
        results: data.caption
      };
    }
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  }
});
', 4);

$this->registerJs('
$("#selection-ajax").on("change", function() {
	var id = $("#selection-ajax").val();
	$("body").css("cursor", "wait");
    $.ajax({
        type: "GET",
        url: "/customer/buyer/" + id.replace(/[^0-9]/g,""),
        success: function ( data ) {
            setTimeout(function(){
            	if (!data.data) {
            		alert("Отсутствует связь с сервером!");
            	}
            	else {
            		$("#buyers-ajax").css("background-color", "#efe");
            		$("#invoice_idno").val(data.data.idno).css("background-color", "#efe");
            		$("#invoice_tva").val(data.data.tva).css("background-color", "#efe");
            		$("#invoice_buyer_name").val(data.data.first_name + " " + data.data.last_name).css("background-color", "#efe");
            		$("#invoice_email").val(data.data.contact_email).css("background-color", "#efe");
            		$("#invoice_buyer_caption").val(data.data.caption).css("background-color", "#efe");
            		$("#invoice_phone").val(data.data.contact_phone).css("background-color", "#efe");
            		$("#status_select").prop("disabled", false).css("background-color", "#efe");
            	}
            	$("body").css("cursor", "default");
            }, 1000);
        }
    });
});
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
label {
    display: inline-block;
}

.autocomplete-suggestions {
    background-color: #ffe;
}



.clear{
    clear:both;
    margin-top: 20px;
}

.autocomplete {
    width: 250px;
    position: relative;
}
.autocomplete #searchResult{
    list-style: none;
    padding: 0px;
    width: 96%;
    position: absolute;
    margin: 0;
    background: white;
    z-index:2000;
/*    border: solid 1px #444;*/
}

.autocomplete #searchResult li{
    background: #F2F3F4;
    padding: 4px;
    margin-bottom: 1px;
}

.autocomplete #searchResult li:nth-child(even){
    background: #E5E7E9;
    color: black;
}

.autocomplete #searchResult li:hover{
    cursor: pointer;
    background: #CACFD2;
}

.autocomplete input[type=text]{
    padding: 5px;
    width: 100%;
/*    letter-spacing: 1px;*/
}

-->
</style>

<script type="text/javascript">
var curr = [];
<? foreach ($customer_list as $k => $v) { ?>
curr[<?= $k ?>] = '<?= $v['currency'] ?>';
<? } ?>

function flipButtons(butt, val) {
	$("." + butt).removeClass("btn-outline-primary").addClass("btn-outline-default");
	$("#" + butt + "_" + val).addClass("btn-outline-primary").removeClass("btn-outline-default");
}

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

function changeDueOn() {

	$('#due_on').val(format(
		new Date(
			parseInt($('#issue_date').val().substring(6,10)),
			parseInt($('#issue_date').val().substring(3,5))-1,
			parseInt($('#issue_date').val().substring(0,2)) + parseInt($('#due_days').val())))
		).trigger('changeDate');

	//$('#due_on').daterangepicker('refresh');
	//$('#due_on').data('daterangepicker');
	$("#due_on").daterangepicker({
	    "singleDatePicker": true,
	    "autoApply": true,
	    "linkedCalendars": false,
	    "showCustomRangeLabel": false,
	    "startDate": format(
	    		new Date(
	    				parseInt($("#issue_date").val().substring(6,10)),
	    				parseInt($("#issue_date").val().substring(3,5))-1,
	    				parseInt($("#issue_date").val().substring(0,2)) + parseInt($("#due_days").val()))),
	    "endDate": "01.01.2099",
	    locale: { format: "DD.MM.YYYY" }
	    });
}

</script>
<?/**?><script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script><?/**/?>
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
<input type="hidden" id="mode" name="mode" value="<?= $flag_copy ? 'create' : 'update' ?>" />
<input type="hidden" name="inner_hash" value="<?= $flag_copy ? md5(time().$b['business_token']) : $invoice[0]->inner_hash ?>" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
					<a href="/<?= Yii::$app->language ?>/outgoing<?//= $context['business'][$sb]['business_token'] ?>">
                		<button type="button" class="btn btn-primary rounded-pill waves-effect waves-light me-2">
                    		<span class="btn-label"><i class="mdi mdi-chevron-double-left"></i></span><?= Terms::translate('go_to_list', 'button') ?>
                    	</button>
					</a> &nbsp;
<?/**/?>
					<button type="reset" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-2">
                    	<?= Terms::translate('reset', 'button') ?>&nbsp;<span class="btn-label-right"><i class="mdi mdi-close"></i></span>
     				</button> &nbsp;
					<button type="submit" class="btn btn-success rounded-pill waves-effect waves-light me-2">
                    	<?= Terms::translate('save', 'button') ?><span class="btn-label-right"><i class="mdi mdi-download"></i></span>
     				</button> &nbsp;
    				<button type="submit" class="btn btn-primary rounded-pill waves-effect waves-light" onclick="$('#mode').val('stay<?= $flag_copy ? '_create' : '' ?>');">
    					<span class="ms-1"><?= Terms::translate('save_to_list', 'common_form') ?></span><span class="btn-label-right"><i class="mdi mdi-download"></i></span>
    				</button>
<?/**/?>


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
    	<?= \yii\helpers\Html::input('text', 'outer_number', ($flag_copy ? $invoice[0]->seq_num : $invoice[0]->outer_number), ['class' => 'form-control has-feedback-left', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.4rem;border-bottom: solid 1px red'])?>
<?/**?>
    	<span class="input-group-text" style="width:3rem;height:2.4rem;" onclick="" title="Номер обновлен/проверен!"
    	    data-plugin="tippy" data-tippy-interactive="true" data-tippy-trigger="click"
    		><i style="font-size: 1.1rem; cursor:pointer;" class="fa-regular fa-refresh text-primary"></i></span>
<?/**/?>
    	</div>
    </div>


<?/**?>
                <!-- Date -->
                <div class="form-group">
                  <label>Date:</label>
                    <div class="input-group date" data-target-input="nearest">
                        <input id="reservationdate" type="text" class="form-control daterangepicker-input" data-target="#reservationdate" value="10.12.2023">
                        <div class="input-group-append" data-target="#reservationdate" data-toggle="daterangepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                    </div>
                </div>
<?/**/?>

	<div class="form-group input mb-2 col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
        <label class="form-label"><?= Terms::translate('status', 'cabinet') ?> (<b class="text-danger">*</b>)</label> <br/>
        <select class="custom-select" id="status_select" name="status"<?= $bid == 0 ? ' disabled' : ''?>>
        	<? foreach ($statuses as $k => $v) { ?>
    		<option value="<?= $v->alias //$k ?>" <?= ($v->alias == $invoice[0]->status) ? ' selected' : '' ?>><?= json_decode($v->caption)->{Yii::$app->language} ?></option>
    		<? } ?>
        </select>
    </div>

    <div class="form-group input mb-2 col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
        <label class="form-label" for="message-translation_alias"><?= Terms::translate('created', 'invoice') ?></label>
        <?= \yii\helpers\Html::input('text', 'moment', date("H:i d.m.Y", strtotime($invoice[0]->moment)), ['class' => 'form-control', 'readonly' => true])?>
        <div class="help-block"></div>
    </div>

    <div class="form-group input mb-2 col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('invoice_date', 'invoice') ?> (<b class="text-danger">*</b>)</label>
    	<input type="text" class="form-control daterangepicker-input" style="border-bottom: solid 1px red"
    	id="issue_date" name="issue_date" value="<?= (strtotime($invoice[0]->issue_date) > 0) ? date("d.m.Y", strtotime($invoice[0]->issue_date)) : '' ?>"
    	data-target="#issue_date" placeholder="DD.MM.YYYY">
    </div>

	<div class="form-group input mb-2 col-xxl-1 col-xl-1 col-lg-1 col-md-4 col-sm-6 col-xs-6">
        <label class="form-label"><?= Terms::translate('actual', 'cabinet') ?></label> <br/>
        <select class="custom-select rounded-0" name="due_days" class="due_days" id="due_days" onchange="changeDueOn();">
		<? for ( $i = 1; $i <= 15; $i++ ) { ?>
    		<option value="<?= $i ?>"<?= ($i == $context['business'][$context['selected_business']]['warn_days']) ? 'selected' : '' ?>><?= $i ?>&nbsp;<?= Terms::translate('day', 'invoice') ?></option>
		<? } ?>
        </select>
<?/**?>
        <select class="custom-select rounded-0" style="width:50%">
            <option>Value 1</option>
            <option>Value 2</option>
            <option>Value 3</option>
		</select>
<?/**/?>

    </div>
    <div class="form-group input mb-2 col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('valid_untill', 'invoice') ?> (<b class="text-danger">*</b>)</label><?/**?> text-truncate <?/**/?>
    	<input type="text" class="form-control daterangepicker-input" style="border-bottom: solid 1px red"
    	id="due_on" name="due_on" value="<?= (strtotime($invoice[0]->due_on) > 0) ? date("d.m.Y", strtotime($invoice[0]->due_on)) : '' ?>"
    	data-target="#due_on" placeholder="DD.MM.YYYY">
    </div>

<?/**?>
    <div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('payment_date', 'invoice') ?></label>
    	<input type="text" class="form-control basic-datepicker flatpickr-input"
    	name="paid_date" value="<?= (strtotime($invoice[0]->paid_date) > 0) ? date("d.m.Y", strtotime($invoice[0]->paid_date)) : '' ?>" readonly="true" disabled="true"
    	placeholder="DD.MM.YYYY" data-date-format="d.m.Y" autocomplete="off">
    </div>

    <div class="form-group input mb-2 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('delivery_date', 'invoice') ?></label>
    	<input type="text" class="form-control basic-datepicker flatpickr-input"
    	name="delivery_date" value="<?= (strtotime($invoice[0]->delivery_date) > 0) ? date("d.m.Y", strtotime($invoice[0]->delivery_date)) : '' ?>"
    	placeholder="DD.MM.YYYY" data-date-format="d.m.Y" autocomplete="off">
    </div>
<?/**?>
	<div class="form-group input mb-2 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('buyer', 'cabinet') ?> (<b class="text-danger">*</b>)</label> <br/>
        <select class="form-select" id="buyer_id" name="buyer_id">
        	<option value="0" data-display="<?= Terms::translate('buyer', 'cabinet') ?>">- - -</option>
        	<? foreach ($customer_list as $k => $v) { ?>
    		<option value="<?= $k ?>" <?= ($k == $invoice[0]->buyer_id) ? ' selected' : '' ?>><?= $v['caption'] ?></option>
    		<? } ?>
        </select>
    </div>
<?/**/?>
    <div class="form-group input mb-2 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 autocomplete">
        <label class="form-label"><?= Terms::translate('buyer', 'cabinet') ?> (<b class="text-danger">*</b>)</label>




        <input type="text" name="buyers" id="buyers-ajax" value="<?= $bidc ?>" class="form-control" style="height:2.4rem;border-bottom: solid 1px red;<?= $bid == 0 ? ' background-color: #fee' : '' ?>" autocomplete="off" />
        <ul id="searchResult" size="5"></ul>
<?/**/?>        <input type="hidden" name="buyer_id" id="selection-ajax" value="<?= $bid ?>" /><?/**/?>
<?/**?>        <input type="text" name="buyers" id="buyers-ajax-x" value="" class="form-control" /><?/**/?>
		<div class="help-block mb-2"><?= Terms::translate('first_simbols', 'cabinet') ?></div>
        <div class="clear"></div>
    </div>


    <div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
    	<label class="form-label" for="buyer_caption"><?= Terms::translate('change_caption', 'cabinet') ?></label>
    	<?= \yii\helpers\Html::input('text', 'buyer_caption', $invoice[0]->buyer_caption, ['id' => 'invoice_buyer_caption', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
    	<div class="help-block"></div>
    </div>
    <div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
    	<label class="form-label" for="buyer_name"><?= Terms::translate('contact', 'cabinet') ?></label>
    	<?= \yii\helpers\Html::input('text', 'buyer_name', $invoice[0]->buyer_name, ['id' => 'invoice_buyer_name', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
    	<div class="help-block"></div>
    </div>


<div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
	<label class="form-label" for="idno"><?= Terms::translate('idno_code', 'cabinet') ?></label>
	<?= \yii\helpers\Html::input('text', 'buyer_idno', $invoice[0]->buyer_idno, ['id' => 'invoice_idno', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
	<div class="help-block"></div>
</div>
<div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
	<label class="form-label" for="tva"><?= Terms::translate('tva_code', 'cabinet') ?></label>
	<?= \yii\helpers\Html::input('text', 'buyer_tva', $invoice[0]->buyer_tva, ['id' => 'invoice_tva', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
	<div class="help-block"></div>
</div>


<div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
	<label class="form-label" for="email"><?= Terms::translate('email', 'cabinet') ?> (<b class="text-danger">*</b>)</label>
	<?= \yii\helpers\Html::input('text', 'buyer_email', $invoice[0]->buyer_email, ['id' => 'invoice_email', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem;border-bottom: solid 1px red'])?>
	<div class="help-block"></div>
</div>
<div class="form-group input mb-2 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
	<label class="form-label" for="phone"><?= Terms::translate('phone', 'cabinet') ?></label>
	<?= \yii\helpers\Html::input('text', 'buyer_phone', $invoice[0]->buyer_phone, ['id' => 'invoice_phone', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
	<div class="help-block"></div>
</div>

<?/**?>
<div class="form-group input mb-2 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
	<label class="form-label" for="currency"><?= Terms::translate('main_remark', 'cabinet') ?></label>
	<?= \yii\helpers\Html::textarea('topic', $invoice[0]->topic, ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem'])?>
	<div class="help-block"></div>
</div>
<?/**/?>

<div class="form-group mt-1 col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>

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

        <div class="btn-group">
            <button id="b_tva_20" type="button" class="btn btn-outline-<?= $bprofile->tva_rate == '20' ? 'primary' : 'default' ?> b_tva" onclick="$('#tva_rate').val(20); checkTotal(); flipButtons('b_tva', '20');">20%</button>
            <button id="b_tva_12" type="button" class="btn btn-outline-<?= $bprofile->tva_rate == '12' ? 'primary' : 'default' ?> b_tva" onclick="$('#tva_rate').val(12); checkTotal(); flipButtons('b_tva', '12');">12%</button>
            <button id="b_tva_08" type="button" class="btn btn-outline-<?= $bprofile->tva_rate == '8'  ? 'primary' : 'default' ?> b_tva" onclick="$('#tva_rate').val(8);  checkTotal(); flipButtons('b_tva', '08');">8%</button>
        </div>
<?/**?>
		<li class="nav-item"><a href="#tva-1" data-bs-toggle="tab" class="nav-link <?= $bprofile->tva_rate == '20' ? 'active' : '' ?>" onclick="$('#tva_rate').val(20); checkTotal();">20%</a></li>
		<li class="nav-item"><a href="#tva-2" data-bs-toggle="tab" class="nav-link <?= $bprofile->tva_rate == '12' ? 'active' : '' ?>" onclick="$('#tva_rate').val(12); checkTotal();">12%</a></li>
		<li class="nav-item"><a href="#tva-3" data-bs-toggle="tab" class="nav-link <?= $bprofile->tva_rate == '8' ? 'active' : '' ?>" onclick="$('#tva_rate').val(8); checkTotal();">8%</a></li>
<?/**/?>
	</ul>
</div>
<div class="form-group col-md-4 col-sm-12 col-xs-12 show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
	<ul class="nav nav-pills navtab-bg mt-1">
		<label class="form-label mt-1"><?= Terms::translate('tva_calc', 'cabinet') ?>: &nbsp;</label>

        <div class="btn-group">
            <button id="b_calc_inner" type="button" class="btn btn-outline-<?= $bprofile->tva_calc == 'inner' ? 'primary' : 'default' ?> b_calc" onclick="$('#tva_calc').val('inner'); checkTotal(); flipButtons('b_calc', 'inner');"><?= Terms::translate('tva_inner', 'cabinet') ?></button>
            <button id="b_calc_over"  type="button" class="btn btn-outline-<?= $bprofile->tva_calc == 'over'  ? 'primary' : 'default' ?> b_calc" onclick="$('#tva_calc').val('over');  checkTotal(); flipButtons('b_calc', 'over');"><?= Terms::translate('tva_over', 'cabinet') ?></button>
        </div>
<?/**?>
		<li class="nav-item"><a href="#tva-4" data-bs-toggle="tab" class="nav-link <?= $bprofile->tva_calc == 'inner' ? 'active' : '' ?>" onclick="$('#tva_calc').val('inner'); checkTotal();"><?= Terms::translate('tva_inner', 'cabinet') ?></a></li>
		<li class="nav-item"><a href="#tva-5" data-bs-toggle="tab" class="nav-link <?= $bprofile->tva_calc == 'over' ? 'active' : '' ?>" onclick="$('#tva_calc').val('over'); checkTotal();"><?= Terms::translate('tva_over', 'cabinet') ?></a></li>
<?/**/?>
	</ul>
</div>
<div class="form-group col-md-1 col-sm-12 col-xs-12 show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
	<input id="tva_rate" name="invoice[tva_rate]" value="<?= $bprofile->tva_rate ?>" type="hidden">
	<input id="tva_calc" name="invoice[tva_calc]" value="<?= $bprofile->tva_calc ?>" type="hidden">
</div>

<div class="form-group mt-1 mb-2 col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>

<div class="row col-12 ms-1">
<div class="form-group input drow col-md-5 col-sm-8 col-xs-4">
    <label class="form-label" for="message-translation_alias"> &nbsp; # / <?= Terms::translate('description', 'invoice') ?></label>
</div>
<div class="form-group input drow col-md-2 col-sm-4 col-xs-4">
    <label class="form-label" for="message-translation_alias"><?= Terms::translate('unit_price', 'invoice') ?></label>
</div>
<div class="form-group input drow col-md-1 col-sm-2 col-xs-4">
    <label class="form-label" for="message-translation_alias"><?= Terms::translate('quantity', 'cabinet') ?></label>
</div>
<div class="form-group input drow col-md-1 col-sm-2 col-xs-4">
    <label class="form-label" for="message-translation_alias">&nbsp;</label>
</div>
<div class="form-group input drow col-md-2 col-sm-4 col-xs-4">
    <label class="form-label" for="message-translation_alias"><?= Terms::translate('total_amount', 'cabinet') ?></label>
</div>
<div class="form-group col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>
<br clear="all">

<div id="row_space" style="width:100%">
<? $i = 0; $total = 0.0; foreach($items as $k => $v) { ?>

<div class="row" id="row_<?= $i ?>" style="margin-left:0;margin-right:0">

    <div class="form-group input drow col-xl-5 col-md-5 col-sm-8 col-xs-4">
        <div class="form-group input drow seq" style="float:left;font-weight:bold; width:1.4rem;text-align:right;margin: 3px 4px 0 0;white-space: nowrap;"><?= $v->Code ?>.</div>
        <div style="float:left;width:90%"><?= \yii\helpers\Html::textarea('name_'.$i, $v->Name, ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:2.2rem;'])?></div>
    </div>

    <div class="form-group input drow col-xl-2 col-md-2 col-sm-4 col-xs-4">
    	<div class="input-group">
        <?= \yii\helpers\Html::input('text', 'unit_price_'.$i, isset($v->UnitPrice) ? number_format(strval(floatval($v->UnitPrice)), 2, '.', ' ') : '', ['class' => 'form-control text-end', 'autocomplete' => 'off', 'readonly' => false,
            'id' => 'unit_price_'.$i, /*'pattern' => '\d+(\.\d{,2})?',*/
            'onchange' => '$("#total_price_'.$i.'").val(ReplaceNumberWithCommas(parseFloat($("#unit_price_'.$i.'").val().replace(" ", "") * parseInt("0"+$("#qnt_'.$i.'").val().replace(" ", "")) ).toFixed(2))); checkTotal();']) ?>
    	<span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;"><?= $invoice[0]->currency ?></span>
    	</div>
    </div>

    <div class="form-group input drow col-xl-1 col-md-1 col-sm-2 col-xs-4">
        <?= \yii\helpers\Html::input('text', 'qnt_'.$i, $v->Quantity, ['id' => 'qnt_'.$i, 'class' => 'form-control text-end', 'autocomplete' => 'off', 'readonly' => false,
            'type' => 'number', 'pattern' => '[0-9]', 'min' => '1', 'max' => '1000000', 'style' => 'padding-right:2px',
            'onchange' => '$("#total_price_'.$i.'").val(ReplaceNumberWithCommas(parseFloat($("#unit_price_'.$i.'").val().replace(" ", "") * parseInt("0"+$("#qnt_'.$i.'").val().replace(" ", "")) ).toFixed(2))); checkTotal();' ]) ?>
    </div>
    <div class="form-group input drow col-xl-1 col-md-1 col-sm-2 col-xs-4">
        <?= \yii\helpers\Html::input('text', 'unit_'.$i, $v->UnitOfMeasure, ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false])?>
    </div>

    <div class="form-group input drow col-xl-2 col-lg-2 col-md-2 col-sm-4 col-xs-4">
    	<div class="input-group">
    	<?= \yii\helpers\Html::input('hidden', 'total_tva_'.$i, '', ['id' => 'total_tva_'.$i])?>
        <?= \yii\helpers\Html::input('text', 'total_price_'.$i, number_format(strval(floatval($v->TotalPrice)), 2, '.', ' '), ['class' => 'form-control text-end', 'autocomplete' => 'off', 'readonly' => true, 'id' => 'total_price_'.$i])?>
        <span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;"><?= $invoice[0]->currency ?></span>
        </div>
    </div>

    <div class="form-group input drow hide_@@ col-xl-1 col-lg-1 col-md-1 col-sm-2 col-xs-4" style="width:3%;font-weight:bold; display:<?= $i > 0 ? 'block' : 'none' ?>">
    	<button type="button" class="btn btn-outline-danger rounded-pill waves-effect waves-light ms-2" style="width:2.4rem;float:right" title="Удалить строку" onclick="$('#row_<?= $i ?>').remove(); checkTotal(); return false;">
    		<i class="fa fa-times" style="font-size:1rem;margin-left:-1px;"></i>
    	</button>
    </div>

    <div class="form-group col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>

</div>

<? $i++; $total += floatval($v->TotalPrice); } ?>
</div>

<script type="text/javascript">
var ii = <?= $i-1 ?>;
</script>

    <div class="form-group input drow col-xxl-6 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
    	<button type="button" class="btn btn-outline-primary rounded-pill waves-effect waves-light me-2 mt-2 mb-1" style="width:auto"
        	onclick="ii++; if (ii > 19) { alert('<?= Terms::translate('lines_limit', 'cabinet') ?>'); } else { $('#row_space').append($.parseHTML($('#row_pattern').clone().html().replace(/@@/g, ii).replace(/##/g, ii+1).replace(/ total_price_tmp/g, '').replace(/seq_tmp/g, 'seq'))); } checkTotal(); return false;">
    		<span class="btn-label"><i class="mdi mdi-plus"></i></span><?= Terms::translate('add_line', 'cabinet') ?>
    	</button>
    </div>
    <div class="form-group input drow col-xxl-4 col-xl-2 col-lg-2 col-md-3 col-sm-4 col-xs-4 text-end">
        <div class="input-group show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
        	<input class="form-control text-end" readonly value="<?= Terms::translate('total_to_pay_w_tva', 'invoice') ?>:" style="margin-top:2px;font-weight:bold">
        </div>
        <div class="input-group show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
        	<input class="form-control text-end" readonly value="<?= Terms::translate('total_tva', 'invoice') ?>:" style="margin-top:2px;font-weight:bold">
        </div>
        <div class="input-group">
        	<input class="form-control text-end" readonly value="<?= Terms::translate('total_to_pay', 'invoice') ?>:" style="margin-top:2px;font-weight:bold">
        </div>
    </div>
	<div class="form-group input drow col-xxl-2 col-xl-2 col-lg-2 col-md-2 col-sm-4 col-xs-4">
		<div class="input-group show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
    		<?= \yii\helpers\Html::input('text', 'wtva_amount', number_format($invoice[0]->wtva_amount, 2, '.', ' '), ['class' => 'form-control text-end', 'id' => 'wtva_amount', 'autocomplete' => 'off', 'readonly' => true, 'style' => 'font-weight:bold;margin-top:2px;'])?>
    		<span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;margin-top:2px;"><?= $invoice[0]->currency ?></span>
    	</div>
		<div class="input-group show_tva" <?= $bprofile->no_tva == '0' ? '' : 'style="display:none"' ?>>
    		<?= \yii\helpers\Html::input('text', 'tva_amount', number_format($invoice[0]->tva_amount, 2, '.', ' '), ['class' => 'form-control text-end', 'id' => 'tva_amount', 'autocomplete' => 'off', 'readonly' => true, 'style' => 'font-weight:bold;margin-top:2px;'])?>
    		<span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;margin-top:2px;"><?= $invoice[0]->currency ?></span>
    	</div>
		<div class="input-group">
    		<?= \yii\helpers\Html::input('text', 'amount', number_format($invoice[0]->amount, 2, '.', ' '), ['class' => 'form-control text-end', 'id' => 'amount', 'autocomplete' => 'off', 'readonly' => true, 'style' => 'font-weight:bold;margin-top:2px;'])?>
    		<span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;margin-top:2px;"><?= $invoice[0]->currency ?></span>
    	</div>
	</div>
</div>

<div class="form-group mt-2 mb-2 col-md-12 col-sm-12 col-xs-12" style="border-top: dotted 2px #cccccc"></div>

	<div class="form-group input col-xxl-4 col-xl-4 col-md-3 col-lg-12 col-sm-12">
        <label class="form-label"><?= Terms::translate('invoice_template', 'subdivision') ?></label> <br/>
        <select class="custom-select" name="invoice[invoice_pattern]"<?/**?> readonly="1" disabled="1"<?/**/?>>
        	<? foreach ($ipattern_list as $v) { ?>
			<option value="<?= $v['value'] ?>" <?= (isset($bprofile->invoice_pattern) && $v['value'] == $bprofile->invoice_pattern) ? ' selected' : '' ?>><?= $v['caption'] ?></option>
			<? } ?>
        </select>
    </div>

	<div class="form-group input col-xxl-2 col-xl-2 col-md-2 col-lg-12 col-sm-12">
        <label class="form-label"><?= Terms::translate('invoice_lang', 'cabinet') ?></label>
        <select class="custom-select" name="invoice[pattern_language]">
        	<? foreach ($language_list as $k => $v) { ?>
			<option value="<?= $k ?>" <?= (isset($bprofile->preferred_language) && $k == $bprofile->preferred_language) ? 'selected' : '' ?>><?= $v ?></option>
			<? } ?>
        </select>
    </div>
	<div class="form-group input col-xxl-2 col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
        <label class="form-label"><?= Terms::translate('currency', 'invoice') ?></label>
        <input type="hidden" name="currency" value="MDL">
        <select class="custom-select" name="currency" id="curr_list" onchange="$('.curr').html('MDL'); /*$('.curr').html($(this).val());*/" disabled="true">
        <? foreach ($currency_list as $k => $v) { ?>
    		<option value="<?= $k ?>" <?= $k == 'MDL' ? ' selected' : '' ?>><?= $v ?></option>
    	<? } ?>
        </select>
    </div>

    <div class="form-group input mt-2 mb-2 col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="form-label" for="currency"><?= Terms::translate('manager_remark', 'cabinet') ?></label>
		<?= \yii\helpers\Html::textarea('remark', str_replace([" <br>", "<br>", "<br />"], "\n", $invoice[0]->remark), ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:4.5rem'])?>
		<div class="help-block"></div>
	</div>
    <div class="form-group input mt-2 mb-2 col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="form-label" for="currency"><?= Terms::translate('manager_topic', 'cabinet') ?></label>
		<?= \yii\helpers\Html::textarea('topic', str_replace([" <br>", "<br>", "<br />"], "\n", $invoice[0]->topic), ['class' => 'form-control', 'autocomplete' => 'off', 'readonly' => false, 'style' => 'height:4.5rem'])?>
		<div class="help-block"></div>
	</div>

		</div>
	</div>
</div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
					<a href="/<?= Yii::$app->language ?>/outgoing<?//= $context['business'][$sb]['business_token'] ?>">
                		<button type="button" class="btn btn-primary rounded-pill waves-effect waves-light me-2">
                    		<span class="btn-label"><i class="mdi mdi-chevron-double-left"></i></span><?= Terms::translate('go_to_list', 'button') ?>
                    	</button>
					</a> &nbsp;
<?/**/?>
					<button type="reset" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-2">
                    	<?= Terms::translate('reset', 'button') ?>&nbsp;<span class="btn-label-right"><i class="mdi mdi-close"></i></span>
     				</button> &nbsp;
					<button type="submit" class="btn btn-success rounded-pill waves-effect waves-light me-2">
                    	<?= Terms::translate('save', 'button') ?><?//= Yii::t('apl', 'update_popup') ?><span class="btn-label-right"><i class="mdi mdi-download"></i></span>
     				</button> &nbsp;
    				<button type="submit" class="btn btn-primary rounded-pill waves-effect waves-light" onclick="$('#mode').val('stay<?= $flag_copy ? '_create' : '' ?>');">
    					<span class="ms-1"><?= Terms::translate('save_to_list', 'common_form') ?></span><span class="btn-label-right"><i class="mdi mdi-download"></i></span>
    				</button>
<?/**/?>
     				<?//= common\models\Helper::GUID(); ?>
                </div>
            </div>
		</div>
	</div>

</form>
</div>



<div id="row_pattern" style="width:100%;display:none">
<div class="row" id="row_@@" style="margin-left:0;margin-right:0">

<?/**?><div class="form-group input drow seq_tmp col-xl-1 col-md-1 col-sm-2 col-xs-4" style="width:3%;padding-top:4px;font-weight:bold;">##</div><?/**/?>
<div class="form-group input drow col-xl-5 col-md-5 col-sm-8 col-xs-4">
	<div class="form-group input drow seq_tmp" style="float:left;font-weight:bold; width:1.4rem;text-align:right;margin: 3px 4px 0 0;white-space: nowrap;">##</div>
    <div style="float:left;width:90%"><textarea class="form-control" name="name_@@" autocomplete="off" style="height:2.4rem"></textarea></div>
</div>

<div class="form-group input drow col-xl-2 col-md-2 col-sm-4 col-xs-4">
	<div class="input-group">
    <input type="text" class="form-control text-end" name="unit_price_@@" id="unit_price_@@" value="" autocomplete="off"
    	onchange="$('#total_price_@@').val(ReplaceNumberWithCommas(parseFloat($('#unit_price_@@').val().replace(' ', '') * $('#qnt_@@').val().replace(' ', '')).toFixed(2))); checkTotal();">
    <span class="input-group-text curr" style="width:3rem;height:2.4rem;padding-left:0.5rem;font-weight:bold;">MDL</span>
    </div>
</div>
<div class="form-group input drow col-xl-1 col-md-1 col-sm-2 col-xs-4">
    <input type="number" class="form-control text-end" name="qnt_@@" id="qnt_@@" value="1" autocomplete="off" min="1" max="1000000" style="padding-right:2px"
    	onchange="$('#total_price_@@').val(ReplaceNumberWithCommas(parseFloat($('#unit_price_@@').val().replace(' ', '') * $('#qnt_@@').val().replace(' ', '')).toFixed(2))); checkTotal();">
</div>
<div class="form-group input drow col-xl-1 col-md-1 col-sm-2 col-xs-4">
    <input type="text" class="form-control" name="unit_@@" value="pcs" autocomplete="off">
</div>
<div class="form-group input drow col-xl-2 col-md-2 col-sm-4 col-xs-4">
	<div class="input-group">
    <input type="hidden" class="form-control total_tva_tmp" name="total_tva_@@" id="total_tva_@@" value="" autocomplete="off" readonly="on">
    <input type="text" class="form-control total_price_tmp text-end" name="total_price_@@" id="total_price_@@" value="" autocomplete="off" readonly="on">
    <span class="input-group-text curr" style="width:3rem;height:2.4rem;padding: 2px 0 0 0.5rem;font-weight:bold;">MDL</span>
    </div>
</div>

<?/**/?>
<div class="form-group input drow hide_@@ col-xl-1 col-lg-1 col-md-1 col-sm-2 col-xs-4" style="width:3%;font-weight:bold">
	<button type="button" class="btn btn-outline-danger rounded-pill waves-effect waves-light ms-2" style="width:2.4rem;float:right"
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

<?= AlertToastr::widget() ?>

<?/**?>
<br clear="all">
<section class="content">
<pre><?php var_dump($context) ?></pre>
</section>
<?/**/?>
