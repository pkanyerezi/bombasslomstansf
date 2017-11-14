
<div>
    <?php if(isset($customer)):?>
    <div class="col-xs-3">
        <br>
        <h4>Customer Details</h4>
        <table class="table table-bordered table-striped">
            <tr>
                <th><?= __('Name') ?></th>
                <td>
                    <?= $this->Html->link(__($customer->name), ['action' => 'view',$customer->id,'controller'=>'Customers']) ?>
                </td>
            </tr>
            <tr>
                <th><?= __('Phone') ?></th>
                <td><?= h($customer->phone) ?></td>
            </tr>
            <tr>
                <th><?= __('Email') ?></th>
                <td><?= h($customer->email) ?></td>
            </tr>
            <tr>
                <th><?= __('Identity') ?></th>
                <td><?= h($customer->identity) ?> (<?= h($customer->identity_type) ?>)</td>
            </tr>
            <tr>
                <th>Action</th>
                <td>
                <?= $this->Html->link(__('Balance Sheet'), ['controller'=>'Transactions','action' => 'balance','customer_id'=>$customer->id],['class'=>'btn btn-info btn-xs']) ?>
                <?= $this->Html->link(__('Accounts Summary'), ['controller'=>'Accounts','action' => 'accountBalance','customer_id'=>$customer->id],['class'=>'btn btn-info btn-xs']) ?>
                </td>
            </tr>
        </table><br>
    </div>
    <div class="col-xs-6">
    <?php else:?>
    <div class="col-xs-6 col-xs-offset-3">
    <?php endif;?>
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-left">
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Actions
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><?= $this->Html->link(__('List Transactions'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                
                        <li><?= $this->Html->link(__('Edit This Transaction Types'), ['controller' => 'TransactionTypes', 'action' => 'edit',$transactionType->id]) ?></li>
                        <li><?= $this->Html->link(__('List Transaction Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?></li>
                        <li><?= $this->Html->link(__('New Transaction Type'), ['controller' => 'TransactionTypes', 'action' => 'add']) ?></li>
                        </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> List Transactions'), array('action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->Form->create($transaction,['class'=>'form']) ?>
                <fieldset>
                    <legend><?= __($transactionType->name) ?></legend>
                    <?php
                        echo $this->Form->input('transaction_type_id', ['type'=>'hidden','value'=>$transactionType->id]);
                        if($transactionType->require_target_user){
                            echo $this->Form->input('target_user_id',['options'=>$target_users,'class'=>'form-control']);
                        }

                        if ($transactionType->reverse_operant !=3) {
                            
                            echo $this->Form->input('quantity',[
                                'class'=>'form-control',
                                'value'=>(empty($transaction->quantity)?1:$transaction->quantity),
                                'label'=>(empty($transactionType->quantity_label)?'Quantity':$transactionType->quantity_label)
                            ]);

                            echo $this->Form->input('amount',[
                                'class'=>'form-control',
                                'value'=>(empty($transaction->amount)?0:$transaction->amount),
                                'label'=>(empty($transactionType->amount_label)?'Amount ('.$transactionType->currency_id.')':$transactionType->amount_label)
                            ]);
                        }

                        $_label = (empty($transactionType->value_label)?'Value':$transactionType->value_label);
                        if ($transactionType->reverse_operant!=3) {
                            switch ($transactionType->reverse_operant) {
                                case 0:
                                    $_label .= ' (Quantity*Amount)';break;
                                case 1:
                                    $_label .= ' (Quantity/Amount)';break;
                                case 2:
                                    $_label .= ' (Amount/Quantity)';break;
                            }
                        }

                        echo $this->Form->input('value',[
                            'class'=>'form-control',
                            'value'=>(empty($transaction->value)?0:$transaction->value),
                            'label'=>$_label,
                            'readonly'=>(($transactionType->reverse_operant==3)?false:true)
                        ]);
                        echo $this->Form->input('user_comment',['label'=>'Comment','class'=>'form-control']);
                        @$custom_field_options = json_decode($transactionType->custom_field_options,true);
                        if (isset($transactionType->custom_fields) && !empty($transactionType->custom_fields)) {
                            $custom_fields = explode(',', $transactionType->custom_fields);
                            foreach ($custom_fields as $value) {
                                if (isset($custom_field_options[$value]) && is_array($custom_field_options[$value])) {
                                    echo $this->Form->input($value,
                                    array_merge([
                                        'name'=>'custom_fields['.$value.']',
                                        'class'=>'form-control'
                                    ],$custom_field_options[$value]));
                                }else{
                                    echo $this->Form->input($value,[
                                        'name'=>'custom_fields['.$value.']',
                                        'class'=>'form-control'
                                    ]);
                                }
                            }
                        }
                        echo $this->Form->input('date',['class'=>'form-control']);
                    ?>
                </fieldset>
                <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
    <?php if(isset($customer)):?>
        <div class="col-xs-3">
            <table class="table table-bordered table-striped">
                <tr>
                    <td colspan="2">
                        <a href="<?=$this->request->webroot?>img/customers/identification/<?= h($customer->identification_img) ?>" target="_blank"><img src="<?=$this->request->webroot?>img/customers/identification/<?= h($customer->identification_img) ?>" alt="<?= h($customer->name) ?>">
                            <?= $this->Html->link(__('Edit ID/Passport'), ['controller'=>'Customers','action' => 'editImages',$customer->id,'identification'],['class'=>'btn btn-info btn-xs']) ?>
                        </a>
                    </td>
                </tr>
            </table><br>
        </div>
    <?php endif;?>
</div>


<script type="text/javascript">
    $(document).ready(function(){
        $("#quantity,#amount").focusout(function(){
            var quantity = Number($('#quantity').val());
            var amount = Number($('#amount').val());
            var value = 0;
            <?php if ($transactionType->reverse_operant==0):?>
                value = quantity*amount;
            <?php elseif($transactionType->reverse_operant==1):?>
                value = quantity/amount;
            <?php elseif($transactionType->reverse_operant==2):?>
                value = amount/quantity;
            <?php elseif($transactionType->reverse_operant==3):?>
                value = Number($('#value').val());
            <?php endif;?>
            $('#value').val(Math.round(value,<?=$transactionType->decimal_places?>));
        });
    });
</script>