

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
                            <li><?= $this->Html->link(__('List Transactions'), ['controller' => 'Transactions', 'action' => 'index',$transactionType->id]) ?></li>
                            <li><?= $this->Html->link(__('New Transaction'), ['controller' => 'Transactions', 'action' => 'add',$transactionType->id]) ?></li>
                        </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit Transaction Type'), array('action' => 'edit',$transactionType->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Add Transaction'), array('action' => 'add',$transactionType->id,'controller'=>'Transactions'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
               
                    <table class="table table-bordered table-striped">
                                                                        <tr>
                            <th><?= __('From Account') ?></th>
                            <td><?= $transactionType->has('from_account') ? $this->Html->link($transactionType->from_account->name, ['controller' => 'AccountTypes', 'action' => 'view', $transactionType->from_account->id]) : '' ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('To Account') ?></th>
                            <td><?= $transactionType->has('to_account') ? $this->Html->link($transactionType->to_account->name, ['controller' => 'AccountTypes', 'action' => 'view', $transactionType->to_account->id]) : '' ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Name') ?></th>
                            <td><?= h($transactionType->name) ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Menu Link Title') ?></th>
                            <td><?= h($transactionType->menu_link_title) ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Commission Structure') ?></th>
                            <td><?= $transactionType->has('commission_structure') ? $this->Html->link($transactionType->commission_structure->name, ['controller' => 'CommissionStructures', 'action' => 'view', $transactionType->commission_structure->id]) : '' ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Linked Transaction Type') ?></th>
                            <td><?= $transactionType->has('linked_transaction_type') ? $this->Html->link($transactionType->linked_transaction_type->name, ['controller' => 'LinkedTransactionTypes', 'action' => 'view', $transactionType->linked_transaction_type->id]) : '' ?></td>
                        </tr>
                                                                                                                        <tr>
                            <th><?= __('Id') ?></th>
                            <td><?= $this->Number->format($transactionType->id) ?></td>
                        </tr>
                                                                                        <tr>
                            <th><?= __('Created') ?></th>
                            <td><?=$this->Time->format($transaction->created,$authUser['time_format'],null,$authUser['time_zone']);?></td>
                        </tr>
                                        <tr>
                            <th><?= __('Modified') ?></th>
                            <td><?=$this->Time->format($transaction->modified,$authUser['time_format'],null,$authUser['time_zone']);?></td>
                        </tr>
                                                                                        <tr>
                            <th><?= __('Add To Menu') ?></th>
                            <td><?= $transactionType->add_to_menu ? __('Yes') : __('No'); ?></td>
                        </tr>
                                        <tr>
                            <th><?= __('Balance Sheet Side') ?></th>
                            <td><?= $transactionType->balance_sheet_side ? __('Yes') : __('No'); ?></td>
                        </tr>
                                        <tr>
                            <th><?= __('Quantitized') ?></th>
                            <td><?= $transactionType->quantitized ? __('Yes') : __('No'); ?></td>
                        </tr>
                                        <tr>
                            <th><?= __('Enabled') ?></th>
                            <td><?= $transactionType->enabled ? __('Yes') : __('No'); ?></td>
                        </tr>
                                                    </table>
                                                    <div class="row">
                        <h4><?= __('Description') ?></h4>
                        <?= $this->Text->autoParagraph(h($transactionType->description)); ?>
                    </div>
                                    <div class="row">
                        <h4><?= __('Custom Fields') ?></h4>
                        <?= $this->Text->autoParagraph(h($transactionType->custom_fields)); ?>
                    </div>
                                            </div>
        </div>
    </div>
</div>