
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
                    <li><?= $this->Html->link(__('New Transaction Type'), ['action' => 'add']) ?></li>
                    <li role="separator" class="divider"></li>                            <li><?= $this->Html->link(__('List Account Types'), ['controller' => 'AccountTypes', 'action' => 'index']) ?></li>
                            <li><?= $this->Html->link(__('New Account Type'), ['controller' => 'AccountTypes', 'action' => 'add']) ?></li>
                    <li role="separator" class="divider"></li>                            <li><?= $this->Html->link(__('List Commission Structures'), ['controller' => 'CommissionStructures', 'action' => 'index']) ?></li>
                            <li><?= $this->Html->link(__('New Commission Structure'), ['controller' => 'CommissionStructures', 'action' => 'add']) ?></li>
                    <li role="separator" class="divider"></li>                            <li><?= $this->Html->link(__('List Transaction Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?></li>
                            <li><?= $this->Html->link(__('New Transaction Type'), ['controller' => 'TransactionTypes', 'action' => 'add']) ?></li>
                    <li role="separator" class="divider"></li>                            <li><?= $this->Html->link(__('List Transactions'), ['controller' => 'Transactions', 'action' => 'index']) ?></li>
                            <li><?= $this->Html->link(__('New Transaction'), ['controller' => 'Transactions', 'action' => 'add']) ?></li>
                                      </ul>
                </div>
            </div>
            <div class="box-tools pull-right">
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> New Transaction Type'), array('action' => 'add',$accountTypeId), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                                <th><?= $this->Paginator->sort('id') ?></th>
                                <th><?= $this->Paginator->sort('from_account_id') ?></th>
                                <th><?= $this->Paginator->sort('to_account_id') ?></th>
                                <th><?= $this->Paginator->sort('name') ?></th>
                                <th><?= $this->Paginator->sort('created') ?></th>
                                <th><?= $this->Paginator->sort('modified') ?></th>
                                <th><?= $this->Paginator->sort('add_to_menu') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactionTypes as $transactionType): ?>
                    <tr>
                                <td><?= $this->Number->format($transactionType->id) ?></td>
                                <td><?= $transactionType->has('from_account') ? $this->Html->link($transactionType->from_account->name, ['controller' => 'AccountTypes', 'action' => 'view', $transactionType->from_account->id]) : '' ?></td>
                                <td><?= $transactionType->has('to_account') ? $this->Html->link($transactionType->to_account->name, ['controller' => 'AccountTypes', 'action' => 'view', $transactionType->to_account->id]) : '' ?></td>
                                <td><?= h($transactionType->name) ?></td>
                                <td><?= h($transactionType->created) ?></td>
                                <td><?= h($transactionType->modified) ?></td>
                                <td><?= h($transactionType->add_to_menu) ?></td>
                                <td class="actions">
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), ['action' => 'view', $transactionType->id,$accountTypeId],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i>'), ['action' => 'edit', $transactionType->id,$accountTypeId],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                            <?= $this->Form->postLink(__('<i class="glyphicon glyphicon-trash"></i>'), ['action' => 'delete', $transactionType->id,$accountTypeId], ['class' => 'btn btn-danger btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'delete', 'confirm' => __('Are you sure you want to delete transactionType with ID # {0}?', $transactionType->id)]) ?>
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



<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Transaction Types']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Transaction Types']); ?>