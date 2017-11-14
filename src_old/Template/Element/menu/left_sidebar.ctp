<!-- Left side column. contains the logo and sidebar -->
<div class="main-sidebar left-side sidebar-offcanvas">                
    <!-- sidebar: style can be found in sidebar.less -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php // echo $this->Html->image('avatar04.png', array('class' => 'img-circle')); ?>
            </div>
            <div class="pull-left info">
                <p> <small><i class="glyphicon glyphicon-circle text-success"></i></small> Hello, 
                    <?php
                    $u = explode(' ',$authUser['name']);
                    echo $u[0];
                    ?>
                </p>
                <a href="<?=$this->request->webroot?>users/logout"><i class="fa fa-power-off"></i> logout</a>
            </div>
        </div>
        <!-- search form -->
        <!--  <form action="<?=$this->request->webroot?>transactions" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="search transactions"/>
                <span class="input-group-btn">
                    <button type='submit' id='search-btn' class="btn btn-flat"><i class="glyphicon glyphicon-search"></i></button>
                </span>
            </div>
        </form> -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

            <?php $maxMenuOuterLinks = isset($appSettings->max_menu_items)?$appSettings->max_menu_items:0;?>
            <?php if(count($menuTransactionTypes)):?>
                
                <?php $currentCurrency = '';?>
                <?php $currentCurrencyChanged = false;?>
                <?php $currentCurrencyItemCount = 0;?>
                
                <?php $treeOpen = false;?>
                <?php $subTreeOpen = false;?>

                <?php foreach ($menuTransactionTypes as $key=>$transactionType): ?>
                
                    <?php $currentCurrencyChanged = ($currentCurrency==$transactionType->currency_id)?false:true;?>
                    <?php $currentCurrency = $transactionType->currency_id;?>

                    <?php if($currentCurrencyChanged):?>

                        <?php // Close the last open SubTress if it exists?>
                        <?php if($subTreeOpen):?>
                            <?php $subTreeOpen = false;?>
                                </ul>
                            </li>
                        <?php endif;?>

                        <?php // Close the last open tree if it exists?>
                        <?php if($treeOpen):?>
                            <?php $treeOpen = false;?>
                                </ul>
                            </li>
                        <?php endif;?>

                        <li class="treeview">
                            <a href="#" title="Create <?=$currentCurrency;?> Transaction">
                                <i class="glyphicon glyphicon-plus-sign"></i>
                                <span><?=$currentCurrency;?></span>
                                <i class="glyphicon glyphicon-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                            <?php $currentCurrencyItemCount = 1;?>
                            <?php $treeOpen = true;?>
                    <?php else:?>
                            <?php $currentCurrencyItemCount++;?>
                    <?php endif;?>

                    <?php $from_acc = $transactionType->from_account->name ?>
                    <?php $to_acc = $transactionType->to_account->name ?>

                    <?php if($currentCurrencyItemCount == $maxMenuOuterLinks):?>
                        <?php $subTreeOpen = true;?>
                        <li class="treeview">
                            <a href="#">
                                <i class="glyphicon glyphicon-chevron-down"></i>
                                <span>more</span>
                                <i class="glyphicon glyphicon-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                    <?php endif;?>

                    <li>
                        <?= $this->Html->link(__('<i class="glyphicon glyphicon-arrow-right"></i><span>' . $transactionType->menu_link_title . '</span>'), ['action' => 'add', $transactionType->id,'controller'=>'Transactions'],['class' => '','escape'=>false,'data-toggle'=>'tooltip','title' => $from_acc . ' TO ' . $to_acc]) ?>
                    </li>

                    <?php if($subTreeOpen && $currentCurrencyChanged):?>
                            <?php $subTreeOpen = false;?>
                            </ul>
                        </li>
                    <?php endif;?>
                <?php endforeach; ?>

                <?php // Close the last open SubTress if it exists?>
                <?php if($subTreeOpen):?>
                        </ul>
                    </li>
                <?php endif;?>

                <?php // Close the last open tree if it exists?>
                <?php if($treeOpen):?>
                        </ul>
                    </li>
                <?php endif;?>
            <?php endif;?>


            <li class="treeview <?=(in_array($this->request->params['controller'], ['Transactions','TransactionStatuses','TransactionTypes','CommissionStructures'])?'active':'')?>">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Transations</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=(in_array($this->request->params['controller'], ['Transactions'])?'active':'')?>">
                        <?= $this->AclHtml->link(__('View all'), ['controller' => 'Transactions', 'action' => 'index']) ?>
                    </li>
                    <li class="<?=(in_array($this->request->params['controller'], ['TransactionStatuses'])?'active':'')?>">
                        <?= $this->AclHtml->link(__('States'), ['controller' => 'TransactionStatuses', 'action' => 'index']) ?>
                    </li>
                    <li  class="<?=(($this->request->params['controller']== 'TransactionTypes' && $this->request->params['action']=='index')?'active':'')?>">
                        <?= $this->AclHtml->link(__('All Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?>
                    </li>
                    <li  class="<?=(($this->request->params['controller']== 'TransactionTypes' && $this->request->params['action']=='indexCommission')?'active':'')?>">
                        <?= $this->AclHtml->link(__('Commission Types'), ['controller' => 'TransactionTypes', 'action' => 'index_commission'],['title'=>'Transaction Types with commission structures attached']) ?>
                    </li>
                    <li  class="<?=(($this->request->params['controller']== 'TransactionTypes' && $this->request->params['action']=='indexNonCommission')?'active':'')?>">
                        <?= $this->AclHtml->link(__('Non-Commission Types'), ['controller' => 'TransactionTypes', 'action' => 'index_non_commission'],['title'=>'Transaction Types without commission structures attached']) ?>
                    </li>
                    <li class="<?=(in_array($this->request->params['controller'], ['CommissionStructures'])?'active':'')?>">
                        <?= $this->AclHtml->link(__('Commission Structures'), ['controller' => 'CommissionStructures', 'action' => 'index']) ?>
                    </li>
                    <li class="<?=(($this->request->params['controller']== 'TransactionTypes' && $this->request->params['action']=='flow')?'active':'')?>">
                        <?= $this->AclHtml->link(__('Flow'), ['controller' => 'TransactionTypes', 'action' => 'flow']) ?>
                    </li>
                </ul>
            </li>

            <li class="treeview <?=((in_array($this->request->params['controller'], ['Accounts','AccountTypes']) && $this->request->params['action']!='accountBalance')?'active':'')?>">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Accounts</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <?php $menuAccountTypesCount = count($menuAccountTypes);?>
                    <?php $maxMenuOuterLinks = isset($appSettings->max_menu_items)?$appSettings->max_menu_items:0;?>
                    <?php if($menuAccountTypesCount):?>
                        <?php foreach ($menuAccountTypes as $key=>$accountType): ?>
                            <?php $itemNumber = $key+1;?>
                            <?php if($itemNumber > $maxMenuOuterLinks ):?>
                                <?php if($itemNumber == $maxMenuOuterLinks+1 ):?>
                                    <li class="treeview">
                                        <a href="#">
                                            <i class="glyphicon glyphicon-chevron-down"></i>
                                            <span>more</span>
                                            <i class="glyphicon glyphicon-angle-left pull-right"></i>
                                        </a>
                                        <ul class="treeview-menu">
                                <?php endif;?>
                            <?php endif;?>
                                    <li class="<?=(($this->request->params['controller']== 'Accounts' && !empty($_GET['account_type_id']) && $_GET['account_type_id']==$accountType->id)?'active':'')?>">
                                        <?= $this->Html->link(__('<i class="glyphicon glyphicon-arrow-right"></i><span>' . $accountType->name . '</span>'), ['action' => 'index', 'account_type_id'=>$accountType->id,'controller'=>'Accounts'],['class' => '','escape'=>false,'data-toggle'=>'tooltip','title' => $accountType->name]) ?>
                                    </li>
                            <?php if($itemNumber > $maxMenuOuterLinks ):?>
                                <?php if($itemNumber == $menuTransactionTypesCount ):?>
                                        </ul>
                                    </li>
                                <?php endif;?>
                            <?php endif;?>
                        <?php endforeach; ?>
                    <?php endif;?>
                    <li class="<?=(($this->request->params['controller']== 'AccountTypes')?'active':'')?>">
                        <?= $this->Html->link(__('<i class="fa fa-user"></i><span>View All</span>'), ['action' => 'index','controller'=>'Accounts'],['class' => '','escape'=>false,'data-toggle'=>'tooltip']) ?>
                    </li>
                    <li class="<?=(($this->request->params['controller']== 'AccountTypes')?'active':'')?>">
                        <?= $this->Html->link(__('<i class="fa fa-user"></i><span>Account Types</span>'), ['action' => 'index','controller'=>'AccountTypes'],['class' => '','escape'=>false,'data-toggle'=>'tooltip']) ?>
                    </li>
                </ul>
            </li>

            <li class="treeview <?=(in_array($this->request->params['controller'], ['Customers'])?'active':'')?>">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Customers</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=(($this->request->params['controller']== 'Customers' && $this->request->params['action']=='index')?'active':'')?>">
                        <?= $this->Html->link(__('List'), ['action' => 'index','controller'=>'Customers'],['escape'=>false]) ?>
                    </li>
                    <li class="<?=(($this->request->params['controller']== 'Customers' && $this->request->params['action']=='add')?'active':'')?>">
                        <?= $this->Html->link(__('Add'), ['action' => 'add','controller'=>'Customers'],['escape'=>false]) ?>
                    </li>
                </ul>
            </li>
            
            <li class="treeview <?=(in_array($this->request->params['controller'], ['Transactions','Accounts'])?'active':'')?>">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Reporting</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=(($this->request->params['controller']== 'Accounts' && $this->request->params['action']=='accountBalance')?'active':'')?>">
                        <?= $this->Html->link(__('<i class="fa fa-user"></i><span>Accounts Balance</span>'), ['action' => 'accountBalance','controller'=>'Accounts'],['class' => '','escape'=>false,'data-toggle'=>'tooltip']) ?>
                    </li>
                    <li class="<?=(($this->request->params['controller']== 'Transactions' && $this->request->params['action']=='statement')?'active':'')?>">
                        <?= $this->AclHtml->link(__('Transaction Statement'), ['controller' => 'Transactions', 'action' => 'statement']) ?>
                    </li>
                    <li class="<?=(($this->request->params['controller']== 'Transactions' && $this->request->params['action']=='balance')?'active':'')?>">
                        <?= $this->AclHtml->link(__('Transaction Balance'), ['controller' => 'Transactions', 'action' => 'balance']) ?>
                    </li>
                </ul>
            </li>

            <li class="treeview <?=(in_array($this->request->params['controller'], ['Currencies','Branches','Roles','AppSettings','Users','RolePermissions'])?'active':'')?>">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>App</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=(($this->request->params['controller']== 'Branches')?'active':'')?>">
                        <?= $this->Html->link(__('Branches'), ['action' => 'index','controller'=>'Branches']) ?>
                    </li>
                    <li class="<?=(($this->request->params['controller']== 'Currencies')?'active':'')?>">
                        <?= $this->Html->link(__('Currencies'), ['action' => 'index','controller'=>'Currencies']) ?>
                    </li>
                    <li class="<?=(($this->request->params['controller']== 'Users')?'active':'')?>">
                        <?= $this->Html->link(__('Admins'), ['action' => 'index','controller'=>'Users']) ?>
                    </li>
                    <li class="<?=(($this->request->params['controller']== 'Roles')?'active':'')?>">
                        <?= $this->Html->link(__('Roles'), ['action' => 'index','controller'=>'Roles']) ?>
                    </li>
                    <li class="<?=(($this->request->params['controller']== 'RolePermissions')?'active':'')?>">
                        <?= $this->Html->link(__('Role Permissions'), ['action' => 'viewPermisionsTable','controller'=>'RolePermissions']) ?>
                    </li>
                    <li class="<?=(($this->request->params['controller']== 'AppSettings')?'active':'')?>">
                        <?= $this->Html->link(__('App Settings'), ['action' => 'index','controller'=>'AppSettings']) ?>
                    </li>
                    <li><a href="<?=$this->request->webroot?>users/logout"><i class="fa fa-power-off"></i> logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- /.sidebar -->
</div>