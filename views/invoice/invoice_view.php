<?php
use yii\helpers\Html;
use openecontmd\backend_api\models\Terms;

//$this->title = 'Список инвойсов';
//$params['class'] = 'form-control';
//$params['autocomplete'] = 'off';

//$country_list = Yii::$app->db->createCommand("SELECT ANSI2 AS value, json_get(CountryCaption, '".Yii::$app->language."') AS caption FROM ut_country ORDER BY json_get(CountryCaption, '".Yii::$app->language."')")->queryAll();
//$country_list[-1] = array('value'=>null, 'caption'=>'---');
//ksort($country_list);

$this->registerJs('var buyers={};', 1);
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
-->
</style>

<?
//var_dump($items->Row); exit;
$link = json_decode($result['doc_url']);

$sb = isset($context['selected_business']) ? $context['selected_business'] : null;
$b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;

?>

<div class="container-fluid g-0"> <!-- container-fluid -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
					<a href="/<?= Yii::$app->language ?>/outgoing">
                		<button type="button" class="btn btn-info rounded-pill waves-effect waves-light me-2 mt-1 mb-1">
                    		&nbsp;&nbsp;<span class="btn-label"><i class="fa fa-angle-double-left"></i></span>&nbsp;&nbsp;<?= Terms::translate('go_to_list', 'button') ?>&nbsp;&nbsp;
                    	</button>
					</a>
                </div>
            </div>
		</div>
	</div>





        <div class="row">
          <div class="col-12">

            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-sm-4"><h4><?= Terms::translate('invoice', 'invoice') ?>: <?= $result['outer_number'] ?></h4></div>
                <div class="col-sm-4"><h4><?= Terms::translate('date', 'invoice') ?>: <?= date("d.m.Y", strtotime($result['issue_date'])) ?></h4></div>
                <div class="col-sm-4"><h4><?= Terms::translate('status', 'cabinet') ?>: <b><?= $status ?></b></h4></div>
                <!-- /.col -->
              </div>

              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  <address>
					<?= Terms::translate('beneficiar', 'invoice') ?>:
					<strong>*<?//= json_decode($b['caption'])->{Yii::$app->language} ?></strong><br>
					Email: <strong>*<?//= $b['contact_email'] ?></strong>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <address>
					<?= Terms::translate('payer', 'invoice') ?>:
					<?/**/?>
						<? if (isset($factura->SupplierInfo->Buyer->Title)) { ?><strong><?= $factura->SupplierInfo->Buyer->Title ?></strong><? } ?><?
						if (isset($factura->SupplierInfo->Buyer->Name)) { ?><strong>, <?= $factura->SupplierInfo->Buyer->Name ?></strong><? } ?>
					<?/**/?>
						<br>Email: <? if (isset($factura->SupplierInfo->Buyer->Email)) { ?><strong><?= $factura->SupplierInfo->Buyer->Email ?></strong><? } ?>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
					<?= Terms::translate('invoice', 'invoice') ?>: <b><?= $result['outer_number'] ?></b> <br>
					<?= Terms::translate('pay_before', 'invoice') ?>: <b><?= date("d.m.Y", strtotime($result['due_on'])) ?></b><br>
					<?/* if ( (isset($result['RESPONSIBLE_NAME']) && ($result['RESPONSIBLE_NAME'].'' != '')) ||
					    (isset($result['RESPONSIBLE_NAME']) && ($result['RESPONSIBLE_NAME'].'' != '')) ||
					    (isset($result['RESPONSIBLE_NAME']) && ($result['RESPONSIBLE_NAME'].'' != '')) ) { ?>
					<?= Terms::translate('responsible', 'invoice') ?>:<br>
					<? if (isset($result['RESPONSIBLE_NAME']) && ($result['RESPONSIBLE_NAME'].'' != '')) { ?><?= trim($result['RESPONSIBLE_NAME']) ?><? } ?><? if (isset($result['RESPONSIBLE_LAST_NAME']) && ($result['RESPONSIBLE_LAST_NAME'].'' != '')) { ?> <?= trim($result['RESPONSIBLE_LAST_NAME']) ?><? } ?><? if (isset($result['RESPONSIBLE_EMAIL']) && ($result['RESPONSIBLE_EMAIL'].'' != '')) { ?>, <?= trim($result['RESPONSIBLE_EMAIL']) ?><? } ?><? } */?>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
				<div class="col-xs-12 table">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th style="width: 59%"><?= Terms::translate('goodies', 'invoice') ?></th>
								<th style="text-align:right"><?= Terms::translate('price', 'invoice') ?></th>
								<th style="text-align:right"><?= Terms::translate('qnt', 'invoice') ?></th>
								<th style="text-align:right"><?= Terms::translate('subtotal', 'invoice') ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
        			<? foreach($items as $k => $v) { ?>
        				<tr>
								<td style="text-align: right;line-height:0.8;"><?= $k ?>. &nbsp; </td>
								<td style="text-align: left;line-height:0.8;"><?= $v->Name ?></td>
								<td style="text-align: right;line-height:0.8;"><?= isset($v->UnitPrice) ? number_format(str_replace(" ", "", floatval($v->UnitPrice)), 2, '.', '&nbsp;') : '-' ?></td>
								<td style="text-align: right;line-height:0.8;"><?= $v->Quantity ?> <?= $v->UnitOfMeasure ?></td>
								<td style="text-align: right;line-height:0.8;"><?= number_format(str_replace(" ", "", floatval($v->TotalPrice)), 2, '.', '&nbsp;') ?> </td>
								<td style="text-align: right;line-height:0.8;"><?= isset($result['currency']) ? $result['currency'] : '-' ?></td>
							</tr>
        			<? } ?>
                      </tbody>
					</table>
					<?/* if (isset($result['COMMENTS']) && (trim(str_replace(['<br>','<br/>','<br />'], '', $result['COMMENTS'])).'' != '')) { ?><h4><?= $result['COMMENTS'] ?></h4><? } */?>
					<?/* if (isset($result['USER_DESCRIPTION']) && (trim(str_replace(['<br>','<br/>','<br />'], '', $result['USER_DESCRIPTION'])).'' != '')) { ?><h4><?= $result['USER_DESCRIPTION'] ?></h4><? } */?>
				</div>
              </div>
              <!-- /.row -->

              <div class="row">
				<div class="col-md-6 col-xs-6 col-sm-6 fs-4">
					<? if (isset($result['remark']) && ($result['remark'].'' != '')) { ?><?= $result['remark'] ?><? } ?>
				</div>

				<div class="col-md-6 col-xs-12 col-sm-12">
					<p class="lead"><?= Terms::translate('to_pay', 'invoice') ?></p>
					<div class="table-responsive">
						<table class="table">
							<tbody>
								<tr>
									<th style="width: 70%"><?= Terms::translate('subtotal', 'invoice') ?>:</th>
									<td style="text-align: right"><?= number_format($result['amount'], 2, '.', '&nbsp;') ?>&nbsp;<?= $result['currency'] ?></td>
								</tr>
								<tr>
									<th><?= Terms::translate('tva_include', 'invoice') ?></th>
									<td style="text-align: right"><?= number_format($result['amount']/6.0, 2, '.', '&nbsp;') ?>&nbsp;<?= $result['currency'] ?></td>
								</tr>
								<tr>
									<th><?= Terms::translate('total_to_pay', 'invoice') ?>:</th>
									<td style="text-align: right"><b><?= number_format($result['amount'], 2, '.', '&nbsp;') ?>&nbsp;<?= $result['currency'] ?></b></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
              </div>
              <!-- /.row -->

            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->






    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
					<a href="/<?= Yii::$app->language ?>/outgoing">
                		<button type="button" class="btn btn-info rounded-pill waves-effect waves-light me-2 mt-1 mb-1">
                    		&nbsp;&nbsp;<span class="btn-label"><i class="fa fa-angle-double-left"></i></span>&nbsp;&nbsp;<?= Terms::translate('go_to_list', 'button') ?>&nbsp;&nbsp;
                    	</button>
					</a>
                </div>
            </div>
		</div>
	</div>

</div>

