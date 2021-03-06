

<div>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-left">
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Actions
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><?= $this->Html->link(__('List App Setting'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Branches'), ['controller' => 'Branches', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Branch'), ['controller' => 'Branches', 'action' => 'add']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Customer Account Types'), ['controller' => 'CustomerAccountTypes', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Customer Account Type'), ['controller' => 'CustomerAccountTypes', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit App Setting'), array('action' => 'edit',$appSetting->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th><?= __('App Name') ?></th>
                        <td><?=h($appSetting->app_name)?></td>
                    </tr>
                    <tr>
                        <th><?= __('Branch') ?></th>
                        <td><?= $appSetting->has('branch') ? $this->Html->link($appSetting->branch->name, ['controller' => 'Branches', 'action' => 'view', $appSetting->branch->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Customer Account Type') ?></th>
                        <td><?= $appSetting->has('customer_account_type') ? $this->Html->link($appSetting->customer_account_type->name, ['controller' => 'CustomerAccountTypes', 'action' => 'view', $appSetting->customer_account_type->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Final Transaction Status') ?></th>
                        <td><?= $appSetting->has('final_transaction_status') ? $this->Html->link($appSetting->final_transaction_status->name, ['controller' => 'TransactionStatuses', 'action' => 'view', $appSetting->final_transaction_status->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Customer Identity Types') ?></th>
                        <td><?= h($appSetting->customer_identity_types) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Customer Custom fields') ?></th>
                        <td><?= h($appSetting->customer_custom_fields) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('All Branches Contacts') ?></th>
                        <td><?= h($appSetting->all_branches_contacts) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>