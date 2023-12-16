<?
    $ln = Yii::$app->language;
//    $rt = explode('/', Yii::$app->controller->request->url);
    $rt = Yii::$app->controller->request->url;

?><aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin's Cabinet</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->

<?/**?>
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>
<?/**/?>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->
<?// var_dump($rt); ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'DASHBOARD', 'url' => ['/'.$ln.'/dashboard'], 'icon' => 'th', 'active' => ($rt == '/'.$ln.'/dashboard')],
                    ['label' => 'MESSAGES', 'url' => ['/'.$ln.'/message'], 'icon' => 'envelope', 'badge' => '<span class="right badge badge-danger">3</span>', 'active' => ($rt == '/'.$ln.'/message')],

                    ['label' => 'ACCOUNT', 'url' => ['/'.$ln.'/customer'], 'icon' => 'user', 'active' => ($rt == '/'.$ln.'/customer')],
                    ['label' => 'MANAGERS', 'url' => ['/'.$ln.'/manager'], 'icon' => 'users', 'active' => ($rt == '/'.$ln.'/manager')],

                    ['label' => 'OUTGOING:', 'header' => true],
                    ['label' => 'CLIENTS', 'url' => ['/'.$ln.'/client'], 'icon' => 'shopping-cart', 'active' => ($rt == '/'.$ln.'/client')],
                    ['label' => 'INVOICES', 'url' => ['/'.$ln.'/outgoing'], 'icon' => 'file-export', 'active' => ($rt == '/'.$ln.'/outgoing')],

                    ['label' => 'INCOMING:', 'header' => true],
                    ['label' => 'VENDORS', 'url' => ['/'.$ln.'/vendor'], 'icon' => 'shopping-cart', 'active' => ($rt == '/'.$ln.'/vendor')],
                    ['label' => 'INCOMING', 'url' => ['/'.$ln.'/incoming'], 'icon' => 'file-import', 'badge' => '<span class="right badge badge-success">11</span>', 'active' => ($rt == '/'.$ln.'/incoming')],

                    ['label' => 'SETTINGS:', 'header' => true],
                    ['label' => 'REPOSITORY', 'url' => ['/'.$ln.'/repository'], 'icon' => 'file-code', 'active' => ($rt == '/'.$ln.'/repository')],
                    ['label' => 'USER RIGHTS', 'url' => ['/'.$ln.'/rights'], 'icon' => 'key', 'active' => ($rt == '/'.$ln.'/rights')],
                    ['label' => 'PARAMETERS', 'url' => ['/'.$ln.'/setting'], 'icon' => 'sliders-h', 'active' => ($rt == '/'.$ln.'/setting')],
/**
                     ['label' => ' ', 'header' => true],
                     ['label' => '---=== SAMPLE ===---', 'header' => true],
                     [
                     'label' => 'Starter Pages',
                     'icon' => 'tachometer-alt',
                     'badge' => '<span class="right badge badge-info">2</span>',
                     'items' => [
                     ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
                     ['label' => 'Inactive Page', 'iconStyle' => 'far'],
                     ]
                     ],

                     ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],

                     ['label' => 'Yii2 PROVIDED', 'header' => true],

                     ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                     ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                     ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
                     ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
                     ['label' => 'Level1'],
                     [
                     'label' => 'Level1',
                     'items' => [
                     ['label' => 'Level2', 'iconStyle' => 'far'],
                     [
                     'label' => 'Level2',
                     'iconStyle' => 'far',
                     'items' => [
                     ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                     ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                     ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
                     ]
                     ],
                     ['label' => 'Level2', 'iconStyle' => 'far']
                     ]
                     ],
                     ['label' => 'Level1'],
                     ['label' => 'LABELS', 'header' => true],
                     ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
                     ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
                     ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
/**/
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>