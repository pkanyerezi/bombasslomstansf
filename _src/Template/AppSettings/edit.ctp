
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
                        <li><?= $this->Html->link(__('List App Setting'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Branches'), ['controller' => 'Branches', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Branch'), ['controller' => 'Branches', 'action' => 'add']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Customer Account Types'), ['controller' => 'CustomerAccountTypes', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Customer Account Type'), ['controller' => 'CustomerAccountTypes', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> List App Setting'), array('action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->Form->create($appSetting,['class'=>'form']) ?>
                <fieldset>
                    <legend><?= __('Edit App Setting') ?></legend>
                    <?php
                        echo $this->Form->input('app_name', ['class'=>'form-control']);
                        echo $this->Form->input('branch_id', ['options' => $branches,'class'=>'form-control']);
                        echo $this->Form->input('customer_account_type_id', ['options' => $customerAccountTypes,'class'=>'form-control']);
                        echo $this->Form->input('customer_identity_types', ['class'=>'form-control']);
                        echo $this->Form->input('final_transaction_status_id', ['class'=>'form-control']);
                        echo $this->Form->input('customer_custom_fields', ['class'=>'form-control']);
                        echo $this->Form->input('all_branches_contacts', ['class'=>'form-control']);
                        echo $this->Form->input('app_theme', ['class'=>'form-control','options'=>[
                            "skin-blue"=>'blue',
                            "skin-black"=>'black',
                            "skin-red"=>'red',
                            "skin-yellow"=>'yellow',
                            "skin-purple"=>'purple',
                            "skin-green"=>'green',
                            "skin-blue-light"=>'blue-light',
                            "skin-black-light"=>'black-light',
                            "skin-red-light"=>'red-light',
                            "skin-yellow-light"=>'yellow-light',
                            "skin-purple-light"=>'purple-light',
                            "skin-green-light"=>'green-light'
                        ]]);
                        echo $this->Form->input('max_menu_items', ['class'=>'form-control']);
                    ?>
                </fieldset>
                <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

