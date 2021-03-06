
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
                    <li><?= $this->Html->link(__('New Account'), ['action' => 'add']) ?></li>
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
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> New Account'), array('action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('name') ?></th>
                        <th><?= $this->Paginator->sort('currency_id') ?></th>
                        <th><?= $this->Paginator->sort('initial_balance') ?></th>
                        <th><?= $this->Paginator->sort('account_type_id') ?></th>
                        <th><?= $this->Paginator->sort('branch_id') ?></th>
                        <th><?= $this->Paginator->sort('enabled') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                        <th class="actions"><?= __('') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $currentCurrency = null;?>
                    <?php $currentCurrencyChanged = false;?>
                    <?php foreach ($accounts as $account): ?>
                        <?php $currentCurrencyChanged = (!empty($currentCurrency) && $currentCurrency!=$account->currency_id)?true:false;?>
                        <?php $currentCurrency = $account->currency_id;?>

                    <?php if($currentCurrencyChanged):?>
                        <tr><td colspan="7" style="height: 100px;">&nbsp;</td></tr>
                    <?php endif;?>
                    

                    <tr>
                        <td><?= $this->Number->format($account->id) ?></td>
                        <td>
                            <?= $this->Html->link(__(h($account->name)), ['action' => 'view', $account->id],['data-toggle'=>'tooltip','title' => 'view account and perform actions']) ?>
                        </td>
                        <td><?= h($account->currency_id) ?></td>
                        <td><?= $this->Number->format($account->initial_balance) ?> <?= h($account->currency_id) ?></td>
                        <td>
                        <?= $account->has('account_type') ? $this->Html->link($account->account_type->name, ['controller' => 'AccountTypes', 'action' => 'view', $account->account_type->id]) : '' ?>
                        </td>
                        <td>
                        <?= $account->has('branch') ? $this->Html->link($account->branch->name, ['controller' => 'Branches', 'action' => 'view', $account->branch->id]) : '' ?>
                        </td>
                        <td><?= ($account->enabled)?'Yes':'No' ?></td>
                        <td>
                            <?= $this->Html->link(__('Transaction Types'), ['action' => 'index', $account->id,'controller'=>'TransactionTypes'],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                            <?= $this->Html->link(__('Add Transaction Types'), ['action' => 'add', $account->id,'controller'=>'TransactionTypes'],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                        </td>
                        <td class="actions">
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> Transactions'), ['action' => 'index', '?'=> ['account_id'=> $account->id],'controller'=>'Transactions'],['class' => 'btn btn-success btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view transactions with this account']) ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-file"></i>'), ['action' => 'add', 'copy'=>$account->id],['class' => 'btn btn-default btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'Copy this record']) ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), ['action' => 'view', $account->id],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i>'), ['action' => 'edit', $account->id],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                            <?= $this->Form->postLink(__('<i class="glyphicon glyphicon-trash"></i>'), ['action' => 'delete', $account->id], ['class' => 'btn btn-danger btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'delete', 'confirm' => __('Are you sure you want to delete accountType with ID # {0}?', $account->id)]) ?>
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



<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Accounts']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Accounts']); ?>