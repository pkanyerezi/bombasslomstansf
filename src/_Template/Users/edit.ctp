<div>
    <div class="col-md-6 col-md-offset-3">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> List User'), array('action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->Form->create($user,['class'=>'form']) ?>
                <fieldset>
                    <legend><?= __('Edit User') ?></legend>
                    <?php
                                    echo $this->Form->input('name',['class'=>'form-control']);
                                    echo $this->Form->input('username',['class'=>'form-control']);
                                    echo $this->Form->input('password',['class'=>'form-control']);
                                    if ($online && $authUser['role']=='super_admin') {
                                        $roles = [
                                            'super_admin'=>'Super Admin',
                                            'employee'=>'Employee'
                                        ];
                                        if (!in_array($user->id,['1',1])){
                                            echo $this->Form->input('role',['type'=>'select','options'=>$roles,'class'=>'form-control']);
                                        }
                                    }
                                    echo $this->Form->input('branch_id',['class'=>'form-control']);
                                    echo $this->Form->input('email',['class'=>'form-control']);
                                    echo $this->Form->input('contact_details',['class'=>'form-control']);
                                    $x =  DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                                    $x = array_combine($x, $x);
                                    echo $this->Form->input('time_zone',['class'=>'form-control','options'=>$x]);
                                    echo $this->Form->input('time_format',['class'=>'form-control']);
                                    echo $this->Form->input('is_active',['class'=>'form-control']);
                                ?>
                </fieldset>
                <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

