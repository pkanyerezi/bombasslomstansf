<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Accounts']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Accounts']); ?>
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
                    <li><?= $this->Html->link(__('New Account'), ['action' => 'add']) ?></li>
                    </ul>
                </div>
            </div>
            <div class="box-tools pull-right">
                <form>
                    <?php $defaultDate = (!empty($_GET['date_to']))?$_GET['date_to']:date('Y-m-d');?>
                    <?php echo $this->element('others/single_date_range_btn',['defaultDate'=>$defaultDate]); ?>
                    <?php foreach($_GET as $key=>$val):?>
                        <?php if(in_array($key, ['date_to','transaction_status_id'])) continue;?>
                        <input type="hidden" name="<?=$key?>" value="<?=$val?>">
                    <?php endforeach;?>
                    
                    <?php if(isset($appTransactionStatuses) && count($appTransactionStatuses)):?>
                        <?= $this->Form->select('transaction_status_id',$appTransactionStatuses,['class'=>'btn btn-warning','value'=>(!empty($_GET['transaction_status_id']))?$_GET['transaction_status_id']:'']) ?>
                    <?php endif;?>
                    
                    <input type="hidden" id="new_date_to" name="date_to" value="<?=$defaultDate?>">
                    <button class="btn btn-warning" type="submit"><i class="glyphicon glyphicon-refresh"></i></button>
                </form>
            </div>
        </div>
        
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Account</th>
                        <?php if($authUser['role']=='super_admin' && empty($_GET['created_by_id']) && empty($_GET['completed_by_id']) && empty($_GET['modified_by_id'])):?>
                        	<th>Initial</th>
                    	<?php endif;?>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accounts as $account): ?>
                        <tr>
                            <td>
                                <?php $transaction_status_id = $_GET['transaction_status_id']; ?>
                                <?php $statusName =  $appTransactionStatuses[$transaction_status_id]; ?>

                                <?= $this->Html->link(__($account->name), ['action' => 'index', '?'=> ['account_id'=> $account->id,'transaction_status_id'=>$transaction_status_id],'controller'=>'Transactions'],['escape'=>false,'data-toggle'=>'tooltip','title' => 'View  ' .  $statusName .' transactions for this account']) ?>        
                            </td>
                            <?php if($authUser['role']=='super_admin' && empty($_GET['created_by_id']) && empty($_GET['completed_by_id']) && empty($_GET['modified_by_id'])):?>
                            	<td><?php echo $this->Number->format($account->initial_balance) . ' ' . $account->currency_id;?> </td>
                            <?php else:?>
                            	<?php $account->initial_balance = 0;?>
                        	<?php endif;?>
                            <td><?php echo $this->Number->format($account->total_debit) . ' ' . $account->currency_id;?></td>
                            <td><?php  echo $this->Number->format($account->total_credit) . ' ' . $account->currency_id  ;?></td>
                            <td class="total_balance in <?=$account->currency_id?>" currency="<?=$account->currency_id?>" totalBalance="<?= (($account->total_credit + $account->initial_balance) - $account->total_debit); ?>"><?= $this->Number->format(($account->total_credit + $account->initial_balance) - $account->total_debit) . ' ' . $account->currency_id; ?></td>
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