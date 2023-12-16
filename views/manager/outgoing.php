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
$bundle->css[] = 'datatables-buttons/css/buttons.bootstrap4.min.css';


//$bundlea->css[] = 'css/adminlte.min.css';

//$bundle->js[] = 'datatables/jquery.dataTables.min.js';
//$bundle->js[] = 'datatables-bs4/js/dataTables.bootstrap4.min.js';
//$bundle->js[] = 'datatables-responsive/js/dataTables.responsive.min.js';
//$bundle->js[] = 'datatables-responsive/js/responsive.bootstrap4.min.js';
$bundle->js[] = 'datatables-buttons/js/dataTables.buttons.min.js';
$bundle->js[] = 'jsgrid/jsgrid.min.js';
$bundle->js[] = 'jsgrid/demos/db.js';

//echo "<pre>"; var_dump($bundle); echo "</pre>";


/*
$this->registerJs('$(function () {
    $("#example").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo(\'#example1_wrapper .col-md-6:eq(0)\');
  });', 3);
*/
$this->registerJs('  $(function () {
    $("#jsGrid1").jsGrid({
        height: "500px",
        width: "100%",

        sorting: true,
        paging: true,

        data: db.clients,
        fields: [
            { name: "Name", type: "text", width: 150 },
            { name: "Age", type: "number", width: 50 },
            { name: "Address", type: "text", width: 200 },
            { name: "Country", type: "select", items: db.countries, valueField: "Id", textField: "Name" },
            { name: "Married", type: "checkbox", title: "Is Married" }
        ]
    });
  });', 3);

?>




        <div class="card-body">
          <div id="jsGrid1"></div>
        </div>
