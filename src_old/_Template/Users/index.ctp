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
                                <th><?= $this->Paginator->sort('email') ?></th>
                                <th><?= $this->Paginator->sort('contact_details') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                                <td><?= h($user->name) ?></td>
                                <td><?= h($user->username) ?></td>
                                <td><?= h($user->role) ?></td>
                                <td><?= h($user->email) ?></td>
                                <td><?= h($user->contact_details) ?></td>
                                <td class="actions">
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), ['action' => 'view', $user->id],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i>'), ['action' => 'edit', $user->id],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>

                            <?php if($user->id!=1):?>
                            <?= $this->Form->postLink(__('<i class="glyphicon glyphicon-trash"></i>'), ['action' => 'delete', $user->id], ['class' => 'btn btn-danger btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'delete', 'confirm' => __('Are you sure you want to delete user with ID # {0}?', $user->id)]) ?>
                            <?php endif;?>
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