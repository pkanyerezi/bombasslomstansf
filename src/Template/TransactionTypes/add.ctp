
<div>
    <div class="col-xs-6 col-xs-offset-3">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-left">
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Actions
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><?= $this->Html->link(__('List Transaction Type'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Account Types'), ['controller' => 'AccountTypes', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Account Type'), ['controller' => 'AccountTypes', 'action' => 'add']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Commission Structures'), ['controller' => 'CommissionStructures', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Commission Structure'), ['controller' => 'CommissionStructures', 'action' => 'add']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Transaction Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Transaction Type'), ['controller' => 'TransactionTypes', 'action' => 'add']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Transactions'), ['controller' => 'Transactions', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Transaction'), ['controller' => 'Transactions', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> List Transaction Types'), array('action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->Form->create($transactionType,['class'=>'form']) ?>
                <fieldset>
                    <legend><?= __('Add Transaction Type') ?></legend>
                    <?php
                        echo $this->Form->input('show_on_receipt',['class'=>'form-control','checked'=>true]);
                        echo $this->Form->input('add_to_menu',['class'=>'form-control']);
                        
                        echo $this->Form->input('super_admin_menu_only',['class'=>'form-control','title'=>'Only the super admin will have access to this transaction type in the menu. You must also allow it to be visible in the menu.']);
                        echo $this->Form->input('name',['class'=>'form-control','title'=>'This is the name that will appear in the balance sheet and lists/pages from the branch it will appear or be viewed from']);
                        echo $this->Form->input('menu_link_title',['class'=>'form-control','title'=>'This is the name the person creating the transaction will view in the side menu']);
                        echo $this->Form->input('name_on_receipt',['class'=>'form-control','title'=>'This is the name that will be used on the reciept']);


                        echo $this->Form->input('currency_id', ['class'=>'form-control','title'=>'All transactions created will have this currency.']);
                        echo $this->Form->input('transaction_status_id', ['title'=>'By default, all its transactions will contain this transaction status when being created. It can only be changed by editing the transaction.','label'=>'Default Transaction Status','class'=>'form-control']);
                        echo $this->Form->input('from_account_id', ['class'=>'form-control']);
                        echo $this->Form->input('to_account_id', ['class'=>'form-control']);
                        echo $this->Form->input('branch_id', ['label'=>'Branch Its Parent Transactions Will Create','class'=>'form-control','title'=>'Only this branch will access this transaction']);
                        echo $this->Form->input('from_branch_id', ['title'=>'For this Transaction type to Appear in side bar menu, Set similar to branch its transactions will be created from.','class'=>'form-control']);
                        echo $this->Form->input('to_branch_id', ['class'=>'form-control','title'=>'This is the branch where the money is intended to be sent to.']);
                        echo $this->Form->input('on_create_lock_to_branch_from', ['class'=>'form-control','title'=>'This makes sure that the transaction can only be initiated from the branch the money is intende to come from','checked'=>true]);
                        
                        $accountTypes = $accountTypes->toArray();
                        array_unshift($accountTypes,'none');
                        echo $this->Form->input('account_type_id', ['options' => $accountTypes,'class'=>'form-control']);

                        echo $this->Form->input('require_target_user',['class'=>'form-control','title'=>'This will require the person additind a transaction to select the emplyee or user that should view the resultant transaction']);
                        echo $this->Form->input('description',['class'=>'form-control']);
                        echo $this->Form->input('custom_fields',['class'=>'form-control','placeholder'=>'commission,reference_code']);
                        echo $this->Form->input('custom_field_options',['class'=>'form-control']);
                        echo $this->Form->input('commission_structure_id', ['options' => $commissionStructures, 'empty' => true,'class'=>'form-control']);
                        echo $this->Form->input('linked_transaction_type_id', ['options' => $linkedTransactionTypes, 'empty' => true,'class'=>'form-control']);
                        echo $this->Form->input('balance_sheet_side',['class'=>'form-control','options'=>[0=>'Debit',1=>'Credit']]);
                        echo $this->Form->input('priority',['class'=>'form-control','value'=>20]);
                        echo $this->Form->input('quantitized',['class'=>'form-control']);
                        echo $this->Form->input('enabled',['class'=>'form-control','checked'=>'checked']);

                        echo $this->Form->input('quantity_label',['class'=>'form-control']);
                        echo $this->Form->input('amount_label',['class'=>'form-control']);
                        echo $this->Form->input('value_label',['class'=>'form-control']);
                        echo $this->Form->input('decimal_places',['class'=>'form-control','value'=>2]);
                        echo $this->Form->input('reverse_operant',['class'=>'form-control','type'=>'select','options'=>[
                                '3'=>'value only',
                                '0'=>'Multiply(Quantity*Amount)',
                                '1'=>'Divide(Quantity/Amount)',
                                '2'=>'Divide(Amount/Quantity)',
                                ]]);
                        
                                ?>
                </fieldset>
                <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

