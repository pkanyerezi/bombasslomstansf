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
                        <li>
                        <?php if(!empty($transactionTypeId)):?>
                            <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> New Transaction'), array('action' => 'add',$transactionTypeId), array('class' => '', 'escape' => false)); ?>
                        <?php endif;?>
                        </li>
                        <li>
                        <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> Transactions Statement'), array('action' => 'statement',$transactionTypeId), array('class' => '', 'escape' => false)); ?>
                        </li>
                        <li>
                        <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> Transactions Balance'), array('action' => 'balance',$transactionTypeId), array('class' => '', 'escape' => false)); ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box-tools pull-right">
                <?php if(!empty($transactionTypeId)):?>
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> New Transaction'), array('action' => 'add',$transactionTypeId), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                <?php endif;?>
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> Statement'), array('action' => 'statement',$transactionTypeId), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> Balance'), array('action' => 'balance',$transactionTypeId), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>
        </div>
        <div class="box-body table-responsive">
            <form class="search" method="GET">
                <input name="q" placeholder="search by referenceCode" class="form-control" type="text">
            </form><hr>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                                <th><?= $this->Paginator->sort('id') ?></th>
                                <th><?= $this->Paginator->sort('reference_code','Code') ?></th>
                                <th><?= $this->Paginator->sort('date') ?></th>
                                <th><?= $this->Paginator->sort('transaction_type_id','Type') ?></th>
                                <th><?= $this->Paginator->sort('transaction_status_id','Status') ?></th>
                                <th><?= $this->Paginator->sort('customer_id') ?></th>
                                <th><?= $this->Paginator->sort('quantity') ?></th>
                                <th><?= $this->Paginator->sort('amount') ?></th>
                                <th><?= $this->Paginator->sort('value') ?></th>
                                <th class="actions" style="width: 155px;"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <?php $cssStyle = '';?>
                        <?php if(!(empty($transaction->target_user_id) || $transaction->target_user_id==$authUser['id'] || in_array($authUser['role'], ['super_admin','admin','manager']))):?>
                            <?php $cssStyle = 'color: #bbb !important;text-decoration: line-through;' ?>
                        <?php endif;?>
                    <tr style="<?=$cssStyle?>">
                        <td><?= $this->Number->format($transaction->id) ?></td>
                        <td><?= h($transaction->reference_code) ?></td>
                        <td>
                            <?=$this->Html->formatTime($this->Time->format($transaction->date,$authUser['time_format'],null,$authUser['time_zone']));?>
                        </td>
                        <td>
                            <?= $transaction->has('transaction_type') ? $this->Html->link($transaction->transaction_type->name, ['controller' => 'TransactionTypes', 'action' => 'view', $transaction->transaction_type->id]) : '' ?>
                            <?php if(!$transaction->parent_transaction_id):?>
							<div class="btn-group pull-right">
							<?= $this->Html->link(__('<i class="glyphicon glyphicon-th"></i>'), ['action' => 'index',$transaction->transaction_type->id,'controller'=>'Transactions'],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'List Transactions of this type']) ?>
                            </div>
							<?php else:?>
                                <div class="btn-group pull-right">
							   <?= $this->Html->link(__('<i class="glyphicon glyphicon-th"></i>'), ['action' => 'index',$transaction->transaction_type->id,'controller'=>'Transactions'],['class' => 'btn btn-primary btn-xs pull-right','escape'=>false,'data-toggle'=>'tooltip','title' => 'List Transactions of this type']) ?>
                                
                                </div>
                            <?php endif;?>
                        </td>
                        <td>
                            <?php if(isset($transaction->transaction_status->name)):?>
                                <?php if($transaction->transaction_status_id==$appSettings->final_transaction_status_id):?>
                                    <span style="color:green"><i class="glyphicon glyphicon-ok-circle"></i></span>
                                <?php endif;?>
                                <span><?=$transaction->transaction_status->name?></span>
                            <?php else:?>
                                <span style="color:red"><i class="glyphicon glyphicon-warning-sign"></i> Undefined</span>
                            <?php endif;?>
                        </td>
                        <td>
                            <?= $transaction->has('customer') ? $this->Html->link($transaction->customer->name, ['controller' => 'Customers', 'action' => 'view', $transaction->customer->id]) : '' ?>
                        </td>
                        <td><?= $this->Number->format($transaction->quantity) ?></td>
                        <td><?= $this->Number->format($transaction->amount) ?> <?=$transaction->currency_id?></td>
                        <td><?= $this->Number->format($transaction->value) ?> <?=$transaction->currency_id?></td>
                        <td class="actions">
                            <?php if (!$transaction->parent_transaction_id && $transaction->transaction_status->id!=$appSettings->final_transaction_status_id && $authUser['role']!='super_admin' && empty($cssStyle)): ?>
                                
                                <?= $this->Form->create($transaction,['style'=>'display: inline-block;','url'=>['action'=>'complete_transaction','controller'=>'Transactions']]) ?>
                                    <?php
                                        echo $this->Form->input('id',['type'=>'hidden']);
                                        echo $this->Form->input('transaction_status_id',['type'=>'hidden','value'=>$appSettings->final_transaction_status_id]);
                                        /*$_label = (empty($transaction->transaction_type->value_label)?'Value':$transaction->transaction_type->value_label);
                                        if ($transaction->transaction_type->reverse_operant!=3) {
                                            switch ($transaction->transaction_type->reverse_operant) {
                                                case 0:
                                                    $_label .= ' (Quantity*Amount)';break;
                                                case 1:
                                                    $_label .= ' (Quantity/Amount)';break;
                                                case 2:
                                                    $_label .= ' (Amount/Quantity)';break;
                                            }
                                        }*/

                                        echo $this->Form->input('value',['type'=>'hidden','value'=>(empty($transaction->value)?0:$transaction->value)
                                        ]);
                                    ?>
                                <?= $this->Form->button(__('<i class="glyphicon glyphicon-ok"></i>'),[
                                    'class' => 'btn btn-success btn-xs',
                                    'confirm'=> __('Is The Transaction ' . $appTransactionStatuses[$appSettings->final_transaction_status_id] . ' ?'),
                                    'title'=>'Mark transaction as ' . $appTransactionStatuses[$appSettings->final_transaction_status_id] . ', and Print receipt'
                                ]) ?>
                                <?= $this->Form->end() ?>

                            <?php endif; ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-file"></i>'), ['action' => 'receipt', $transaction->id],['class' => 'btn btn-info btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'Receipt']) ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), ['action' => 'view', $transaction->id],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                            <?php if(!$transaction->parent_transaction_id && empty($cssStyle)):?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i>'), ['action' => 'edit', $transaction->id],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                            <?= $this->Form->postLink(__('<i class="glyphicon glyphicon-trash"></i>'), ['action' => 'delete', $transaction->id], ['class' => 'btn btn-danger btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'delete', 'confirm' => __('Are you sure you want to delete transaction with ID # {0}?', $transaction->id)]) ?>
                            <?php endif;?>
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


<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Transactions']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Transactions']); ?>