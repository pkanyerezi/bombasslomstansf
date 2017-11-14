

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
                        <li><?= $this->Html->link(__('List Role'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Transaction Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Transaction Type'), ['controller' => 'TransactionTypes', 'action' => 'add']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit Role'), array('action' => 'edit',$role->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
               
                    <table class="table table-bordered table-striped">
                                                                        <tr>
                            <th><?= __('Title') ?></th>
                            <td><?= h($role->title) ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Alias') ?></th>
                            <td><?= h($role->alias) ?></td>
                        </tr>
                                                                                                                        <tr>
                            <th><?= __('Id') ?></th>
                            <td><?= $this->Number->format($role->id) ?></td>
                        </tr>
                                                                                        <tr>
                            <th><?= __('Created') ?></th>
                            <td><?= h($role->created) ?></td>
                        </tr>
                                        <tr>
                            <th><?= __('Modified') ?></th>
                            <td><?= h($role->modified) ?></td>
                        </tr>
                                                                    </table>
                            </div>
        </div>
    </div>
</div>