<?php
//    use yii\helpers\Html;
//    use yii\helpers\Url;
//    use yii\widgets\LinkPager;
//    use manager\models\User;
use openecontmd\backend_api\models\SysLang;
use openecontmd\backend_api\models\Terms;
use openecontmd\backend_api\models\Content;
//use openecontmd\backend_api\assets\BackendAsset;

Yii::$app->language = 'en';

$this->title = 'Starter Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];

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

//    $cdn_path = Yii::$app->params['cdn_url']."?mode=cash&w=100&c=1&mime=png&url=".Yii::$app->params['self_url'];
    $cdn_path = '';//Yii::$app->params['self_url'];
    $this->title = 'Outgoing'; //($table) ? Yii::t('apl', 'title_' . $table['SysTableName']) : 'Узел меню';
    $this->params['breadcrumbs'][] = $this->title;
//    $this->title = 'Термины';
//   onclick="$("#search_value_\' + (key + 1) + \'").val("123")"
    $langs = SysLang::getLanguageNames();
/**/

//$sys_menu_header = json_decode($item->sys_menu_header);

//echo "<pre>"; var_dump($context['client']->alias, $business, 'facturare', Yii::$app->language, 'invoices_manual'); echo "</pre>"; exit;
$options_list = Content::getUserOptions($context['client']->alias, $business, 'facturare', Yii::$app->language, 'invoices_manual');
//echo "<pre>"; var_dump($options_list); echo "</pre>"; exit;

if (isset($options_list[0])) {
    $context_ids = Content::getUserContextIDs($context['client']->client_id, $business, 'facturare', 0);

//    echo "<pre>"; var_dump($context_ids['client_id'], $context_ids['business_id'], 0, $options_list[0]['outs'], Yii::$app->language, "'api_google','api_gmail'"); echo "</pre>"; exit;
    $outs = Content::getUserOuts($context_ids, $context_ids['business_id'], 0, $options_list[0]['outs'], Yii::$app->language, "'api_google','api_gmail'");
//    echo "<pre>"; var_dump($context_ids, $outs); echo "</pre>"; exit;
}
else
{
    $context_ids = $outs = [];
}

//echo "<pre>"; var_dump($outs); echo "</pre>"; //exit;
$outs_list = '';
foreach ($outs as $ko => $vo) {
    $outs_list .= '&#9658;&nbsp;'.$vo.'<br>';
}

$sl = [];
$status_list = Content::getStatuses(Yii::$app->language);
foreach ($status_list as $v) $sl[$v['alias']] = $v['status'];
unset($status_list);
//echo "<pre>"; var_dump($sl); echo "</pre>"; exit;

$this->registerJs('var buyers={};', 1);

/*
$cap = [];
foreach ($options_list as $k1 => $v1) {
    $cap[$v1['integration_alias']] = json_decode($v1['caption'])->{Yii::$app->language};
}
*/
//echo "<pre>"; var_dump($context_ids, $outs, $outs_list); echo "</pre>"; exit;




?>
<?/**?>
<script>
function copyToClipboard() {
    const str = document.getElementById('key_to_copy').value;
    const el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
//    alert(`Ключ скопирован в буфер обмена`);
}
</script>
<?/**/?>
<style>
.input-group-text {
  padding: 0.2rem 0.9rem;
}
#example sub {
    display:none;
}
</style>

    <div class="row">
        <div class="col-12 mt-1">
        	<div class="d-block d-lg-none"><h4 class="header-title mb-2"><?= $this->title ?></h4></div>
		</div>
	</div>

<? // data-tippy="ABCD" data-original-title="I'm a Tippy tooltip!"
/**/
    if ($table) {
/*
    if (!Yii::$app->params['showset'] == Yii::$app->request->cookies->getValue('showset'))
        Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => 'showset', 'value' => 'ro,ru,en']));
    else
        Yii::$app->params['showset'] = Yii::$app->request->cookies->getValue('showset');
*/

//    var_dump(Yii::$app->getRequest()->getCookies()->getValue('showset', (isset($_COOKIE['showset'])) ? $_COOKIE['showset'] : 'showset'));
//    var_dump(Yii::$app->request->cookies->getValue('showset'));
//    var_dump($_COOKIE['showset']);
//    Yii::$app->params['showset'] = Yii::$app->getRequest()->getCookies()->getValue('showset', (isset($_COOKIE['showset'])) ? $_COOKIE['showset'] : 'showset');

/*


        {
            "targets": [1,2],
            "orderable": true
        },

        {
            "targets": [1,2],
            "orderable": true,
            "searchable": true,
            "visible": true,
            "className": "dt-body-right"
        },

*/
    $js_text = '
/**
$.extend( true, $.fn.dataTable.defaults, {
    "searching": true,
    "ordering": true,
//    "stateSave": true
} );
/**/

var showset = { "ro":true, "ru":true, "en":true };
var filterset = { "all":true, "actual":false, "deadline":false, "expired":false, "rejected":false, "archived":false };


function checkCookieValuesF(f) {
    if (f.length > 0) {
       var cook = $.cookie("filterset");
       if (cook !== undefined) {
';
    foreach (['all', 'actual', 'deadline', 'expired', 'rejected', 'archived'] as $lv) {
        $js_text .= '           $("#filterset_'.$lv.'").removeClass("active"); filterset.'.$lv.' = ("'.$lv.'" == f);'."\n";
    }
    $js_text .= '           $("#filterset_" + f).addClass("active");
       }
       $.cookie("filterset", filterset, { expires : 2, path: "/", secure  : false });
    }
}

function str_highlight_text(string, str_to_highlight) {
    if (!str_to_highlight) return string;
    var sp = str_to_highlight.split(" ");
    var re = [];
    sp.forEach(function callback(currentValue, index, array) {
        re[index] = "<span style=\"background-color:#ffa;\">" + currentValue + "</span>";
    });
    return replaceBulk(string, sp, re);
}

function replaceBulk( str, findArray, replaceArray ){
  var i, regex = [], map = {};
  for( i=0; i<findArray.length; i++ ){
    regex.push( findArray[i].replace(/([-[\]{}()*+?.\\^$|#,])/g, "\\$1") );
    map[findArray[i]] = replaceArray[i];
  }
  regex = regex.join("|");
  str = str.replace( new RegExp( regex, "g" ), function(matched){
    return map[matched];
  });
  return str;
}

var selectors = [];
';
/**/
foreach ($selectors as $k1 => $v1) {
    $js_text .=  "selectors['$k1'] = [];\n";
    foreach ($v1 as $k2 => $v2) {
        if ($k1 == 'status')
            $js_text .=  "selectors['$k1']['$k2'] = '".(isset($sl[$k2]) ? htmlspecialchars($sl[$k2], ENT_QUOTES) : '---')."';\n";
        else
            $js_text .=  "selectors['$k1']['$k2'] = '".htmlspecialchars($v2, ENT_QUOTES)."';\n";
    }
}
/**/

if ( in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '176.123.2.203']) )
    $icons_set = 'V/E/C/D/P/S'; // /G/I
else
    $icons_set = 'V/E/C/D/P/S'; // /G

$js_text .= '
$(document).ready(function() {

$.cookie.json = true;
/*
if ($.cookie("showset") !== undefined) { showset = $.cookie("showset"); }
checkCookieValues();
*/

/*
if ($.cookie("filterset") !== undefined) { filterset = $.cookie("filterset"); }
checkCookieValuesF("");
*/
var s, r, gs = "";
var table = $(\'#example\').DataTable( {

//  Upper Paging & So On  =============================
    dom:    "<\'row\' <\'col-sm-4 mt-1\'l> <\'col-sm-4\'i> <\'col-sm-4 d-flex justify-content-end\'p> >" +
            "<\'row\'<\'col-sm-12\'tr>>" +
            "<\'row\' <\'col-sm-4 mt-1\'l> <\'col-sm-4\'i> <\'col-sm-4 d-flex justify-content-end\'p> >",


    buttons: [
        "searchPanesClear",
        "searchPanes"
    ],

//  Main Settings
        "processing": true,
        "serverSide": true,

//        "order": [[1, "asc"], [2, "asc"]],
        "order": [[2, "desc"]],

//        "order": [[]],
//        "colReorder": true,

  "search": {
//    "search": "At",
    "return": true,
//    "caseInsensitive": true
  },

//        "search": { return: true },
//        "searching": true,

        "paging": true,
        "pageLength": 10,
        "paginate": 10,
//        "autoWidth":false,

        columnDefs: [
        {
            "targets": [0],
            "orderable": false,
            "searchable": false
        },
     ],

//  Columns defenition   =============================
        "columns": [
            { "data": "'.$key_name.'",
              "width": "133px",
              "className": "dt-nowrap text-end",
               render: function(data, type, raw) {
                    var v = "'.$icons_set.'".split("/");   // + " / " + raw["id"];
                    var t = "";
                    v.forEach(function callback(currentValue, index, array)
                    {
                        switch(array[index])
                        {
//                            case "V": t = t + "<a href=\"" + data + "\" class=\"link-primary\"><i class=\"fe-eye ms-1 me-1 cursor-pointer\"></i></a>"; break;
                            case "V": t = t + "<a href=\"outgoing/view/" + raw.inner_hash + "\" class=\"link-primary\">&nbsp;<i class=\"far fa-eye cursor-pointer\" style=\"font-size:1.1em; margin: 0 5px;\" title=\"'.(Terms::translate('view', 'invoice')).'\"></i></a>"; break;

                            case "E":
                                if (raw.status == "draft")
                                    t = t + "<a href=\"outgoing/edit/" + raw.inner_hash + "\" title=\"'.(Terms::translate('edit', 'invoice')).'\">&nbsp;<i class=\"far fa-edit text-success\" style=\"font-size:1.1em; margin: 0 5px;\"></i></a>";
                                else
                                    t = t + "<i class=\"fe-edit ms-1 me-1 text-muted\" style=\"font-size:1.3em\" title=\"'.(Terms::translate('edit', 'invoice')).'\"></i>";
                            break;

                            case "C":
                                    t = t + "<a href=\"copy/" + raw.inner_hash + "\" title=\"'.(Terms::translate('copy', 'invoice')).'\">&nbsp;<i class=\"far fa-copy text-info\" style=\"font-size:1.1em; margin: 0 5px;\"></i></a>";
                            break;

//                            case "C": t = t + "<a href=\"actions/copy?tab='.$table->SysTableName.'&ids=" + data + "&item='.$parent.'\" title=\"Copy\"><i class=\"mdi mdi-content-copy text-primary\" style=\"font-size:1.1em; margin: 0 5px;\"></i></a>"; break;
                            case "D":
                                if (raw.status == "draft")
                                    t = t + "<a href=\"delete/'.$business.'/" + raw.inner_hash + "\" data-id=\"" + data + "\" data-table=\"'.$table->SysTableName.'\" data-message=\"Delete\" title=\"'.(Terms::translate('delete', 'invoice')).'\" class=\"delete_item\"><i class=\"mdi mdi-trash-can-outline ms-1 me-1 text-danger\" style=\"font-size:1.4em\"></i></a>";
                                else
                                    t = t + "<i class=\"mdi mdi-trash-can-outline ms-1 me-1 text-muted\" style=\"font-size:1.4em\" title=\"'.(Terms::translate('delete', 'invoice')).'\"></i>";
                            break;
//                            case "S": t = t + "<a href=\"send/" + raw.inner_hash + "\" title=\"Send\"><i class=\"fa-envelope text-success\" style=\"font-size:1.3em\"></i></a>"; break;

case "S":
//if ( (raw.contact_email != "") && ( (raw.status == "actual") || (raw.status == "sended") ) )
if ( true )
    t = t + "<a class=\"cursor-pointer\" data-id=\"" + raw.inner_hash + "\" data-toggle=\"modal\" data-target=\"#send-modal\"\
    onclick=\"send_key = \'" + raw.inner_hash + "\'; $(\'#send_id\').val(\'" + raw.inner_hash + "\'); $(\'#send_name\').val(\'" + raw.outer_number + "\');\
    $(\'#send_text\').html(\''.(Terms::translate('invoice', 'popup')).': <b>" + raw.outer_number + "</b> '.(Terms::translate('dated', 'popup')).' <b>" + raw.issue_date + "</b><br><b>" + raw.amount + " " + raw.currency + "</b>, '.(Terms::translate('up_to', 'popup')).' <b>" + raw.due_on + "</b><br><br>E-Mail: <b>" + raw.send_email + "</b>\');\">\
    &nbsp; <i class=\"far fa-envelope text-success\" style=\"font-size:1.2em;cursor:pointer\" title=\"'.(Terms::translate('send', 'invoice')).'\"></i></a>";
else
    t = t + "&nbsp; <i class=\"fa fa-envelope text-secondary\" style=\"font-size:1.2em\" title=\"'.(Terms::translate('send', 'invoice')).'\"></i>";
break;

';

/*  test  */
$js_text .= '
case "P": t = t + "&nbsp; <a class=\"cursor-pointer\" href=\"http://admin-test.repo.tst/payment/v1/merchant/init?order_hash=" + raw.inner_hash + "&order_number=" + raw.outer_number + "\" target=\"_blank\">\
<i class=\"fa fa-credit-card text-primary\" style=\"font-size:1.2em;cursor:pointer\" title=\"'.Terms::translate('modal_push', 'popup').'\"></i></a>"; break;
';


/* new
$js_text .= '
case "P": t = t + "&nbsp; <a class=\"cursor-pointer\" data-id=\"" + raw.inner_hash + "\" data-toggle=\"modal\" data-target=\"#push-modal\"\
onclick=\"push_key = \'" + raw.inner_hash + "\'; $(\'#push_id\').val(\'" + raw.inner_hash + "\'); $(\'#push_name\').val(\'" + raw.outer_number + "\');\
$(\'#push_text\').html(\''.(Terms::translate('invoice', 'popup')).': <b>" + raw.outer_number + "</b> '.(Terms::translate('upload', 'invoice')).' <b>" + raw.issue_date + "</b><br><b>" + raw.amount + " " + raw.currency + "</b><br><br><b>'.(Terms::translate('integrations', 'popup')).':</b><br>'.$outs_list.'\');\">\
<i class=\"fa fa-credit-card text-primary\" style=\"font-size:1.2em;cursor:pointer\" title=\"'.Terms::translate('modal_push', 'popup').'\"></i></a>"; break;
';
*/

/*
if (count($outs) > 0)
    $js_text .= '
case "P": t = t + "[<a class=\"cursor-pointer\" data-id=\"" + raw.inner_hash + "\" data-bs-toggle=\"modal\" data-bs-target=\"#push-modal\"\
onclick=\"push_key = \'" + raw.inner_hash + "\'; $(\'#push_id\').val(\'" + raw.inner_hash + "\'); $(\'#push_name\').val(\'" + raw.outer_number + "\');\
$(\'#push_text\').html(\''.(Terms::translate('invoice', 'popup')).': <b>" + raw.outer_number + "</b> '.(Terms::translate('upload', 'invoice')).' <b>" + raw.issue_date + "</b><br><b>" + raw.amount + " " + raw.currency + "</b><br><br><b>'.(Terms::translate('integrations', 'popup')).':</b><br>'.$outs_list.'\');\">\
<i class=\"fa-solid fa-cloud-arrow-down ms-1 me-1 text-success\" style=\"font-size:1.2em\" title=\"'.Terms::translate('modal_push', 'popup').'\"></i></a>"; break;
';
    else
        $js_text .= '
case "P": t = t + "]<a class=\"cursor-pointer\"><i class=\"fa-solid fa-cloud-arrow-down ms-1 me-1 text-muted\" style=\"font-size:1.2em\" title=\"'.Terms::translate('modal_push', 'popup').'\"></i></a>"; break;
';
*/


$js_text .= '
case "G": t = t + "<a href=\"order/" + raw.inner_hash + "\" target=\"_blank\" class=\"link-primary\" title=\"Preview order page\"><i class=\"fa-solid fa-magnifying-glass ms-1 me-1 cursor-pointer\" style=\"font-size:1.1em\"></i></a>"; break;
';

$js_text .= '
case "I":
    t = t + "<a href=\"sign/'.$business.'/" + raw.inner_hash + "\" class=\"link-primary\"><i class=\"far fa-pen ms-1 me-1 cursor-pointer\" style=\"font-size:1.3em\" title=\"'.(Terms::translate('sign', 'invoice')).'\"></i></a>";
/*
if (raw.status == "actual")
    t = t + "<a class=\"cursor-pointer\" data-id=\"" + raw.inner_hash + "\" data-bs-toggle=\"modal\" data-bs-target=\"#sign-modal\"\
    onclick=\"sign_key = \'" + raw.inner_hash + "\'; $(\'#sign_id\').val(\'" + raw.inner_hash + "\'); $(\'#sign_name\').val(\'" + raw.outer_number + "\');\
    $(\'#sign_text\').html(\''.(Terms::translate('invoice', 'popup')).': <b>" + raw.outer_number + "</b> '.(Terms::translate('dated', 'popup')).' <b>" + raw.issue_date + "</b><br><b>" + raw.amount + " " + raw.currency + "</b>, '.(Terms::translate('up_to', 'popup')).' <b>" + raw.due_on + "</b><br>\');\">\
    <i class=\"far fa-user-pen ms-1 me-1 text-primary\" style=\"font-size:1.2em\" title=\"Подписать счет'.(Terms::translate('sign', 'invoice')).'\"></i></a>";
else
    t = t + "<i class=\"far fa-pen-nib ms-1 me-2 text-success\" style=\"font-size:1.2em\" title=\"Подписано'.(Terms::translate('signed', 'invoice')).'\"></i>";
*/
break;
';


//  case "P": t = t + "<a href=\"'.Yii::$app->params['api_url'].'order/" + raw.inner_hash + "\" target=\"_blank\" class=\"link-primary\" title=\"Preview order page\"><i class=\"fa-solid fa-magnifying-glass ms-1 me-1 cursor-pointer\" style=\"font-size:1.1em\"></i></a>"; break;
$js_text .= '
                        }
                    });
                    return t;
               }
            },';

    //$shift = 0;
    $fieldlist = '`'.$key_name.'`'; $showsetlist = '';
    foreach ($rows as $k => $v) {
//var_dump($columns[$v]->FieldType);

        $sel = (isset($selectors[$columns[$v]->FieldName]) && count($selectors[$columns[$v]->FieldName]) > 0 ) ? 'selector' : $columns[$v]->FieldType;
//var_dump($sel);
        switch ($sel)
        {
//            case 'int':
//            case 'float':  decimal  2 / 4 / 6
            default:
                $js_text .= '{ "data": "'.$v.'",'."\n";
                $js_text .= '"render": function(data, type, raw) { if (data) return data; else return ""; }'."\n";
                $js_text .= '},'."\n";
                $fieldlist .= ', `'.$v.'`';
            break;
            case 'selector':
                $js_text .= '{ "data": "'.$v.'",'."\n";
                //$js_text .= '  "class": "text-purple",'."\n";
//                $js_text .= '"render": function(data, type, raw) { if (selectors[\''.$v.'\'][data]) { s = ($("#search_value_G").val() != "") ? $("#search_value_G").val() : $("#search_value_'.($k+1).'").val(); return selectors[\''.$v.'\'][data] + \'<br>\' + (str_highlight_text(data, s) != \'\' ? \'{\'+str_highlight_text(data, s)+\'}\' : \'\'); } else return "- - -"; }'."\n";
                $js_text .= '"render": function(data, type, raw) { if (selectors[\''.$v.'\'][data]) { s = ($("#search_value_G").val() != "") ? $("#search_value_G").val() : $("#search_value_'.($k+1).'").val(); return selectors[\''.$v.'\'][data]; } else return "- - -"; }'."\n";
                $js_text .= '},'."\n";
                $fieldlist .= ', `'.$v.'`';
            break;
            case 'varchar':
                $js_text .= '{ "data": "'.$v.'",'."\n";
                $js_text .= '"render": function(data, type, raw) { if (data) { s = ($("#search_value_G").val() != "") ? $("#search_value_G").val() : $("#search_value_'.($k+1).'").val(); return str_highlight_text(data, s) } else return ""; }'."\n";
                $js_text .= '},'."\n";
                $fieldlist .= ', `'.$v.'`';
            break;
            case 'int':
                $js_text .= '{ "data": "'.$v.'",'."\n";
                $js_text .= '  "class": "text-end",'."\n";
                $js_text .= '"render": function(data, type, raw) { if (data) return data+"&nbsp;"; else return ""; }'."\n";
                $js_text .= '},'."\n";
                $fieldlist .= ', `'.$v.'`';
            break;
            case 'decimal':
                $js_text .= '{ "data": "'.$v.'",'."\n";
                $js_text .= '  "class": "text-end",'."\n";
                $js_text .= '"render": function(data, type, raw) { if (data) return data+"&nbsp;"; else return ""; }'."\n";
                $js_text .= '},'."\n";
                $fieldlist .= ', `'.$v.'`';
            break;
            case 'date':
                $js_text .= '{ "data": "'.$v.'",'."\n";
                $js_text .= '  "class": "dt-nowrap text-end",'."\n";
                //$js_text .= '"render": function(data, type, raw) { if (data) return data; else return ""; }'."\n";
/*
                $js_text .= '"render": function(data, type, raw) { if (data) { var icons = \'\';
                    if (raw.is_archived == \'1\') icons = icons + \'<i class=\"far fa-archive text-secondary fs-4\" title="Archived"></i>&nbsp;&nbsp;\';
                    if (raw.source_create == \'self\') icons = icons + \'<i class=\"far fa-hand-paper text-info fs-4\" title="Hand made"></i>&nbsp;&nbsp;\';
                    else  icons = icons + \'<i class=\"far fa-file-download text-danger fs-4\" title="Imported"></i>&nbsp;&nbsp;\';
                    return icons + data;
                } else return ""; }'."\n";
*/
                $js_text .= '"render": function(data, type, raw) { if (data) { var icons = \'\'; var color = \'\';
                    //if (raw.is_archived == \'1\') icons = icons + \'<i class=\"far fa-archive text-secondary fs-4\" title="Archived"></i>&nbsp;&nbsp;\';
                    if (raw.expired != \'0\') color = \'danger\'; else color = \'info\';
                    if (raw.status == \'draft\') icons = icons + \'<i class=\"far fa-file-lines text-success fs-4\" title="Draft"></i>&nbsp;&nbsp;\';
                    if (raw.status == \'actual\') icons = icons + \'<i class=\"far fa-circle-dollar text-\' + color + \' fs-4\" title="К оплате"></i>&nbsp;&nbsp;\';
                    if (raw.status == \'sended\') icons = icons + \'<i class=\"far fa-envelope text-info fs-4\" title="Выслан плательщику"></i>&nbsp;<i class=\"far fa-circle-dollar text-\' + color + \' fs-4\" title="К оплате"></i>&nbsp;&nbsp;\';
                    if (raw.status == \'part\') icons = icons + \'<i class=\"far fa-circle-half-stroke text-info fs-4\" title="Частично оплачен"></i>&nbsp;&nbsp;\';
                    if (raw.status == \'full\') icons = icons + \'<i class=\"fas fa-circle-check text-success fs-4\" title="Оплачен полностью"></i>&nbsp;&nbsp;\';
                    if (raw.status == \'denied\') icons = icons + \'<i class=\"far fa-xmark text-danger fs-3\" title="Отклонён плательщиком"></i>&nbsp;&nbsp;\';
//                    else  icons = icons + \'<i class=\"far fa-file-download text-danger fs-4\" title="Imported"></i>&nbsp;&nbsp;\';
                    return icons + data;
                } else return ""; }'."\n";

                $js_text .= '},'."\n";
                $fieldlist .= ', DATE_FORMAT(`'.$v.'`, \'<sub>%Y%m%d%H%i%s</sub>%d.%m.%Y\') AS `'.$v.'`';
            break;
            case 'datetime':
                $js_text .= '{ "data": "'.$v.'",'."\n";
                $js_text .= '  "class": "dt-nowrap text-end",'."\n";
/**
                $js_text .= '"render": function(data, type, raw) { if (data) { var icons = \'\';
                    if (raw.is_archived == \'1\') icons = icons + \'<i class=\"far fa-archive text-secondary fs-4\" title="Archived"></i>&nbsp;&nbsp;\';
                    if (raw.source_create == \'self\') icons = icons + \'<i class=\"far fa-hand-paper text-info fs-4\" title="Hand made"></i>&nbsp;&nbsp;\';
                    else  icons = icons + \'<i class=\"far fa-file-download text-danger fs-4\" title="Imported"></i>&nbsp;&nbsp;\';
                    return icons + data;
                } else return ""; }'."\n";
/**/
                $js_text .= '"render": function(data, type, raw) { if (data) { var icons = \'\'; var color = \'\';
                    //if (raw.is_archived == \'1\') icons = icons + \'<i class=\"far fa-archive text-secondary fs-4\" title="Archived"></i>&nbsp;&nbsp;\';
                    if (raw.expired != \'0\') color = \'danger\'; else color = \'info\';
                    if (raw.status == \'draft\') icons = icons + \'<i class=\"far fa-file-lines text-success fs-4\" title="Draft"></i>&nbsp;&nbsp;\';
                    if (raw.status == \'actual\') icons = icons + \'<i class=\"far fa-circle-dollar text-\' + color + \' fs-4\" title="К оплате"></i>&nbsp;&nbsp;\';
                    if (raw.status == \'sended\') icons = icons + \'<i class=\"far fa-envelope text-info fs-4\" title="Выслан плательщику"></i>&nbsp;<i class=\"far fa-circle-dollar text-\' + color + \' fs-4\" title="К оплате"></i>&nbsp;&nbsp;\';
                    if (raw.status == \'part\') icons = icons + \'<i class=\"far fa-circle-half-stroke text-info fs-4\" title="Частично оплачен"></i>&nbsp;&nbsp;\';
                    if (raw.status == \'full\') icons = icons + \'<i class=\"fas fa-circle-check text-success fs-4\" title="Оплачен полностью"></i>&nbsp;&nbsp;\';
                    if (raw.status == \'denied\') icons = icons + \'<i class=\"far fa-xmark text-danger fs-3\" title="Отклонён плательщиком"></i>&nbsp;&nbsp;\';
//                    else  icons = icons + \'<i class=\"far fa-file-download text-danger fs-4\" title="Imported"></i>&nbsp;&nbsp;\';
                    if (raw.source_create == \'self\') icons = icons + \'<i class=\"far fa-hand-paper text-secondary fs-4\" title="Hand made"></i>&nbsp;&nbsp;\';
                    else  icons = icons + \'<i class=\"far fa-file-download text-secondary fs-4\" title="Imported"></i>&nbsp;&nbsp;\';
                    return icons + data;
                } else return ""; }'."\n";

                $js_text .= '},'."\n";
                $fieldlist .= ', DATE_FORMAT(`'.$v.'`, \'<sub>%Y%m%d%H%i%s</sub>%d.%m.%Y&nbsp;%H:%i\') AS `'.$v.'`';
            break;
            case 'icon':
                $js_text .= '{ "data": "'.$v.'",'."\n";
                $js_text .= '  "class": "text-center",'."\n";
                $js_text .= '"render": function(data, type, raw) { if (data) return "<i class=\""+data+" mt-2 fa-xl\"></i>"; else return ""; }'."\n";
                $js_text .= '},'."\n";
                $fieldlist .= ', `'.$v.'`';
            break;
/**
            case 'json_lang_title':
                foreach (Yii::$app->params['languageSet'] as $lk => $lv) {
                    $js_text .= '{ "data": "'.$v.'['.$lk.']",'."\n";
                    $js_text .= '"render": function(data, type, raw) { return JSON.parse(raw["'.$v.'"]).'.$lk.' }'."\n";
                    $js_text .= '},'."\n"; $shift++;
                }
                $fieldlist .= ', `'.$v.'`'; $shift--;
            break;
/**/
            case 'json_lang_title':
                $js_text .= '{ "data": "'.$v.'",'."\n";
                $js_text .= '"render": function(data, type, raw) { r = ""; s = ($("#search_value_G").val() != "") ? $("#search_value_G").val() : $("#search_value_'.($k+1).'").val();'."\n";
                foreach (Yii::$app->params['languageSet'] as $lk => $lv) {
                    $js_text .= 'if (showset.'.$lk.') { r = r + "<div class=\"flag20 flag_'.$lk.'\"></div>" + str_highlight_text((JSON.parse(data).'.$lk.'), s) + "<br>"; }'."\n" ;
//                    $js_text .= 'if (showset.'.$lk.') { var regEx = new RegExp(s, "ig"); r = r + "<img src=\"/cabinet/web/flags_iso_24/'.$lv['ansi2'].'.png\" style=\"width:20px; margin: 0 6px 0px 0; opacity:0.4\" valign=\"bottom\">" + (JSON.parse(data).'.$lk.').replace( s, "<span style=\"background-color:#ffa\">" + s + "</span>" ) + "<br>"; }'."\n" ;
//                    $js_text .= 'if (showset.'.$lk.') r = r + "<img src=\"/cabinet/web/flags_iso_24/'.$lv['ansi2'].'.png\" style=\"width:20px; margin: 0 6px 0px 0; opacity:0.4\" valign=\"bottom\">" + (JSON.parse(data).'.$lk.') + "<br>";'."\n" ; // <hr class=\"mt-0 mb-0\">
                }
/**/
                $js_text .= ' return r; }'."\n";
                $js_text .= '},'."\n";// $shift++;
                $fieldlist .= ', `'.$v.'`';// $shift--;
                if ($showsetlist == '') $showsetlist .= '`'.$v.'`'; else $showsetlist .= ',`'.$v.'`';
            break;

            case 'boolean':
                $js_text .= '{ "data": "'.$v.'",'."\n";
                $js_text .= '  "class": "text-center",'."\n";
                $js_text .= '"render": function(data, type, raw) { return (data == 1 ? "<i class=\" fas fa-check text-success\"></i>" : "<i class=\"fas fa-minus text-muted\"></i>"); }'."\n";
                $js_text .= '},'."\n";
                $fieldlist .= ', `'.$v.'`';
            break;
            case 'file':    //   far fa-eye-slash   fas fa-expand   fe-maximize     mdi-select
                $img_path = $cdn_path.'images/' . $table->SysTableName. '/';
                $js_text .= '{ "data": "'.$v.'",'."\n";
                $js_text .= '  "class": "text-center",'."\n";
                $js_text .= '"render": function(data, type, raw) { if (data === null) return "<h1><i class=\"fas fa-image text-muted\"></i></h1>"; else return "<img src=\"'.$img_path.'"+data+"\" style=\"max-height: 60px; max-width: 150px;\">"; }'."\n";  //  mdi mdi-select text-muted
                $js_text .= '},'."\n";
                $fieldlist .= ', `'.$v.'`';
           break;
        }
    }
    $fieldlist .= ', REPLACE(REPLACE(FORMAT(`amount`, 2,\'de_DE\'), \'.\', \'&nbsp;\'), \',\' ,\'.\') AS `amount`, `buyer_email`, `inner_hash`, DATE_FORMAT(`issue_date`, \'%d.%m.%Y\') AS `issue_date`, DATE_FORMAT(`due_on`, \'%d.%m.%Y\') AS `due_on`, source_create, is_archived';

    $js_text .= '
        ],
        "ajax": {
            "url": "/ajax/table",
            "type": "POST",
            "data": function ( d ) {
                d.tablename = "'.$table->SysTableName.'";
                d.parent = "'.$parent.'";
                d.keyname = "'.$key_name.'";
                d.fieldlist = "'.$fieldlist.'";
                d.showsetlist = "'.$showsetlist.'";
                d.showset = showset;
                d.filterset = filterset;
                d.client_id = "'.$context['client']->client_id.'";
                d.business_token = "'.$business.'";
                d.lang = "'.Yii::$app->language.'";
            }
        },

//  Localisation   =============================
    language: {
        processing:     "'.Terms::translate('processing', 'cabinet').'...",
//        search:         "_INPUT_<i class=\"fe-search ms-1 me-1 cursor-pointer text-primary lead\"></i>",
        search:         "_INPUT_ <i class=\"fe-refresh-ccw ms-1 me-1 cursor-pointer lead text-danger\" id=\"reset_global\" title=\"Сбрость поиск и обновить таблицу\"></i>",
        searchPlaceholder: "'.Terms::translate('search_everywhere', 'cabinet').'...",
        lengthMenu:     "'.Terms::translate('show', 'cabinet').'&nbsp; _MENU_ &nbsp;'.Terms::translate('lines', 'cabinet').'",
        info:           "'.Terms::translate('records_from', 'cabinet').' _START_ '.Terms::translate('records_to', 'cabinet').' _END_ ('.Terms::translate('records_all', 'cabinet').': _TOTAL_)",
        infoEmpty:      "'.Terms::translate('records_from', 'cabinet').' 0 '.Terms::translate('records_to', 'cabinet').' 0 из 0 записей",
        infoFiltered:   "('.Terms::translate('records_total', 'cabinet').' _MAX_)", //  записей
        infoPostFix:    "",
        loadingRecords: "'.Terms::translate('loading', 'cabinet').'...",
        zeroRecords:    "<span class=\"text-danger\">'.Terms::translate('no_records', 'cabinet').'</span>",
        emptyTable:     "'.Terms::translate('no_data', 'cabinet').'",
        paginate: {
            first:      "<i class=\"fas fa-angle-double-left\"></i>",
            previous:   "<i class=\"fas fa-angle-left\"></i>",
            next:       "<i class=\"fas fa-angle-right\"></i>",
            last:       "<i class=\"fas fa-angle-double-right\"></i>",
        },
    },

//  Apply the search   =============================
    initComplete: function () {
        this.api().columns().every ( function (key) {
            var that = this;
            $( \'input.search_value\', this.footer() ).on( \'keyup change clear\', function (event) {
                if ( (event.key === "Enter") || (this.value == "") ) {

                    if ( this.attributes["data-key"].value == key && that.search() !== this.value )
                    {
//alert(this.value);
                        that.search( this.value ).draw();
                    }
                    if ( this.attributes["data-key"].value == "G" && key == 0 && that.search() !== this.value )
                    {
                        that.search( this.value ).draw();
                    }
                }
            } );
        } );
    }

} );

/*
$(\'#example thead th.searchable\').each( function (key) {
    $(this).html(\'\
        \'+(key+1)+\'<input class="text-secondary rounded search_value" id="search_value_\' + (key + 1) + \'" data-key="\' + (key + 1) + \'"\
        type="text" placeholder="поиск..." style="padding-right:25px; background-color: #ffe" />\
        <i class="ti-close me-0 cursor-pointer text-danger" style="margin: 3px 0 0 -20px"\
        onclick="$(\\\'#search_value_\' + (key + 1) + \'\\\').val(\\\'\\\').trigger(\\\'keyup\\\').focus()"></i>\' );
} );
*/

$(\'#example thead th.searchline\').each( function (key) {
    if ($(this).data("key") > 0) {
        $(this).html(\'\
            <input class="text-secondary rounded search_value" id="search_value_\' + ($(this).data("key")) + \'" data-key="\' + ($(this).data("key")) + \'"\
            type="text" placeholder="&nbsp;'.Terms::translate('search_column', 'cabinet').'..." style="padding-right:25px; background-color: #ffe" />\
            <i class="ti-close me-0 cursor-pointer text-danger" style="margin: 4px 0 0 -22px"\
            onclick="$(\\\'#search_value_\' + ($(this).data("key")) + \'\\\').val(\\\'\\\').trigger(\\\'change\\\').focus()"></i>\' );
    } else if ($(this).data("key") == "G") {
        $(this).html(\'\
            <input class="text-secondary rounded search_value" id="search_value_\' + ($(this).data("key")) + \'" data-key="\' + ($(this).data("key")) + \'"\
            type="text" placeholder="&nbsp;'.Terms::translate('search_everywhere', 'cabinet').'..." style="padding-right:25px; background-color: #fee" />\
            <i class="ti-close me-0 cursor-pointer text-danger" style="margin: 4px 0 0 -22px" id=\"reset\" title=\"Сбрость всё и обновить таблицу\"\
></i>\' );
    }
} );

$(\'#reset\').click( function (e) {
    e.preventDefault();
    $(".search_value").val("").trigger("change");
} );

$(\'#reset_global\').click( function (e) {
    e.preventDefault();
    table.search("").draw();
} );


} );

';


    $this->registerJs($js_text, 3);



//    var_dump(Yii::$app->params['languageSet']);
?>

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
	</div>
<?/**/?>

<div class="card">
	<div class="card-body">
<?// echo "<pre>"; var_dump($selectors); echo "</pre>"; ?>

		<div class="row">

			<div class="row col-xxl- col-xl-6 col-lg-6 col-md-6 col-sm-5 col-xs-6 mb-1">
<?/**?>
				<div class="col-auto mb-1">
					<button type="button" id="showset_ro" class="col-auto me-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" style="padding: 0.3rem 0.2rem 0.3rem 0.25rem" onclick="showset.ro = !showset.ro; checkCookieValues(); $('#example').DataTable().ajax.reload();"><img src="/cabinet/web/flags_iso_24/RO.png"></button>
					<button type="button" id="showset_ru" class="col-auto me-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" style="padding: 0.3rem 0.2rem 0.3rem 0.25rem" onclick="showset.ru = !showset.ru; checkCookieValues(); $('#example').DataTable().ajax.reload();"><img src="/cabinet/web/flags_iso_24/RU.png"></button>
					<button type="button" id="showset_en" class="col-auto me-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" style="padding: 0.3rem 0.2rem 0.3rem 0.25rem" onclick="showset.en = !showset.en; checkCookieValues(); $('#example').DataTable().ajax.reload();"><img src="/cabinet/web/flags_iso_24/GB.png"></button>
				</div>
<?/**/?>
    			<div class="col-auto mb-1">
    				<a href="<?= $business ?>/outgoing/new">
    					<button type="button" class="btn btn-info text-nowrap rounded-pill waves-effect waves-light">
    					<?= Terms::translate('add_invoice', 'cabinet') ?><?//= Yii::t('apl', 'create_' . $table->SysTableName) ?><span class="btn-label-right">&nbsp;&nbsp;<i class="fa fa-plus"></i></span>
    					</button>
    				</a>
    			</div>
<?/**/?>
			</div>


<?/**?>
			<div class="row btn-group d-flex justify-content-start col-xl-3 col-lg-3 col-md-4 col-sm-5 col-xs-6 mb-1 ms-3">
					<button type="button" class="col-auto me-0 mb-1 btn btn-outline-primary text-nowrap waves-effect waves-light active"><img src="/cabinet/web/flags_iso_24/RO.png"></button>
					<button type="button" class="col-auto me-0 mb-1 btn btn-outline-primary text-nowrap waves-effect waves-light active"><img src="/cabinet/web/flags_iso_24/RU.png"></button>
					<button type="button" class="col-auto me-0 mb-1 btn btn-outline-primary text-nowrap waves-effect waves-light"><img src="/cabinet/web/flags_iso_24/GB.png"></button>
			</div>
<?/**/?>
			<div class="col-xl-6 col-lg-7 col-md-6 col-sm-5 col-xs-4 mb-1 justify-content-end">
				<div class="d-flex justify-content-end row">
					<button type="button" id="filterset_all"      class="col-auto ms-1 mb-1 btn btn-outline-success btn-sm text-nowrap waves-effect waves-light" onclick="checkCookieValuesF('all'); $('#example').DataTable().ajax.reload();"><?= Terms::translate('filter_all', 'cabinet') ?></button> &nbsp;
					<button type="button" id="filterset_actual"   class="col-auto ms-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" onclick="checkCookieValuesF('actual'); $('#example').DataTable().ajax.reload();"><?= Terms::translate('filter_actual', 'cabinet') ?></button> &nbsp;
					<button type="button" id="filterset_deadline" class="col-auto ms-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" onclick="checkCookieValuesF('deadline'); $('#example').DataTable().ajax.reload();"><?= Terms::translate('filter_expires', 'cabinet') ?></button> &nbsp;
					<button type="button" id="filterset_expired"  class="col-auto ms-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" onclick="checkCookieValuesF('expired'); $('#example').DataTable().ajax.reload();"><?= Terms::translate('filter_overdue', 'cabinet') ?></button> &nbsp;
					<button type="button" id="filterset_rejected" class="col-auto ms-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" onclick="checkCookieValuesF('rejected'); $('#example').DataTable().ajax.reload();"><?= Terms::translate('filter_canceled', 'cabinet') ?></button> &nbsp;
					<button type="button" id="filterset_archived" class="col-auto ms-1 mb-1 btn btn-outline-secondary btn-sm text-nowrap waves-effect" onclick="checkCookieValuesF('archived'); $('#example').DataTable().ajax.reload();"><?= Terms::translate('filter_archive', 'cabinet') ?></button>
<?/**?>					<button type="button" id="filterset_archived" class="col-auto ms-1 mb-1 btn btn-outline-secondary btn-sm text-nowrap waves-effect actual ms-3" onclick="retur(false); checkCookieValuesF('archived'); $('#example').DataTable().ajax.reload();">Recurring<?//= Terms::translate('filter_archive', 'cabinet') ?></button><?/**/?>
				</div>
			</div>
<?/**/?>

		</div>

		<div class="row gy-0">
			<div class="col-lg-12 col-md-12">
				<div class="container-fluid gx-0">
					<table id="example"	class="table table-sm table-hover table-bordered dt-responsive"><!-- nowrap table-striped -->
						<thead>
<?/**?>
							<tr>
								<th class="searchable searchline border-0 border-bottom text-start" data-orderable="false" data-key="G" style="padding: 0 4px 4px 0"></th>  <!-- <i class="fe-search ms-1 me-1 cursor-pointer lead text-primary"></i> -->
<? $kk = 0; foreach ($rows as $k => $v) { if ($columns[$v]->FieldVisible) $kk++; ?>
								<th class="<?= $columns[$v]->FieldSearchable ? 'searchable ' : '' ?>searchline border-0 border-bottom" data-key="<?= ($columns[$v]->FieldSearchable) ? $kk : 0 ?>" style="padding: 0 4px 4px 0;"><?//= ($columns[$v]->FieldSearchable) ? $kk : 0 ?></th>
<? } ?>
							</tr>
<?/**/?>
							<tr>
								<th data-orderable="false">
									<i class="fa fa-fw fa-retweet text-secondary" style="cursor:pointer" onclick="$('#example').DataTable().ajax.reload();" title="Обновить данные"></i>
								</th>
<? /*foreach ($rows as $k => $v) { ?>
							<th><?= Yii::t('apl', 'table_' . $columns[$v]['FieldName']) ?></th>
<? } */

foreach ($rows as $k => $v) { if ($columns[$v]->FieldVisible) {
//echo "<pre>"; var_dump($columns[$v]['FieldName']);
    switch ($columns[$v]->FieldType)
    {
        default:
//        case 'varchar':
?>
<th data-orderable="<?= $columns[$v]->FieldSortable ? 'true' : 'false' ?>"><?= Terms::translate($columns[$v]['FieldName'], 'tables') ?><?//= (isset($selectors[$columns[$v]->FieldName]) && count($selectors[$columns[$v]->FieldName]) > 0 ) ? 'selector' : $columns[$v]->FieldType ?></th>
<?
        break;
        case "int":
    //        case 'varchar':
    ?>
<th data-orderable="<?= $columns[$v]->FieldSortable ? 'true' : 'false' ?>"><?= Terms::translate($columns[$v]['FieldName'], 'tables') ?> &nbsp;</th>
<?
        break;
        case 'boolean':
?>
<th data-orderable="<?= $columns[$v]->FieldSortable ? 'true' : 'false' ?>" class="text-center"><?= Terms::translate($columns[$v]['FieldName'], 'tables') ?></th>
<?
        break;
/*
        case 'json_lang_title':
            foreach (Yii::$app->params['languageSet'] as $lk => $lv) {
        ?>
<th nowrap><img src="/cabinet/web/flags_iso_24/<?= $lv['ansi2'] ?>.png" style="width:20px; margin: 0 6px 0px 0" valign="bottom"><?= Yii::t('apl', 'table_' . $columns[$v]['FieldName']) ?></th>
<?          }
        break;
*/
        case 'json_lang_title':
?>
<th data-orderable="<?= $columns[$v]->FieldSortable ? 'true' : 'false' ?>" nowrap><?= Terms::translate($columns[$v]['FieldName'], 'tables') ?></th>
<?
        break;
    }
/*
    if ($columns[$v]->FieldType == 'json_lang_title') { ?>
<th><img src="/cabinet/web/flags_iso_24/MD.png" style="width:20px; margin: 0 4px 4px 0"> <?= Yii::t('apl', 'table_' . $columns[$v]['FieldName']) ?></th>
<th><img src="/cabinet/web/flags_iso_24/RU.png" style="width:20px; margin: 0 4px 4px 0"> <?= Yii::t('apl', 'table_' . $columns[$v]['FieldName']) ?></th>
<th><img src="/cabinet/web/flags_iso_24/GB.png" style="width:20px; margin: 0 4px 4px 0"> <?= Yii::t('apl', 'table_' . $columns[$v]['FieldName']) ?></th>
<? } else { ?>
<th><?= Yii::t('apl', 'table_' . $columns[$v]['FieldName']) ?></th>
<? }
*/

} }






?>
<?/**?>
								<th>Раздел</th>
								<th>Псевдоним</th>
								<th><img src="/cabinet/web/flags_iso_24/MD.png" style="width:20px; margin: 0 4px 4px 0" align="bottom"> Термин / фраза</th>
<?/**?>
								<th><img src="/cabinet/web/flags_iso_24/RU.png" style="width:20px; margin: 0 4px 4px 0"> Термин / фраза</th>
								<th><img src="/cabinet/web/flags_iso_24/GB.png" style="width:20px; margin: 0 4px 4px 0"> Термин / фраза</th>
<?/**/?>
							</tr>
						</thead>
					</table>
				</div>

			</div>
		</div>
	</div>
</div>

<? } ?>


      <div class="modal fade" id="send-modal-old">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Small Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->



<!-- Send modal content -->
<div id="send-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">

                <form class="px-3" action="#">
					<div class="mb-3 mt-3 text-center fs-4">
						<b><?= Terms::translate('modal_send_2', 'popup') ?></b>
					</div>
					<div class="mb-3 mt-3 text-center" id="send_text">
					</div>
                    <div id="send_display_0" class="text-center" style="height:50px;">
                    	<i class="far fa-envelope text-primary" style="font-size:2rem"></i>
                    </div>
					<div id="send_display_1" class="mb-3 mt-3 text-center fs-2" style="height:50px; display:none;">
						<i class="fas fa-database text-primary" style="font-size:2rem"></i>
						<img src="data:image/gif;base64,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">
						<i class="far fa-envelope text-primary" style="font-size:2rem"></i>
					</div>
                    <div id="send_display_2" class="mb-3 mt-3 text-center fs-2" style="height:50px; display:none;">
                    	<i class="fa fa-envelope text-success" style="font-size:2rem"></i>
                    </div>
                    <div class="mb-3 text-center">
              			<button type="button" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-2" data-dismiss="modal" onclick="action_send_clear();">
							<span class="ms-1"><?= Terms::translate('close', 'invoice') ?></span><span class="btn-label-right"><i class="mdi mdi-close"></i></span>
						</button> &nbsp; &nbsp;
    					<button id="send_button" type="button" class="btn btn-primary text-nowrap rounded-pill waves-effect waves-light me-2" onclick="action_send();">
    					<?= Terms::translate('button_send', 'popup') ?><span class="btn-label-right"> &nbsp; <i class="far fa-envelope"></i></span>
    					</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Push modal content -->
<div id="push-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">

                <form class="px-3" action="#">
					<div class="mb-3 mt-3 text-center fs-4">
						<b><?= Terms::translate('modal_push', 'popup') ?></b>
					</div>
					<div class="mb-3 mt-3 text-center" id="push_text">
					</div>
                    <div id="push_display_0" class="mb-3 mt-3 text-center fs-2" style="height:50px;">
                    	<i class="fa-solid fa-arrow-right-to-bracket text-primary"></i>
                    </div>
					<div id="push_display_1" class="mb-3 mt-3 text-center fs-2" style="height:50px; display:none;">
						<i class="fas fa-database text-primary"></i>
						<img src="/@vendor/open-e-cont-md/yii2-backend-api/img/Fading arrows.gif">
						<i class="fa-solid fa-arrow-right-to-bracket text-primary"></i>
					</div>
                    <div id="push_display_2" class="mb-3 mt-3 text-center fs-2" style="height:50px; display:none;">
                    	<i class="fa-solid fa-arrow-right-to-bracket text-success"></i>
                    </div>
                    <div class="mb-3 text-center">
              			<button type="button" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-2" data-dismiss="modal" onclick="action_push_clear();">
							<span class="ms-1"><?= Terms::translate('close', 'invoice') ?></span><span class="btn-label-right"><i class="mdi mdi-close"></i></span>
						</button>
    					<button id="push_button" type="button" class="btn btn-primary text-nowrap rounded-pill waves-effect waves-light me-2" onclick="action_push();">
    					<?= Terms::translate('button_push', 'popup') ?><span class="btn-label-right"><i class="far fa-envelope"></i></span>
    					</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?/**?>
<!-- Sign modal content -->
<div id="sign-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">

                <form class="px-3" action="#">
					<div class="mb-3 mt-3 text-center fs-4">
						<b><?= Terms::translate('modal_sign_2', 'popup') ?></b>
					</div>
					<div class="mb-3 mt-3 text-center" id="sign_text">
					</div>
                    <div id="sign_display_0" class="mb-3 mt-3 text-center fs-2" style="height:50px;">
                    	<i class="far fa-user-pen text-primary"></i>
                    </div>
					<div id="sign_display_1" class="mb-3 mt-3 text-center fs-2" style="height:50px; display:none;">
						<i class="far fa-user-pen text-primary"></i>
						<img src="/@vendor/open-e-cont-md/yii2-backend-api/img/2-loader.gif" style="height:30px">

					</div>
                    <div id="sign_display_2" class="mb-3 mt-3 text-center fs-2" style="height:50px; display:none;">
                    	<i class="fa fa-user-pen text-success"></i>
                    </div>
                    <div class="mb-3 text-center">
              			<button type="button" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-2" data-bs-dismiss="modal" onclick="action_sign_clear();">
							<span class="ms-1"><?= Terms::translate('close', 'invoice') ?></span><span class="btn-label-right"><i class="mdi mdi-close"></i></span>
						</button>
    					<button id="sign_button" type="button" class="btn btn-primary text-nowrap rounded-pill waves-effect waves-light me-2" onclick="action_sign();">
    					<?= Terms::translate('button_sign', 'popup') ?><span class="btn-label-right"><i class="far fa-pen-clip"></i></span>
    					</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?/**/?>

<script type="text/javascript">

var send_key = '';
function action_send() {
	$("#send_button").prop("disabled", true);
	$("#send_display_0").css("display", "none");
	$("#send_display_1").css("display", "block");

    $.ajax({
        type: "GET",
//        url: '<?//= Yii::$app->params['api_url'] ?>facturare/v1/put/invoicenew_make',
        url: 'outgoing/send/' + send_key,
//        data: {'key': send_key},
        success: function ( data ) {
        	$("#send_button").prop("disabled", false);
        	$("#send_display_1").css("display", "none");
        	$("#send_display_2").css("display", "block");
        }
    });
}
function action_send_clear() {
	$("#send_button").prop("disabled", false);
	$("#send_display_0").css("display", "block");
	$("#send_display_1").css("display", "none");
	$("#send_display_2").css("display", "none");
}


var push_key = '';
function action_push() {
	$("#push_button").prop("disabled", true);
	$("#push_display_0").css("display", "none");
	$("#push_display_1").css("display", "block");

    $.ajax({
        type: "GET",
        url: '<?//= Yii::$app->params['api_url'] ?>facturare/v1/put/push',
        data: {'key': push_key},
        success: function ( data ) {
        	$("#push_button").prop("disabled", false);
        	$("#push_display_1").css("display", "none");
        	$("#push_display_2").css("display", "block");
        },
        error: function ( data ) {
        	//alert(var_dump(data));
        }
    });
}
function action_push_clear() {
	$("#push_button").prop("disabled", false);
	$("#push_display_0").css("display", "block");
	$("#push_display_1").css("display", "none");
	$("#push_display_2").css("display", "none");

	$("#reset_global").trigger("click");
//	$('#example').DataTable().ajax.reload();
}

var sign_key = '';
function action_sign() {
	$("#sign_button").prop("disabled", true);
	$("#sign_display_0").css("display", "none");
	$("#sign_display_1").css("display", "block");

	alert('<?//= Yii::$app->params['api_url'] ?>facturare/v1/sign/invoice');

//	window.location('https://msign.staging.egov.md/#/');
	//window.open('https://msign.staging.egov.md/#/sign/instrument/2705efbb-9502-464f-ae04-b06300d3e43f');

    $.ajax({
        type: "GET",
        url: '<?//= Yii::$app->params['api_url'] ?>facturare/v1/sign/invoice',
        data: {'key': sign_key},
        success: function ( data ) {
        	$("#sign_button").prop("disabled", false);
        	$("#sign_display_1").css("display", "none");
        	$("#sign_display_2").css("display", "block");
        }
    });

}
function action_sign_clear() {
	$("#sign_button").prop("disabled", false);
	$("#sign_display_0").css("display", "block");
	$("#sign_display_1").css("display", "none");
	$("#sign_display_2").css("display", "none");
	$('#example').DataTable().ajax.reload();
}

</script>

<?/**?>
<br>==================<br><pre><? var_dump($table->SysTableName, $key_name) ?></pre>
<br>==================<br><pre><? var_dump($rows) ?></pre>
<br>==================<br><pre><? var_dump($columns) ?></pre>
<?/**/?>
