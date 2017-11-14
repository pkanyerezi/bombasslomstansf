

<div>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-left">
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Actions
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><?= $this->Html->link(__('List Role Permission'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Role'), ['controller' => 'Roles', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit Role Permission'), array('action' => 'edit',$rolePermission->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
               
                    <table class="table table-bordered table-striped">
                                                                        <tr>
                            <th><?= __('Role') ?></th>
                            <td><?= $rolePermission->has('role') ? $this->Html->link($rolePermission->role->title, ['controller' => 'Roles', 'action' => 'view', $rolePermission->role->id]) : '' ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Controller') ?></th>
                            <td><?= h($rolePermission->controller) ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Action') ?></th>
                            <td><?= h($rolePermission->action) ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Description') ?></th>
                            <td><?= h($rolePermission->description) ?></td>
                        </tr>
                                                                                                                        <tr>
                            <th><?= __('Id') ?></th>
                            <td><?= $this->Number->format($rolePermission->id) ?></td>
                        </tr>
                                                                                                        <tr>
                            <th><?= __('Enabled') ?></th>
                            <td><?= $rolePermission->enabled ? __('Yes') : __('No'); ?></td>
                        </tr>
                                                    </table>
                            </div>
        </div>
    </div>
</div>