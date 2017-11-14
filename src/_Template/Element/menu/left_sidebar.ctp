<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">                
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?= $this->Html->image('avatar04.png', array('class' => 'img-circle')); ?>
            </div>
            <div class="pull-left info">
                <p> <small><i class="fa fa-circle text-success"></i></small> Hello, 
                    <?php
                    $u = explode(' ',$authUser['name']);
                    echo $u[0];
                    ?>
                </p>
                <a href="<?=$this->request->webroot?>users/logout"><i class="fa fa-power-off"></i> logout</a>
            </div>
        </div>
        <!-- search form -->
        <form action="<?=$this->request->webroot?>transactions" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="search transactions"/>
                <span class="input-group-btn">
                    <button type='submit' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <form action="<?=$this->request->webroot?>customers" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="search customers"/>
                <span class="input-group-btn">
                    <button type='submit' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <?php $menuTransactionTypesCount = $menuTransactionTypes->count();?>
            <?php $maxMenuOuterLinks = 5;?>
            <?php if($menuTransactionTypesCount):?>
                <?php foreach ($menuTransactionTypes as $key=>$transactionType): ?>
                    <?php $from_acc = $transactionType->has('from_account') ? $transactionType->from_account->name : '' ?><?php $to_acc = $transactionType->has('to_account') ? $transactionType->to_account->name : '' ?>
                    <?php $itemNumber = $key+1;?>
                    <?php if($itemNumber > $maxMenuOuterLinks ):?>
                        <?php if($itemNumber == $maxMenuOuterLinks+1 ):?>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-cog"></i>
                                    <span>Others</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                        <?php endif;?>
                    <?php endif;?>
                                    <li>
                                        <?= $this->Html->link(__('<i class="glyphicon glyphicon-arrow-right"></i><span>' . $transactionType->menu_link_title . '</span>'), ['action' => 'add', $transactionType->id,'controller'=>'Transactions'],['class' => '','escape'=>false,'data-toggle'=>'tooltip','title' => $from_acc . ' TO ' . $to_acc]) ?>
                                    </li>
                    <?php if($itemNumber > $maxMenuOuterLinks ):?>
                        <?php if($itemNumber == $maxMenuOuterLinks+1 ):?>
                                </ul>
                            </li>
                        <?php endif;?>
                    <?php endif;?>
                <?php endforeach; ?>
            <?php endif;?>

            
            

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Transations</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <?= $this->Html->link(__('View all'), ['controller' => 'Transactions', 'action' => 'index']) ?>
                    </li>
                    <li>
                        <?= $this->Html->link(__('States'), ['controller' => 'TransactionStatuses', 'action' => 'index']) ?>
                    </li>
                    <li>
                        <?= $this->Html->link(__('Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?>
                    </li>
                    <li>
                        <?= $this->Html->link(__('Flow'), ['controller' => 'TransactionTypes', 'action' => 'flow']) ?>
                    </li>
                    <li>
                        <?= $this->Html->link(__('CommissionStructures'), ['controller' => 'CommissionStructures', 'action' => 'index']) ?>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Accounts</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <?php $menuAccountTypesCount = $menuAccountTypes->count();?>
                    <?php $maxMenuOuterLinks = 5;?>
                    <?php if($menuAccountTypesCount):?>
                        <?php foreach ($menuAccountTypes as $key=>$accountType): ?>
                            <?php $itemNumber = $key+1;?>
                            <?php if($itemNumber > $maxMenuOuterLinks ):?>
                                <?php if($itemNumber == $maxMenuOuterLinks+1 ):?>
                                    <li class="treeview">
                                        <a href="#">
                                            <i class="fa fa-cog"></i>
                                            <span>Others</span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </a>
                                        <ul class="treeview-menu">
                                <?php endif;?>
                            <?php endif;?>
                                    <li>
                                        <?= $this->Html->link(__('<i class="glyphicon glyphicon-arrow-right"></i><span>' . $accountType->name . '</span>'), ['action' => 'index', 'account_type_id'=>$accountType->id,'controller'=>'Accounts'],['class' => '','escape'=>false,'data-toggle'=>'tooltip','title' => $accountType->name]) ?>
                                    </li>
                            <?php if($itemNumber > $maxMenuOuterLinks ):?>
                                <?php if($itemNumber == $maxMenuOuterLinks+1 ):?>
                                        </ul>
                                    </li>
                                <?php endif;?>
                            <?php endif;?>
                        <?php endforeach; ?>
                    <?php endif;?>
                    <li><a href="<?=$this->request->webroot;?>account-types"><i class="fa fa-user"></i> Account Types</a></li>
                    <li><a href="<?=$this->request->webroot;?>accounts/account-balance"><i class="fa fa-user"></i> Account Balance</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Customers</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=$this->request->webroot;?>customers"><i class="fa fa-user"></i> List</a></li>
                    <li><a href="<?=$this->request->webroot;?>customers/add"><i class="fa fa-building"></i> Add</a></li>
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>App</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=$this->request->webroot;?>branches"><i class="fa fa-building"></i> Branches</a></li>
                    <li><a href="<?=$this->request->webroot;?>users"><i class="fa fa-user"></i> Admins</a></li>
                    <li><a href="<?=$this->request->webroot;?>roles"><i class="fa fa-user"></i> Roles</a></li>
                    <li><a href="<?=$this->request->webroot;?>app-settings"><i class="fa fa-cog"></i> App Settings</a></li>
                    <li><a href="<?=$this->request->webroot?>users/logout"><i class="fa fa-power-off"></i> logout</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>