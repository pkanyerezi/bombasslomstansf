

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
                        <li><?= $this->Html->link(__('List Account Type'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Accounts'), ['controller' => 'Accounts', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Account'), ['controller' => 'Accounts', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit Account Type'), array('action' => 'edit',$accountType->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
               
                    <table class="table table-bordered table-striped">
                                                                        <tr>
                            <th><?= __('Name') ?></th>
                            <td><?= h($accountType->name) ?></td>
                        </tr>
                                                                                                                        <tr>
                            <th><?= __('Id') ?></th>
                            <td><?= $this->Number->format($accountType->id) ?></td>
                        </tr>
                                                                                                        <tr>
                            <th><?= __('Enabled') ?></th>
                            <td><?= $accountType->enabled ? __('Yes') : __('No'); ?></td>
                        </tr>
                                                    </table>
                            </div>
        </div>
    </div>
</div>