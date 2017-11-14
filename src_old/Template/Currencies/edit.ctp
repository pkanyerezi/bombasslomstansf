
<div>
    <div class="col-xs-6 col-xs-offset-3">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> List Currency'), array('action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->Form->create($currency,['class'=>'form']) ?>
                <fieldset>
                    <legend><?= __('Edit Currency') ?></legend>
                    <?php
                                    echo $this->Form->input('name',['class'=>'form-control']);
                                    echo $this->Form->input('priority',['class'=>'form-control']);
                                ?>
                </fieldset>
                <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

