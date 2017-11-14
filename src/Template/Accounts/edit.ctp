
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
                            <li><?= $this->Html->link(__('List Accounts'), ['action' => 'index']) ?></li>
                        </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> List Accounts'), array('action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->Form->create($account,['class'=>'form']) ?>
                <fieldset>
                    <legend><?= __('Edit Account') ?></legend>
                    <?php
                        echo $this->Form->input('branch_id',['class'=>'form-control']);
                        echo $this->Form->input('account_type_id',['class'=>'form-control']);
                        echo $this->Form->input('currency_id',['class'=>'form-control']);
                        echo $this->Form->input('initial_balance',['class'=>'form-control']);
                        echo $this->Form->input('name',['class'=>'form-control']);
                        echo $this->Form->input('description',['class'=>'form-control']);
                        echo $this->Form->input('enabled',['class'=>'form-control']);
                    ?>
                </fieldset>
                <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

