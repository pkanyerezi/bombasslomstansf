

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
                            <th><?= __('Id') ?></th>
                            <td><?= $this->Number->format($customer->id) ?></td>
                        </tr>
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
                        <?php
                            $custom_fields_field = json_decode($customer->custom_fields,true);
                            if (!empty($custom_fields_field)):
                                foreach ($custom_fields_field as $key => $value):
                        ?>
                                <tr><th><?= __(ucwords($key)) ?></th><th><?= __($value) ?></th></tr>
                        <?php 
                                endforeach;
                            endif;
                        ?>
                        <tr>
                            <th>Action</th>
                            <td>
                            <?= $this->Html->link(__('Balance Sheet'), ['controller'=>'Transactions','action' => 'balance','customer_id'=>$customer->id],['class'=>'btn btn-info btn-xs']) ?>
                            <?= $this->Html->link(__('Accounts Summary'), ['controller'=>'Accounts','action' => 'accountBalance','customer_id'=>$customer->id],['class'=>'btn btn-info btn-xs']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="<?=$this->request->webroot?>img/customers/identification/<?= h($customer->identification_img) ?>" target="_blank"><img src="<?=$this->request->webroot?>img/customers/identification/<?= h($customer->identification_img) ?>" alt="<?= h($customer->name) ?>">
                                <?= $this->Html->link(__('Edit ID/Passport'), ['controller'=>'Customers','action' => 'editImages',$customer->id,'identification'],['class'=>'btn btn-info btn-xs']) ?>
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