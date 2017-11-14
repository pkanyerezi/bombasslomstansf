

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
                        <li><?= $this->Html->link(__('List Customers'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Branches'), ['controller' => 'Branches', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Branch'), ['controller' => 'Branches', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit Customer'), array('action' => 'edit',$customer->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="col-md-8">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th><?= __('Branch') ?></th>
                            <td><?= $customer->has('branch') ? $this->Html->link($customer->branch->name, ['controller' => 'Branches', 'action' => 'view', $customer->branch->id]) : '' ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <td><?= h($customer->name) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Phone') ?></th>
                            <td><?= h($customer->phone) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Email') ?></th>
                            <td><?= h($customer->email) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Identity') ?></th>
                            <td><?= h($customer->identity) ?> (<?= h($customer->identity_type) ?>)</td>
                        </tr>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <td><?= $this->Number->format($customer->id) ?></td>
                        </tr>
                        <tr>
                            <th>Action</th>
                            <td>
                            <a href="<?=$this->request->webroot?>transactions/balance?customer_id=<?=$customer->id?>" class="btn btn-info btn-xs">Balance Sheet</a>
                            <a href="<?=$this->request->webroot?>accounts/accountBalance?customer_id=<?=$customer->id?>" class="btn btn-info btn-xs">Accounts Summary</a>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-4">
                    <div class="list-group">
                        <a href="#" class="list-group-item active">
                            Create Transactions
                        </a>
                        <?php if($transactionTypes->count()):?>
                            <?php foreach($transactionTypes as $key=>$transactionType):?>
                                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus-sign"></i> Add ' . $transactionType), array('action' => 'add',$key,'controller'=>'Transactions','entity_id'=>$customer->id), array('class' => 'list-group-item','escape' => false)); ?>
                            <?php endforeach;?>
                        <?php endif;?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>