
<div>
    <div class="box box-primary">
        <div class="box-header">
            <div class="box-tools pull-left">
                <div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Actions
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li><?= $this->Html->link(__('New Role Permission'), ['action' => 'add']) ?></li>
                    <li role="separator" class="divider"></li>                            <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?></li>
                            <li><?= $this->Html->link(__('New Role'), ['controller' => 'Roles', 'action' => 'add']) ?></li>
                                      </ul>
                </div>
            </div>
            <div class="box-tools pull-right">
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> New Role Permission'), array('action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                                <th><?= $this->Paginator->sort('id') ?></th>
                                <th><?= $this->Paginator->sort('role_id') ?></th>
                                <th><?= $this->Paginator->sort('controller') ?></th>
                                <th><?= $this->Paginator->sort('action') ?></th>
                                <th><?= $this->Paginator->sort('enabled') ?></th>
                                <th><?= $this->Paginator->sort('description') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rolePermissions as $rolePermission): ?>
                    <tr>
                                <td><?= $this->Number->format($rolePermission->id) ?></td>
                                <td><?= $rolePermission->has('role') ? $this->Html->link($rolePermission->role->title, ['controller' => 'Roles', 'action' => 'view', $rolePermission->role->id]) : '' ?></td>
                                <td><?= h($rolePermission->controller) ?></td>
                                <td><?= h($rolePermission->action) ?></td>
                                <td><?= h($rolePermission->enabled) ?></td>
                                <td><?= h($rolePermission->description) ?></td>
                                <td class="actions">
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), ['action' => 'view', $rolePermission->id],['class' => 'btn btn-primary btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'view']) ?>
                            <?= $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i>'), ['action' => 'edit', $rolePermission->id],['class' => 'btn btn-warning btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'edit']) ?>
                            <?= $this->Form->postLink(__('<i class="glyphicon glyphicon-trash"></i>'), ['action' => 'delete', $rolePermission->id], ['class' => 'btn btn-danger btn-xs','escape'=>false,'data-toggle'=>'tooltip','title' => 'delete', 'confirm' => __('Are you sure you want to delete rolePermission with ID # {0}?', $rolePermission->id)]) ?>
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



<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Role Permissions']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Role Permissions']); ?>