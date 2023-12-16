<?php
//use openecontmd\backend_api\models\SysLang;
use openecontmd\backend_api\models\Terms;

//$this->title = 'Starter Page';
$this->title = Terms::translate('client/*', 'branch');


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

//echo "<pre>"; var_dump($bundle); echo "</pre>";


/*
$this->registerJs('$(function () {
    $("#example").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo(\'#example1_wrapper .col-md-6:eq(0)\');
  });', 3);
*/

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
/**/
$.extend( true, $.fn.dataTable.defaults, {
    "searching": true,
    "ordering": true,
    "stateSave": true
} );
/**/

var showset = { "ro":true, "ru":true, "en":true };
var filterset = { "all":false, "actual":true, "deadline":false, "expired":false, "rejected":false, "archived":false };

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
             $js_text .=  "selectors['$k1']['$k2'] = '".htmlspecialchars($v2, ENT_QUOTES)."';\n";
         }
     }
     /**/
     $js_text .= '
$(document).ready(function() {

//$.cookie.json = true;
/*
if ($.cookie("showset") !== undefined) {   showset = $.cookie("showset"); }
checkCookieValues();
*/

//if ($.cookie("filterset") !== undefined) {   filterset = $.cookie("filterset"); }
checkCookieValuesF("");

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
        "order": [[3, "desc"]],

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
        "autoWidth":true,

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
                    var v = "V/S/E/C/D".split("/");   // + " / " + raw["id"];
                    var t = "";
                    v.forEach(function callback(currentValue, index, array)
                    {
                        switch(array[index])
                        {
//                            case "V": t = t + "<a href=\"" + data + "\" class=\"link-primary\"><i class=\"fe-eye ms-1 me-1 cursor-pointer\"></i></a>"; break;
//                            case "V": t = t + "<a href=\"invoice/view/" + raw.inner_hash + "\" class=\"link-primary\"><i class=\"fe-eye ms-1 me-1 cursor-pointer\" style=\"font-size:1.3em\"></i></a>"; break;
//                            case "S": t = t + "<a href=\"send//" + raw.customerID + "\" title=\"Edit\"><i class=\"mdi mdi-email-send-outline ms-1 me-1 text-success\" style=\"font-size:1.3em\"></i></a>"; break;
                            case "E": t = t + "<a href=\"client/edit/" + raw.unique_key + "\" title=\"'.Terms::translate('edit_customer', 'cabinet').'\">&nbsp;<i class=\"far fa-edit text-success\" style=\"font-size:1.3em\"></i></a>"; break;
//                            case "C": t = t + "<a href=\"actions/copy?tab='.$table->SysTableName.'&ids=" + data + "&item='.$parent.'\" title=\"Copy\">&nbsp;<i class=\"far fa-copy text-primary\" style=\"font-size:1.1em; margin: 0 5px;\"></i></a>"; break;
                            case "D": t = t + "<a href=\"client/delete/" + raw.unique_key + "\" data-id=\"" + data + "\" data-table=\"'.$table->SysTableName.'\" data-message=\"'.Terms::translate('delete_customer', 'cabinet').' \'"+ raw.caption + "\'?\" title=\"'.Terms::translate('delete_customer', 'cabinet').'\" class=\"delete_item\">&nbsp;<i class=\"fas fa-trash text-danger\" style=\"font-size:1.1em; margin: 0 5px;\"></i></a>"; break;
                        }
                    });
                    return t;
               }
            },';


     //$shift = 0;
     $fieldlist = '`'.$key_name.'`'; $showsetlist = '';
     foreach ($rows as $k => $v) {
         //        var_dump($columns[$v]->FieldName);

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
             case 'date':
                 $js_text .= '{ "data": "'.$v.'",'."\n";
                 $js_text .= '  "class": "text-end",'."\n";
                 $js_text .= '"render": function(data, type, raw) { if (data) return data; else return ""; }'."\n";
                 $js_text .= '},'."\n";
                 $fieldlist .= ', DATE_FORMAT(`'.$v.'`, \'<sub>%Y%m%d%H%i%s</sub>%d.%m.%Y\') AS `'.$v.'`';
                 break;
             case 'datetime':
                 $js_text .= '{ "data": "'.$v.'",'."\n";
                 $js_text .= '  "class": "text-end",'."\n";
                 $js_text .= '"render": function(data, type, raw) { if (data) return data; else return ""; }'."\n";
                 $js_text .= '},'."\n";
                 $fieldlist .= ', DATE_FORMAT(`'.$v.'`, \'<sub>%Y%m%d%H%i%s</sub>%d.%m.%Y %H:%i\') AS `'.$v.'`';
                 break;

             case 'icon':
                 $js_text .= '{ "data": "'.$v.'",'."\n";
                 $js_text .= '  "class": "text-center",'."\n";
                 $js_text .= '"render": function(data, type, raw) { if (data == \'1\') return "<i class=\"fas fa-user mt-2 fa-2xl fs-5 text-info\" title=\"'.(Terms::translate('persona_entrepreneur', 'cabinet')).'\"></i>"; else return "<i class=\"fas fa-landmark mt-2 fa-2xl fs-5 text-secondary\" title=\"'.(Terms::translate('persona_juridica', 'cabinet')).'\"></i>"; }'."\n";
                 $js_text .= '},'."\n";
                 $fieldlist .= ', IFNULL(`'.$v.'`, 0) AS `'.$v.'`';
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
     $fieldlist .= ', `unique_key`';

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
                d.business_token = "";
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

<style>
.input-group-text {
  padding: 0.2rem 0.9rem;
}
#example sub {
    display:none;
}
</style>

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

			<div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-xs-6 mb-1">
<?/**?>
				<div class="col-auto mb-1">
					<button type="button" id="showset_ro" class="col-auto me-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" style="padding: 0.3rem 0.2rem 0.3rem 0.25rem" onclick="showset.ro = !showset.ro; checkCookieValues(); $('#example').DataTable().ajax.reload();"><img src="/cabinet/web/flags_iso_24/RO.png"></button>
					<button type="button" id="showset_ru" class="col-auto me-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" style="padding: 0.3rem 0.2rem 0.3rem 0.25rem" onclick="showset.ru = !showset.ru; checkCookieValues(); $('#example').DataTable().ajax.reload();"><img src="/cabinet/web/flags_iso_24/RU.png"></button>
					<button type="button" id="showset_en" class="col-auto me-1 mb-1 btn btn-outline-primary btn-sm text-nowrap waves-effect waves-light" style="padding: 0.3rem 0.2rem 0.3rem 0.25rem" onclick="showset.en = !showset.en; checkCookieValues(); $('#example').DataTable().ajax.reload();"><img src="/cabinet/web/flags_iso_24/GB.png"></button>
				</div>
<?/**/?>
    			<div class="col-auto mb-1">
				<a href="client/new">
				<button type="button"
					class="btn btn-info text-nowrap rounded-pill waves-effect waves-light">
					<?= Terms::translate('add_customer', 'cabinet') ?><span class="btn-label-right">&nbsp;&nbsp;<i class="fa fa-plus"></i></span>
				</button>
				</a>
    			</div>

			</div>

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
//var_dump($columns[$v]['FieldName'], Yii::t('apl', 'table_' . $columns[$v]['FieldName']));

    switch ($columns[$v]->FieldType)
    {
        default:
//        case 'varchar':
?>
<th data-orderable="<?= $columns[$v]->FieldSortable ? 'true' : 'false' ?>"><?= Terms::translate($columns[$v]['FieldName'], 'tables') ?>&nbsp;&nbsp;<?//= (isset($selectors[$columns[$v]->FieldName]) && count($selectors[$columns[$v]->FieldName]) > 0 ) ? 'selector' : $columns[$v]->FieldType ?></th>
<?
        break;
        case "int":
    //        case 'varchar':
    ?>
<th data-orderable="<?= $columns[$v]->FieldSortable ? 'true' : 'false' ?>"><?= Terms::translate($columns[$v]['FieldName'], 'tables') ?>&nbsp;&nbsp;</th>
<?
        break;
        case 'boolean':
?>
<th data-orderable="<?= $columns[$v]->FieldSortable ? 'true' : 'false' ?>" class="text-center"><?= Terms::translate($columns[$v]['FieldName'], 'tables') ?>&nbsp;&nbsp;</th>
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
<th data-orderable="<?= $columns[$v]->FieldSortable ? 'true' : 'false' ?>" nowrap><?= Terms::translate($columns[$v]['FieldName'], 'tables') ?>&nbsp;&nbsp;</th>
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
<br>==================<br><pre><? var_dump($table->SysTableName, $key_name) ?></pre>
<br>==================<br><pre><? var_dump($rows) ?></pre>
<br>==================<br><pre><? var_dump($columns) ?></pre>
<?/**/?>
