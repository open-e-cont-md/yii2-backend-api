<?php
//    use yii\helpers\Html;
//    use yii\helpers\Url;
//    use yii\widgets\LinkPager;
//    use manager\models\User;
use openecontmd\backend_api\models\SysLang;
use openecontmd\backend_api\models\Terms;
use openecontmd\backend_api\models\Content;

$this->title = 'Starter Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];

/*   PLUGINS   */
//$bundlea = \hail812\adminlte3\assets\AdminLteAsset::register($this);
$bundle = \hail812\adminlte3\assets\PluginAsset::register($this);
$backend_bundle = \openecontmd\backend_api\assets\BackendAsset::register($this);

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

$backend_bundle->js[] = 'js/jquery.cookie.min.js';
$backend_bundle->css[] = 'css/font-awesome.min.css';


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
    $icons_set = 'V/E/D/P'; // /G/I
else
    $icons_set = 'V/E/D/P'; // /G

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
                            case "V": t = t + "<a href=\"incoming/view/" + raw.inner_hash + "\" class=\"link-primary\"><i class=\"far fa-eye cursor-pointer\" style=\"font-size:1.1em; margin: 0 5px;\" title=\"'.(Terms::translate('view', 'invoice')).'\"></i></a>"; break;

                            case "E":
                                if (raw.status == "draft")
                                    t = t + "<a href=\"edit/'.$business.'/" + raw.inner_hash + "\" title=\"'.(Terms::translate('edit', 'invoice')).'\"><i class=\"fe-edit ms-1 me-1 text-success\" style=\"font-size:1.3em\"></i></a>";
                                else
                                    t = t + "<i class=\"fe-edit ms-1 me-1 text-muted\" style=\"font-size:1.3em\" title=\"'.(Terms::translate('edit', 'invoice')).'\"></i>";
                            break;

                            case "C":
                                    t = t + "<a href=\"copy/'.$business.'/" + raw.inner_hash + "\" title=\"'.(Terms::translate('copy', 'invoice')).'\"><i class=\"far fa-copy ms-1 me-1 text-success\" style=\"font-size:1.3em\"></i></a>";
                            break;

//                            case "C": t = t + "<a href=\"actions/copy?tab='.$table->SysTableName.'&ids=" + data + "&item='.$parent.'\" title=\"Copy\"><i class=\"mdi mdi-content-copy ms-1 me-1 text-primary\" style=\"font-size:1.3em\"></i></a>"; break;
                            case "D":
                                if (raw.status == "draft")
                                    t = t + "<a href=\"delete/'.$business.'/" + raw.inner_hash + "\" data-id=\"" + data + "\" data-table=\"'.$table->SysTableName.'\" data-message=\"Delete\" title=\"'.(Terms::translate('delete', 'invoice')).'\" class=\"delete_item\"><i class=\"mdi mdi-trash-can-outline ms-1 me-1 text-danger\" style=\"font-size:1.4em\"></i></a>";
                                else
                                    t = t + "<i class=\"mdi mdi-trash-can-outline ms-1 me-1 text-muted\" style=\"font-size:1.4em\" title=\"'.(Terms::translate('delete', 'invoice')).'\"></i>";
                            break;
//                            case "S": t = t + "<a href=\"send/'.$business.'/" + raw.inner_hash + "\" title=\"Send\"><i class=\"mdi mdi-email-send-outline ms-1 me-1 text-success\" style=\"font-size:1.3em\"></i></a>"; break;

case "S":
if ( (raw.contact_email != "") && ( (raw.status == "actual") || (raw.status == "sended") ) )
t = t + "<a class=\"cursor-pointer\" data-id=\"" + raw.inner_hash + "\" data-bs-toggle=\"modal\" data-bs-target=\"#send-modal\"\
onclick=\"send_key = \'" + raw.inner_hash + "\'; $(\'#send_id\').val(\'" + raw.inner_hash + "\'); $(\'#send_name\').val(\'" + raw.outer_number + "\');\
$(\'#send_text\').html(\''.(Terms::translate('invoice', 'popup')).': <b>" + raw.outer_number + "</b> '.(Terms::translate('dated', 'popup')).' <b>" + raw.issue_date + "</b><br><b>" + raw.amount + " " + raw.currency + "</b>, '.(Terms::translate('up_to', 'popup')).' <b>" + raw.due_on + "</b><br><br>E-Mail: <b>" + raw.send_email + "</b>\');\">\
<i class=\"far fa-envelope ms-1 me-1 text-success\" style=\"font-size:1.2em\" title=\"'.(Terms::translate('send', 'invoice')).'\"></i></a>";
else
t = t + "<i class=\"far fa-envelope ms-1 me-1 text-secondary\" style=\"font-size:1.2em\" title=\"'.(Terms::translate('send', 'invoice')).'\"></i>";
break;

';

if (count($outs) > 0)
$js_text .= '
case "P": t = t + "<a class=\"cursor-pointer\" data-id=\"" + raw.inner_hash + "\" data-bs-toggle=\"modal\" data-bs-target=\"#push-modal\"\
onclick=\"push_key = \'" + raw.inner_hash + "\'; $(\'#push_id\').val(\'" + raw.inner_hash + "\'); $(\'#push_name\').val(\'" + raw.outer_number + "\');\
$(\'#push_text\').html(\''.(Terms::translate('invoice', 'popup')).': <b>" + raw.outer_number + "</b> '.(Terms::translate('upload', 'invoice')).' <b>" + raw.issue_date + "</b><br><b>" + raw.amount + " " + raw.currency + "</b><br><br><b>'.(Terms::translate('integrations', 'popup')).':</b><br>'.$outs_list.'\');\">\
<i class=\"fa-solid fa-cloud-arrow-down ms-1 me-1 text-success\" style=\"font-size:1.2em\" title=\"'.Terms::translate('modal_push', 'popup').'\"></i></a>"; break;
';
else
$js_text .= '
case "P": t = t + "<a class=\"cursor-pointer\"><i class=\"fa-solid fa-cloud-arrow-down ms-1 me-1 text-muted\" style=\"font-size:1.2em\" title=\"'.Terms::translate('modal_push', 'popup').'\"></i></a>"; break;
';

$js_text .= '
case "G": t = t + "<a href=\"'.Yii::$app->language.'/order/" + raw.inner_hash + "\" target=\"_blank\" class=\"link-primary\" title=\"Preview order page\"><i class=\"fa-solid fa-magnifying-glass ms-1 me-1 cursor-pointer\" style=\"font-size:1.1em\"></i></a>"; break;
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

			<div class="row col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-5 col-xs-6 mb-1">
<?/**?>
				<div class="col-auto mb-1">
					<button type="button" id="showset_ro" class="col-auto me-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" style="padding: 0.3rem 0.2rem 0.3rem 0.25rem" onclick="showset.ro = !showset.ro; checkCookieValues(); $('#example').DataTable().ajax.reload();"><img src="/cabinet/web/flags_iso_24/RO.png"></button>
					<button type="button" id="showset_ru" class="col-auto me-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" style="padding: 0.3rem 0.2rem 0.3rem 0.25rem" onclick="showset.ru = !showset.ru; checkCookieValues(); $('#example').DataTable().ajax.reload();"><img src="/cabinet/web/flags_iso_24/RU.png"></button>
					<button type="button" id="showset_en" class="col-auto me-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" style="padding: 0.3rem 0.2rem 0.3rem 0.25rem" onclick="showset.en = !showset.en; checkCookieValues(); $('#example').DataTable().ajax.reload();"><img src="/cabinet/web/flags_iso_24/GB.png"></button>
				</div>
<?/**?>
    			<div class="col-auto mb-1">
    				<a href="<?= $business ?>/new">
    					<button type="button" class="btn btn-primary text-nowrap rounded-pill waves-effect waves-light">
    					<?= Terms::translate('add_invoice', 'cabinet') ?><?//= Yii::t('apl', 'create_' . $table->SysTableName) ?><span class="btn-label-right"><i class="fa fa-plus"></i></span>
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

<?/**?>
<!-- Send modal content -->
<div id="send-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">

                <form class="px-3" action="#">
					<div class="mb-3 mt-3 text-center fs-4">
						<b><?= Terms::translate('modal_send_2', 'popup') ?></b>
					</div>
					<div class="mb-3 mt-3 text-center" id="send_text">
					</div>
                    <div id="send_display_0" class="mb-3 mt-3 text-center fs-2" style="height:50px;">
                    	<i class="far fa-envelope text-primary"></i>
                    </div>
					<div id="send_display_1" class="mb-3 mt-3 text-center fs-2" style="height:50px; display:none;">
						<i class="fas fa-database text-primary"></i>
						<img src="<?//= Yii::$app->params['self_url'] ?>cabinet/web/icons/Fading arrows.gif">
						<i class="far fa-envelope text-primary"></i>
					</div>
                    <div id="send_display_2" class="mb-3 mt-3 text-center fs-2" style="height:50px; display:none;">
                    	<i class="fa fa-envelope text-success"></i>
                    </div>
                    <div class="mb-3 text-center">
              			<button type="button" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-2" data-bs-dismiss="modal" onclick="action_send_clear();">
							<span class="ms-1"><?= Terms::translate('close', 'invoice') ?></span><span class="btn-label-right"><i class="mdi mdi-close"></i></span>
						</button>
    					<button id="send_button" type="button" class="btn btn-primary text-nowrap rounded-pill waves-effect waves-light me-2" onclick="action_send();">
    					<?= Terms::translate('button_send', 'popup') ?><span class="btn-label-right"><i class="far fa-envelope"></i></span>
    					</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Push modal content -->
<div id="push-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
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
						<img src="<?//= Yii::$app->params['self_url'] ?>cabinet/web/icons/Fading arrows.gif">
						<i class="fa-solid fa-arrow-right-to-bracket text-primary"></i>
					</div>
                    <div id="push_display_2" class="mb-3 mt-3 text-center fs-2" style="height:50px; display:none;">
                    	<i class="fa-solid fa-arrow-right-to-bracket text-success"></i>
                    </div>
                    <div class="mb-3 text-center">
              			<button type="button" name="cancel-button" class="btn btn-secondary rounded-pill waves-effect waves-light me-2" data-bs-dismiss="modal" onclick="action_push_clear();">
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
						<img src="img/2-loader.gif" style="height:30px">

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
        url: '<?//= Yii::$app->params['api_url'] ?>facturare/v1/pdf/sendinvoice',
        data: {'key': send_key},
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
