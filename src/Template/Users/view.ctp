<div>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit User'), array('action' => 'edit',$user->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
               
                    <table class="table table-bordered table-striped">
                                                                        <tr>
                            <th><?= __('Name') ?></th>
                            <td><?= h($user->name) ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Username') ?></th>
                            <td><?= h($user->username) ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Role') ?></th>
                            <td><?= h($user->role) ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Email') ?></th>
                            <td><?= h($user->email) ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Contact Details') ?></th>
                            <td><?= h($user->contact_details) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Branch') ?></th>
                            <td><?= $user->has('branch') ? $this->Html->link($user->branch->name, ['controller' => 'Branches', 'action' => 'view', $user->branch->id]) : '' ?></td>
                        </tr>
                                                                                                        <tr>
                            <th><?= __('Is Active') ?></th>
                            <td><?= $user->is_active ? __('Yes') : __('No'); ?></td>
                        </tr>
                                                    </table>
                            </div>
        </div>
    </div>
</div>