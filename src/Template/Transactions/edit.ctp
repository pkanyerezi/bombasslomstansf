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
                        <li><?= $this->Html->link(__('List Transaction'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Transaction Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Transaction Type'), ['controller' => 'TransactionTypes', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> List Transaction'), array('action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->Form->create($transaction,['class'=>'form']) ?>
                <fieldset>
                    <legend><?= __('Edit Transaction') ?></legend>
                    <?php
                        echo $this->Form->input('transaction_status_id',['class'=>'form-control']);

                        echo $this->Form->input('user_comment',['class'=>'form-control']);

                        $custom_field_options = [];
                        @$custom_field_options = json_decode($transaction->transaction_type->custom_field_options,true);
                        if (isset($transaction->transaction_type->custom_fields) && !empty($transaction->transaction_type->custom_fields)) {
                            $custom_fields_field = json_decode($transaction->custom_fields,true);
                            $custom_fields = explode(',', $transaction->transaction_type->custom_fields);
                            foreach ($custom_fields as $value) {

                                if($value=='commission'){
                                    continue;
                                }

                                if (isset($custom_field_options[$value]) && is_array($custom_field_options[$value])) {
                                    echo $this->Form->input($value,
                                    array_merge([
                                        'name'=>'custom_fields['.$value.']',
                                        'class'=>'form-control',
                                        'value'=>(isset($custom_fields_field[$value])?$custom_fields_field[$value]:'')
                                    ],$custom_field_options[$value]));
                                }else{
                                    echo $this->Form->input($value,[
                                        'name'=>'custom_fields['.$value.']',
                                        'class'=>'form-control',
                                        'value'=>(isset($custom_fields_field[$value])?$custom_fields_field[$value]:'')
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
</div>