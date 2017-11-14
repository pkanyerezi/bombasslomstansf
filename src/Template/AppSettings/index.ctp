
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
                        <?= $this->Html->link(__('List Branches'), ['controller' => 'Branches', 'action' => 'index']) ?>
                    </li>
                    <li><?= $this->Html->link(__('New Branch'), ['controller' => 'Branches', 'action' => 'add']) ?></li>
                    <li role="separator" class="divider"></li>                            
                    <li><?= $this->Html->link(__('List Customer Account Types'), ['controller' => 'CustomerAccountTypes', 'action' => 'index']) ?></li>
                    <li><?= $this->Html->link(__('New Customer Account Type'), ['controller' => 'CustomerAccountTypes', 'action' => 'add']) ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('app_name') ?></th>
                        <th><?= $this->Paginator->sort('branch_id') ?></th>
                        <th><?= $this->Paginator->sort('customer_account_type_id') ?></th>
                        <th><?= $this->Paginator->sort('final_transaction_status_id') ?></th>
						<th><?= $this->Paginator->sort('cancel_transaction_status_id') ?></th>
                        <th><?= $this->Paginator->sort('customer_identity_types') ?></th>
                        <th><?= $this->Paginator->sort('customer_custom_fields') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appSettings as $appSetting): ?>
                    <tr>
                        <td>
                            <?=h($appSetting->app_name)?>
                        </td>
                        <td><?= $appSetting->has('branch') ? $this->Html->link($appSetting->branch->name, ['controller' => 'Branches', 'action' => 'view', $appSetting->branch->id]) : '' ?></td>
                        <td><?= $appSetting->has('customer_account_type') ? $this->Html->link($appSetting->customer_account_type->name, ['controller' => 'CustomerAccountTypes', 'action' => 'view', $appSetting->customer_account_type->id]) : '' ?></td>
                        <td><?= $appSetting->has('final_transaction_status') ? $this->Html->link($appSetting->final_transaction_status->name, ['controller' => 'TransactionStatuses', 'action' => 'view', $appSetting->final_transaction_status->id]) : '' ?></td>
						<td><?= $appSetting->has('cancel_transaction_status') ? $this->Html->link($appSetting->cancel_transaction_status->name, ['controller' => 'TransactionStatuses', 'action' => 'view', $appSetting->cancel_transaction_status->id]) : '' ?></td>
                        <td>
                            <?=h($appSetting->customer_identity_types)?>
                        </td>
                        <td>
                            <?=h($appSetting->customer_custom_fields)?>
                        </td>
                        <td class="actions">
                        <?= $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), ['action' => 'view', $appSetting->id],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                        <?= $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i>'), ['action' => 'edit', $appSetting->id],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                        <?= $this->Form->postLink(__('<i class="glyphicon glyphicon-trash"></i>'), ['action' => 'delete', $appSetting->id], ['class' => 'btn btn-danger btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'delete', 'confirm' => __('Are you sure you want to delete appSetting with ID # {0}?', $appSetting->id)]) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>  
    </div>
</div>



<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'App Settings']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'App Settings']); ?>