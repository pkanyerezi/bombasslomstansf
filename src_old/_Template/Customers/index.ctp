<div>
    <div class="box box-primary">
        <div class="box-header">
            <div class="box-tools pull-left">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Actions
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><?= $this->Html->link(__('New Customer'), ['action' => 'add']) ?></li>
                        <li role="separator" class="divider"></li>                            <li><?= $this->Html->link(__('List Branches'), ['controller' => 'Branches', 'action' => 'index']) ?></li>
                        <li><?= $this->Html->link(__('New Branch'), ['controller' => 'Branches', 'action' => 'add']) ?></li>
                    </ul>
                </div>
            </div>
            <div class="box-tools pull-right">
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> New Customer'), array('action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>
        </div>
        <div class="box-body table-responsive">
            <form class="search" method="GET">
                <input name="q" placeholder="search by name,email,identityNumber" class="form-control" type="text">
            </form><hr>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th><?= $this->Paginator->sort('branch_id') ?></th>
                        <th><?= $this->Paginator->sort('name') ?></th>
                        <th><?= $this->Paginator->sort('phone') ?></th>
                        <th><?= $this->Paginator->sort('email') ?></th>
                        <th><?= $this->Paginator->sort('identity') ?></th>
                        <th><?= $this->Paginator->sort('identity_type') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Transact
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <?php if($transactionTypes->count()):?>
                                        <?php foreach($transactionTypes as $key=>$transactionType):?>
                                            <li>
                                            <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Add ' . $transactionType), array('action' => 'add',$key,'controller'=>'Transactions','entity_id'=>$customer->id), array('escape' => false)); ?>
                                            </li>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </ul>
                            </div>
                        </td>
                        <td><?= $customer->has('branch') ? $this->Html->link($customer->branch->name, ['controller' => 'ranches', 'action' => 'view', $customer->branch->id]) : '' ?></td>
                        <td><a href="<?=$this->request->webroot?>customers/view/<?=$customer->id?>"><?= h($customer->name) ?></a></td>
                        <td><?= h($customer->phone) ?></td>
                        <td><?= h($customer->email) ?></td>
                        <td><?= h($customer->identity) ?></td>
                        <td><?= h($customer->identity_type) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), ['action' => 'view', $customer->id],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i>'), ['action' => 'edit', $customer->id],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                            <?= $this->Form->postLink(__('<i class="glyphicon glyphicon-trash"></i>'), ['action' => 'delete', $customer->id], ['class' => 'btn btn-danger btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'delete', 'confirm' => __('Are you sure you want to delete customer with ID # {0}?', $customer->id)]) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                </ul>
                <p><?= $this->Paginator->counter() ?></p>
            </div>
        </div>  
    </div>
</div>



<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Customers']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Customers']); ?>