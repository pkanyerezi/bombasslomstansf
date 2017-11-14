
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
                        <li><?= $this->Html->link(__('List Customer'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Branches'), ['controller' => 'Branches', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Branch'), ['controller' => 'Branches', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> List Customer'), array('action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->Form->create($customer,['class'=>'form']) ?>
                <fieldset>
                    <legend><?= __('Add Customer') ?></legend>
                    <?php
                        $identity_types = $appSettings->customer_identity_types;
                        $identity_type = [];
                        if (!empty($identity_types)) {
                            $identity_types = explode(',', $identity_types);
                            foreach ($identity_types as $value) {
                                $identity_type[trim($value)] = ucwords(str_replace('_', " ", trim($value)));
                            }
                        }
                        // echo $this->Form->input('branch_id', ['options' => $branches,'class'=>'form-control']);
                        echo $this->Form->input('name',['class'=>'form-control']);
                        echo $this->Form->input('phone',['class'=>'form-control']);
                        echo $this->Form->input('email',['class'=>'form-control']);
                        echo $this->Form->input('identity',['class'=>'form-control']);
                        echo $this->Form->input('identity_type',['type'=>'select','options'=>$identity_type,'class'=>'form-control']);
                        if (!empty($appSettings->customer_custom_fields)) {
                            $custom_fields = explode(',', $appSettings->customer_custom_fields);
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
                    ?>
                </fieldset>
                <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

