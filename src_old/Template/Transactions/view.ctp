

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
                        <li><?= $this->Html->link(__('List Transaction'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Transaction Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Transaction Type'), ['controller' => 'TransactionTypes', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit Transaction'), array('action' => 'edit',$transaction->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th><?= __('Id') ?></th>
                        <td><?= $this->Number->format($transaction->id) ?></td>
                    </tr>

                    <tr>
                        <th><?= __('Reference Code') ?></th>
                        <td><?= $transaction->reference_code ?></td>
                    </tr>

                    <tr>
                        <th><?= __('Transaction Status') ?></th>
                        <td><?= $transaction->has('transaction_status') ? $this->Html->link($transaction->transaction_status->name, ['controller' => 'TransactionStatuses', 'action' => 'view', $transaction->transaction_status->id]) : '' ?></td>
                    </tr>
                    
                    <tr>
                        <th><?= __('Transaction Type') ?></th>
                        <td><?= $transaction->has('transaction_type') ? $this->Html->link($transaction->transaction_type->name, ['controller' => 'TransactionTypes', 'action' => 'view', $transaction->transaction_type->id]) : '' ?></td>
                    </tr>

                    

                    
                    
                    
                    <tr>
                        <th><?= __('Quantity') ?></th>
                        <td><?= $this->Number->format($transaction->quantity) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Amount') ?></th>
                        <td><?= $this->Number->format($transaction->amount) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Value') ?></th>
                        <td><?= $this->Number->format($transaction->value) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Commission') ?></th>
                        <td><?= $this->Number->format($transaction->commission) ?></td>
                    </tr>

                    <tr>
                        <th><?= __('CreatedBy') ?></th>
                        <td><?= $transaction->has('created_by') ? $this->Html->link($transaction->created_by->name, ['controller' => 'Users', 'action' => 'view', $transaction->created_by->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('CompletedBy') ?></th>
                        <td><?= $transaction->has('completed_by') ? $this->Html->link($transaction->completed_by->name, ['controller' => 'Users', 'action' => 'view', $transaction->completed_by->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('ModifiedBy') ?></th>
                        <td><?= $transaction->has('modified_by') ? $this->Html->link($transaction->modified_by->name, ['controller' => 'Users', 'action' => 'view', $transaction->modified_by->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('TargetUser') ?></th>
                        <td><?= $transaction->has('target_user') ? $this->Html->link($transaction->target_user->name, ['controller' => 'Users', 'action' => 'view', $transaction->target_user->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('CreatedOn') ?></th>
                        <td><?=$this->Time->format($transaction->created,$authUser['time_format'],null,$authUser['time_zone']);?></td>
                    </tr>
                    <tr>
                        <th><?= __('ModifiedOn') ?></th>
                        <td><?=$this->Time->format($transaction->modified,$authUser['time_format'],null,$authUser['time_zone']);?></td>
                    </tr>
                    <tr>
                        <th><?= __('Customer') ?></th>
                        <td><?= $transaction->has('customer') ? $this->Html->link($transaction->customer->name, ['controller' => 'Customers', 'action' => 'view', $transaction->customer->id]) : '' ?></td>
                    </tr>
                    <?php if($transaction->has('customer')):?>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <td><?= h($transaction->customer->name) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Phone') ?></th>
                            <td><?= h($transaction->customer->phone) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Email') ?></th>
                            <td><?= h($transaction->customer->email) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Identity') ?></th>
                            <td><?= h($transaction->customer->identity) ?> (<?= h($transaction->customer->identity_type) ?>)</td>
                        </tr>
                    <?php endif;?>

                    <?php
                        $custom_fields_field = json_decode($transaction->custom_fields,true);
                        if (!empty($custom_fields_field)):
                            foreach ($custom_fields_field as $key => $value):
                    ?>
                            <tr><th><?= __(ucwords($key)) ?></th><th><?= __($value) ?></th></tr>
                    <?php 
                            endforeach;
                        endif;
                    ?>
                </table>                         
                <div class="row">
                    <h4><?= __('Comments') ?></h4>
                    <?= $this->Text->autoParagraph(h($transaction->system_comment)); ?>
                    <?= $this->Text->autoParagraph(h($transaction->user_comment)); ?>
                </div>                        
            </div>
        </div>
    </div>
</div>