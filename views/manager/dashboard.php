<?php
use openecontmd\backend_api\models\Terms;

$this->title = Terms::translate('manager/dashboard', 'branch');

$this->params['breadcrumbs'] = [['label' => $this->title]];
?>

<?// echo "<pre>"; var_dump(Yii::$app->user->identity); echo "<pre>"; ?>

    <!-- Main content -->
    <section class="content">

<div class="container-fluid">

        <!-- Small boxes (Stat box) -->
        <div class="row">


          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h4><?= Yii::$app->user->identity->username ?></h4>
                <p><?= Yii::$app->user->identity->email ?></p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->




          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h4>Repository</h4>
                <p>Demo Repository</p>
              </div>
              <div class="icon">
                <i class="fas fa-database" style="font-size: 3.8rem"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h4>Repositore Key</h4>
                <p>3d4f8a840da878280ee31efb51778ec3</p>
              </div>
              <div class="icon">
                <i class="ion ion-key"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h4>65</h4>
                <p>Unique Visitors</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->


<?/**?>
<div class="col-md-6">
            <!-- TABLE: LATEST ORDERS -->
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Latest Orders</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Item</th>
                      <th>Status</th>
                      <th>Popularity</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR9842</a></td>
                      <td>Call of Duty IV</td>
                      <td><span class="badge badge-success">Shipped</span></td>
                      <td>
                        <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR1848</a></td>
                      <td>Samsung Smart TV</td>
                      <td><span class="badge badge-warning">Pending</span></td>
                      <td>
                        <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR7429</a></td>
                      <td>iPhone 6 Plus</td>
                      <td><span class="badge badge-danger">Delivered</span></td>
                      <td>
                        <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR7429</a></td>
                      <td>Samsung Smart TV</td>
                      <td><span class="badge badge-info">Processing</span></td>
                      <td>
                        <div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR1848</a></td>
                      <td>Samsung Smart TV</td>
                      <td><span class="badge badge-warning">Pending</span></td>
                      <td>
                        <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR7429</a></td>
                      <td>iPhone 6 Plus</td>
                      <td><span class="badge badge-danger">Delivered</span></td>
                      <td>
                        <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                      </td>
                    </tr>
                    <tr>
                      <td><a href="pages/examples/invoice.html">OR9842</a></td>
                      <td>Call of Duty IV</td>
                      <td><span class="badge badge-success">Shipped</span></td>
                      <td>
                        <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
</div>
<?/**/?>

		<div class="row">
			<!-- /.col -->
			<div class="col-6">


				<div class="card">
					<div class="card-header border-transparent">
						<p class="text-center">
							<strong>Goal Completion</strong>
						</p>

						<div class="progress-group">
							Add Products to Cart <span class="float-right"><b>160</b>/200</span>
							<div class="progress progress-sm">
								<div class="progress-bar bg-primary" style="width: 80%"></div>
							</div>
						</div>
						<!-- /.progress-group -->

						<div class="progress-group">
							Complete Purchase <span class="float-right"><b>310</b>/400</span>
							<div class="progress progress-sm">
								<div class="progress-bar bg-danger" style="width: 75%"></div>
							</div>
						</div>

						<!-- /.progress-group -->
						<div class="progress-group">
							<span class="progress-text">Visit Premium Page</span> <span
								class="float-right"><b>480</b>/800</span>
							<div class="progress progress-sm">
								<div class="progress-bar bg-success" style="width: 60%"></div>
							</div>
						</div>

						<!-- /.progress-group -->
						<div class="progress-group">
							Send Inquiries <span class="float-right"><b>250</b>/500</span>
							<div class="progress progress-sm">
								<div class="progress-bar bg-warning" style="width: 50%"></div>
							</div>
						</div>
						<!-- /.progress-group -->
					</div>
				</div>
			</div>

			<div class="col-6">
				<div class="card">
					<div class="card-header border-transparent">
						<p class="text-center">
							<strong>Goal Completion</strong>
						</p>

						<div class="progress-group">
							Add Products to Cart <span class="float-right"><b>160</b>/200</span>
							<div class="progress progress-sm">
								<div class="progress-bar bg-primary" style="width: 80%"></div>
							</div>
						</div>
						<!-- /.progress-group -->

						<div class="progress-group">
							Complete Purchase <span class="float-right"><b>310</b>/400</span>
							<div class="progress progress-sm">
								<div class="progress-bar bg-danger" style="width: 75%"></div>
							</div>
						</div>

						<!-- /.progress-group -->
						<div class="progress-group">
							<span class="progress-text">Visit Premium Page</span> <span
								class="float-right"><b>480</b>/800</span>
							<div class="progress progress-sm">
								<div class="progress-bar bg-success" style="width: 60%"></div>
							</div>
						</div>

						<!-- /.progress-group -->
						<div class="progress-group">
							Send Inquiries <span class="float-right"><b>250</b>/500</span>
							<div class="progress progress-sm">
								<div class="progress-bar bg-warning" style="width: 50%"></div>
							</div>
						</div>
						<!-- /.progress-group -->
					</div>
				</div>

			</div>
			<!-- /.col -->

		</div>

	</div>


</section>
