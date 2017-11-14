

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
                        <li><?= $this->Html->link(__('List Accounts'), ['action' => 'index']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit Account'), array('action' => 'edit',$account->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="col-md-8">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th><?= __('Name') ?></th>
                            <td><?= h($account->name) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('AccountType') ?></th>
                            <td><?= h($account->account_type->name) ?></td>
                        </tr>

                        <tr>    
                            <th><?= __('Id') ?></th>
                            <td><?= $this->Number->format($account->id) ?></td>
                        </tr>
                        
                        <tr>
                            <th><?= __('Created') ?></th>
                            <td><?=$this->Time->format($account->created,$authUser['time_format'],null,$authUser['time_zone']);?></td>
                        </tr>
                                        <tr>
                            <th><?= __('Modified') ?></th>
                            <td><?=$this->Time->format($account->modified,$authUser['time_format'],null,$authUser['time_zone']);?></td>
                        </tr>
                                                                                        <tr>
                            <th><?= __('Enabled') ?></th>
                            <td><?= $account->enabled ? __('Yes') : __('No'); ?></td>
                        </tr>
                    </table>
                    <div class="row">
                        <h4><?= __('Description') ?></h4>
                        <?= $this->Text->autoParagraph(h($account->description)); ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="list-group">
                        <a href="#" class="list-group-item active">
                            Create <?= h($account->account_type->name) ?> Transactions
                        </a>
						<?= $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> List Transactions with this account'), ['action' => 'index', '?'=> ['account_id'=> $account->id],'controller'=>'Transactions'],['class' => 'list-group-item','escape'=>false,'data-toggle'=>'tooltip','title' => 'view transactions with this account']) ?>
                        <?php if($transactionTypes->count()):?>
                            <?php foreach($transactionTypes as $key=>$transactionType):?>
                                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> Add' . $transactionType), array('action' => 'add',$key,'controller'=>'Transactions','account'=>$account->id), array('class' => 'list-group-item', 'escape' => false)); ?>
                            <?php endforeach;?>
                        <?php endif;?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>