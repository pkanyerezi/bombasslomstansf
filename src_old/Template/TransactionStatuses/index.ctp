
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
                    <li><?= $this->Html->link(__('New Transaction Status'), ['action' => 'add']) ?></li>
                    <li role="separator" class="divider"></li>                            
                    <li><?= $this->Html->link(__('List Transactions'), ['controller' => 'Transactions', 'action' => 'index']) ?></li>
                    </ul>
                </div>
            </div>
            <div class="box-tools pull-right">
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> New Transaction Status'), array('action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                                <th><?= $this->Paginator->sort('id') ?></th>
                                <th><?= $this->Paginator->sort('name') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactionStatuses as $transactionStatus): ?>
                    <tr>
                                <td><?= $this->Number->format($transactionStatus->id) ?></td>
                                <td><?= h($transactionStatus->name) ?></td>
                                <td class="actions">
                                <?= $this->Html->link(__($transactionStatus->name . ' Transactions'), ['action' => 'index','controller'=>'Transactions','transaction_status_id'=>$transactionStatus->id],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip']) ?>

                                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> Statement'), array('action' => 'statement','controller'=>'Transactions','transaction_status_id'=>$transactionStatus->id), array('class' => 'btn btn-primary btn-xs', 'escape' => false)); ?>
                                
                                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> Balance'), array('action' => 'balance','controller'=>'Transactions','transaction_status_id'=>$transactionStatus->id), array('class' => 'btn btn-primary btn-xs', 'escape' => false)); ?>


                            <!-- <?= $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), ['action' => 'view', $transactionStatus->id],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?> -->
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i>'), ['action' => 'edit', $transactionStatus->id],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                            <?= $this->Form->postLink(__('<i class="glyphicon glyphicon-trash"></i>'), ['action' => 'delete', $transactionStatus->id], ['class' => 'btn btn-danger btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'delete', 'confirm' => __('NB. All {0} transactions will be delete. Continue?', $transactionStatus->name)]) ?>
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



<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Transaction Statuses']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Transaction Statuses']); ?>