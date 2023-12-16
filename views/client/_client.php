<?php
$this->title = 'Starter Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];

/*   PLUGINS   */
$bundlea = \hail812\adminlte3\assets\AdminLteAsset::register($this);
$bundle = \hail812\adminlte3\assets\PluginAsset::register($this);
//$bundle->css[] = 'toastr/toastr.min.css';
//$bundle->js[] = 'toastr/toastr.min.js';
//$this->registerJs('$(\'.toastrDefaultSuccess\').click(function() {toastr.success(\'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.\')});', 3);


$bundle->css[] = 'jsgrid/jsgrid.min.css';
$bundle->css[] = 'jsgrid/jsgrid-theme.min.css';

$bundle->css[] = 'datatables-bs4/css/dataTables.bootstrap4.min.css';
$bundle->css[] = 'datatables-responsive/css/responsive.bootstrap4.min.css';
$bundle->css[] = 'datatables-buttons/css/buttons.bootstrap4.min.css';

//$bundlea->css[] = 'css/adminlte.min.css';

//$bundle->js[] = 'datatables/jquery.dataTables.min.js';
//$bundle->js[] = 'datatables-bs4/js/dataTables.bootstrap4.min.js';
//$bundle->js[] = 'datatables-responsive/js/dataTables.responsive.min.js';
//$bundle->js[] = 'datatables-responsive/js/responsive.bootstrap4.min.js';

//$bundle->js[] = 'jsgrid/jsgrid.min.js';
//$bundle->js[] = 'jsgrid/demos/db.js';


$bundle->js[] = 'datatables/jquery.dataTables.min.js';
$bundle->js[] = 'datatables-bs4/js/dataTables.bootstrap4.min.js';
$bundle->js[] = 'datatables-responsive/js/dataTables.responsive.min.js';
$bundle->js[] = 'datatables-responsive/js/responsive.bootstrap4.min.js';
$bundle->js[] = 'datatables-buttons/js/dataTables.buttons.min.js';

$bundle->js[] = 'datatables-buttons/js/buttons.bootstrap4.min.js';
//$bundle->js[] = 'jszip/jszip.min.js';
//$bundle->js[] = 'pdfmake/pdfmake.min.js';
//$bundle->js[] = 'pdfmake/vfs_fonts.js';
$bundle->js[] = 'datatables-buttons/js/buttons.html5.min.js';
$bundle->js[] = 'datatables-buttons/js/buttons.print.min.js';
$bundle->js[] = 'datatables-buttons/js/buttons.colVis.min.js';

//echo "<pre>"; var_dump($bundle); echo "</pre>";


/*
$this->registerJs('$(function () {
    $("#example").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo(\'#example1_wrapper .col-md-6:eq(0)\');
  });', 3);
*/
$this->registerJs('$(document).ready(function() {
    var showset = { "ro":true, "ru":true, "en":true };
    var filterset = { "all":false, "actual":true, "deadline":false, "expired":false, "rejected":false, "archived":false };
    var table = $(\'#dt\').DataTable({
//  Main Settings
        "processing": true,
        "serverSide": true,
        "paging": true,
        "pageLength": 10,
        "paginate": 10,
        "columnDefs": [
            {
                "targets": [0],
                "orderable": false,
                "searchable": false
            },
        ],
        "ajax": {
            "url": "/ajax/test",
            "type": "POST",
            "data": function ( d ) {
                d.tablename = "ut_customer";
                d.parent = "352e3d14b65fcaf5dec771507334317a";
                d.keyname = "customerID";
                d.fieldlist = "action,first_name,last_name";
                d.showsetlist = "action,first_name,last_name";
                d.showset = showset;
                d.filterset = filterset;
                d.client_id = "230213927";
                d.business_token = "";
            }
        },
        "columns": [
            {"data": "customerID", "width": "15%"},
            {"data": "action", "width": "auto"},
            {"data": "first_name", "width": "auto"},
            {"data": "last_name", "width": "auto"},
            {"data": "position", "width": "auto"},
            {"data": "office", "width": "auto"},
            {"data": "start_date", "width": "auto"}
        ]
    });
});', 3);

?>




        <div class="card-body">
          <table id="dt" class="table-sm table-hover table-bordered dt-responsive" width="100%">
			<thead>
				<tr>
				<th class="searchable searchline text-start">customerID</th>
				<th>action</th>
				<th>first_name</th>
				<th>last_name</th>
				<th>position</th>
				<th>office</th>
				<th>start date</th>
				</tr>
			</thead>

          </table>
        </div>
