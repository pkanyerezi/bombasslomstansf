<div>
    <div class="box box-primary">
        <div class="box-header">
            <div class="box-tools pull-right">
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> New User'), array('action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('name') ?></th>
                        <th><?= $this->Paginator->sort('username') ?></th>
                        <th><?= $this->Paginator->sort('role') ?></th>
                        <th><?= $this->Paginator->sort('branch_id') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?= $this->Html->link(__($user->name), ['action' => 'view', $user->id],['escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                        </td>
                        <td><?= h($user->username) ?></td>
                        <td><?= h($user->role) ?></td>
                        <td>
                        <?= $this->Html->link(__($user->branch->name), ['controller'=>'Branches','action' => 'view', $user->branch->id],['escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                        </td>
                        <td>
                            <div class="box-tools pull-left">
                                <div class="dropdown">
                                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Actions
                                    <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><?= $this->Html->link(__('Created Transactions'), ['controller'=>'Transactions','action' => 'index','created_by_id'=>$user->id]) ?></li>
                                    <li><?= $this->Html->link(__('Completed Transactions'), ['controller'=>'Transactions','action' => 'index','completed_by_id'=>$user->id]) ?></li>
                                    <li><?= $this->Html->link(__('Modified Transactions'), ['controller'=>'Transactions','action' => 'index','modified_by_id'=>$user->id]) ?></li>
                                    <li role="separator" class="divider"></li> 
                                    <li>
                                    
                                    </li>
                                    <li>
                                    <?= $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i>'), ['action' => 'edit', $user->id],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                                    </li>

                                    <?php if($user->role!='super_admin' && $user->id!=$authUser['id']):?>
                                        <li>
                                    <?= $this->Form->postLink(__('<i class="glyphicon glyphicon-trash"></i>'), ['action' => 'delete', $user->id], ['class' => 'btn btn-danger btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'delete', 'confirm' => __('Are you sure you want to delete user with ID # {0}?', $user->id)]) ?>
                                    </li>
                                    <?php endif;?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                </ul>
                <p><?= $this->Paginator->counter() ?></p>
            </div>
        </div>  
    </div>
</div>



<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Users']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Users']); ?>