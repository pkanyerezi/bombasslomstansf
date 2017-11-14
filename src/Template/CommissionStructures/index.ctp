
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
                    <li><?= $this->Html->link(__('New Commission Structure'), ['action' => 'add']) ?></li>
                    <li role="separator" class="divider"></li>                            <li><?= $this->Html->link(__('List Transaction Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?></li>
                            <li><?= $this->Html->link(__('New Transaction Type'), ['controller' => 'TransactionTypes', 'action' => 'add']) ?></li>
                                      </ul>
                </div>
            </div>
            <div class="box-tools pull-right">
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> New Commission Structure'), array('action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                                <th><?= $this->Paginator->sort('id') ?></th>
                                <th><?= $this->Paginator->sort('TransactionTypes.currency_id') ?></th>
                                <th><?= $this->Paginator->sort('name') ?></th>
								<th><?= $this->Paginator->sort('tag') ?></th>
                                <th><?= $this->Paginator->sort('transaction_type_id') ?></th>
								<th><?= $this->Paginator->sort('enabled') ?></th>
								<th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commissionStructures as $commissionStructure): ?>
                    <tr class="text">
                        <td><?= $this->Number->format($commissionStructure->id) ?></td>
                        <td><?= h($commissionStructure->transaction_type->currency_id) ?></td>
                        <td><?= h($commissionStructure->name) ?></td>
						<td><?= h($commissionStructure->tag) ?></td>
                        <td>
							<?= $this->Html->link($commissionStructure->transaction_type->name, ['controller' => 'TransactionTypes', 'action' => 'view', $commissionStructure->transaction_type_id],[
								'class'=>($commissionStructure->transaction_type->commission_structure_id)?'alert-danger':'',
								'title'=>($commissionStructure->transaction_type->commission_structure_id)?'This Transaction Type has a commission structure.':'',
							])  ?>
							<div>
								<span class="label label-default">Branch</span> 
                                <?= $this->Html->link($commissionStructure->transaction_type->branch->name, ['controller' => 'Branches', 'action' => 'view', 
                                $commissionStructure->transaction_type->branch_id])  ?>
							</div>
							<div>
								<span class="label label-default">From Account</span> <?= $this->Html->link('(' . $commissionStructure->transaction_type->from_account->currency_id . ')' .$commissionStructure->transaction_type->from_account->name, ['controller' => 'Branches', 'action' => 'view', $commissionStructure->transaction_type->from_account_id])  ?>
								<br><span class="label label-default">To Account</span>
								<?= $this->Html->link('(' . $commissionStructure->transaction_type->to_account->currency_id . ')' . $commissionStructure->transaction_type->to_account->name, ['controller' => 'Branches', 'action' => 'view', $commissionStructure->transaction_type->to_account_id])  ?>
							</div>
						</td>
						<td><?= ($commissionStructure->enabled)?'Yes':'No' ?></td>
						<td class="actions">
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-file"></i>'), ['action' => 'add', 'copy'=> $commissionStructure->id],['class' => 'btn btn-default btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'Copy this record']) ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), ['action' => 'view', $commissionStructure->id],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i>'), ['action' => 'edit', $commissionStructure->id],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                            <?= $this->Form->postLink(__('<i class="glyphicon glyphicon-trash"></i>'), ['action' => 'delete', $commissionStructure->id], ['class' => 'btn btn-danger btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'delete', 'confirm' => __('Are you sure you want to delete commissionStructure with ID # {0}?', $commissionStructure->id)]) ?>
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



<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Commission Structures']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Commission Structures']); ?>