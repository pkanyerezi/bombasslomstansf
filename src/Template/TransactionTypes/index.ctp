<div>
    <div class="box box-primary">
        <div class="box-header">
            <div class="box-tools pull-left">
                <div class="dropdown" style="display:inline;">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Actions
                    <span class="caret"></span>
                  </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li>
                            <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> New Transaction Type'), array('action' => 'add',$accountTypeId), array('class' => '', 'escape' => false)); ?>
                        </li>
                    </ul>
                </div>
                <form style="display:inline;">
                    <?php if(isset($appTransactionStatuses) && count($appTransactionStatuses)):?>
                        <?= $this->Form->select('branch_id',$branches,['class'=>'btn btn-warning','value'=>(!empty($_GET['branch_id']))?$_GET['branch_id']:$authUser['branch_id']]) ?>
                    <?php endif;?>
                    <button class="btn btn-warning" type="submit"><i class="glyphicon glyphicon-refresh"></i></button>
                </form>
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
                        <th><?= $this->Paginator->sort('currency_id') ?></th>
                        <th><?= $this->Paginator->sort('from_account_id') ?></th>
                        <th><?= $this->Paginator->sort('to_account_id') ?></th>
                        <th><?= $this->Paginator->sort('name') ?></th>
                        <th><?= $this->Paginator->sort('name_on_receipt') ?></th>
                        <th><?= $this->Paginator->sort('currency_id') ?></th>
                        <th><?= $this->Paginator->sort('add_to_menu') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $currentCurrency = null;?>
                    <?php $currentCurrencyChanged = false;?>
                    <?php foreach ($transactionTypes as $transactionType): ?>
                        <?php $currentCurrencyChanged = (!empty($currentCurrency) && $currentCurrency!=$transactionType->currency_id)?true:false;?>
                        <?php $currentCurrency = $transactionType->currency_id;?>

                    <?php if($currentCurrencyChanged):?>
                        <tr><td colspan="7" style="height: 100px;">&nbsp;</td></tr>
                    <?php endif;?>

                    <tr>
                        <td><?= $this->Number->format($transactionType->id) ?></td>
                        <td><?= ($transactionType->currency_id) ?></td>
                        <td><?= $transactionType->has('from_account') ? $this->Html->link($transactionType->from_account->name, ['controller' => 'Accounts', 'action' => 'view', $transactionType->from_account->id]) : '' ?></td>
                        <td><?= $transactionType->has('to_account') ? $this->Html->link($transactionType->to_account->name, ['controller' => 'Accounts', 'action' => 'view', $transactionType->to_account->id]) : '' ?></td>
                        <td><?= h($transactionType->name) ?></td>
                        <td><?= h($transactionType->name_on_receipt) ?></td>
                        <td><?= h($transactionType->currency_id) ?></td>
                        <td><?= ($transactionType->add_to_menu)?'<i class="glyphicon glyphicon-ok-sign"></i>':'<i class="glyphicon glyphicon-remove"></i>' ?></td>
                        <td class="actions">
                        
                        <?php if($transactionType->account_type_id == $appSettings->customer_account_type_id):?>
                            <?= $this->Html->link(__('Customers'), ['action' => 'index','controller'=>'Customers'],['class' => 'btn btn-info btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view Related Accounts']) ?>
                        <?php elseif(empty($transactionType->account_type_id)):?>
                            <?= $this->Html->link(__('Add Transaction'), ['action' => 'add',$transactionType->id,'controller'=>'Transactions'],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
						<?php else:?>
                            <?= $this->Html->link(__('Accounts'), ['action' => 'index','account_type_id'=>$transactionType->account_type_id,'controller'=>'Accounts'],['class' => 'btn btn-info btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view Related Accounts']) ?>
                        <?php endif;?>
                        
						<?= $this->Html->link(__('Transaction'), ['action' => 'index',$transactionType->id,'controller'=>'Transactions'],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                        <?= $this->Html->link(__('<i class="glyphicon glyphicon-file"></i>'), ['action' => 'add', 'copy'=>$transactionType->id],['class' => 'btn btn-default btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'Copy']) ?>
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