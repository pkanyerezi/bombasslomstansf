<?php 
    $accountsList = [];
    $accountCurrenciesFound = [];
    foreach ($accounts as $account){
        @$debit = $accountsList[''.$account->from_account->name]['debit'];
        @$credit = $accountsList[''.$account->to_account->name]['credit'];

        $debit = ($debit)?$debit:0;
        $credit = ($credit)?$credit:0;


        $accountsList[''.$account->from_account->name]['debit'] = $debit + $account->amount_from;
        $accountsList[''.$account->to_account->name]['credit'] = $credit + $account->amount_from;

        $accountsList[''.$account->from_account->name]['name'] = $account->from_account->name;
        $accountsList[''.$account->from_account->name]['currency'] = $account->from_account->currency_id;
        $accountsList[''.$account->from_account->name]['branch'] = $account->from_account->branch_id;
        $accountsList[''.$account->from_account->name]['id'] = $account->from_account->id;

        $accountsList[''.$account->to_account->name]['name'] = $account->to_account->name;
        $accountsList[''.$account->to_account->name]['currency'] = $account->to_account->currency_id;
        $accountsList[''.$account->to_account->name]['branch'] = $account->to_account->branch_id;
        $accountsList[''.$account->to_account->name]['id'] = $account->to_account->id;

        if (!in_array($account->to_account->currency_id, $accountCurrenciesFound)) {
            $accountCurrenciesFound[] = $account->to_account->currency_id;
        }
        if (!in_array($account->from_account->currency_id, $accountCurrenciesFound)) {
            $accountCurrenciesFound[] = $account->from_account->currency_id;
        }
    }
?>
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
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total_debit = 0;
                        $total_credit = 0;
                    ?>
                    <?php foreach ($accountsList as $account): ?>
                        <?php if($account['branch']!=$branch_id) continue;?>
                        <tr>
                            <td>
                                <?php $name =  h((is_array($account))?$account['name']:$account); ?>
                                <?php $transaction_status_id = $_GET['transaction_status_id']; ?>
                                <?php $statusName =  $appTransactionStatuses[$transaction_status_id]; ?>

                                <?= $this->Html->link(__($name), ['action' => 'index', '?'=> ['account_id'=> $account['id'],'transaction_status_id'=>$transaction_status_id],'controller'=>'Transactions'],['escape'=>false,'data-toggle'=>'tooltip','title' => 'View  ' .  $statusName .' transactions for this account']) ?>        
                            </td>
                            <td>
                                <?php
                                    $debit = 0;
                                    if (isset($account['debit'])) {
                                        $debit = $account['debit'];
                                    }
                                    $total_debit +=$debit; 
                                    echo $this->Number->format($debit) . ' ' . $account['currency'];
                                ?>
                            </td>
                            <td>
                                <?php
                                    $credit = 0;
                                    if (isset($account['credit'])) {
                                        $credit = $account['credit'];
                                    }
                                    $total_credit += $credit;
                                    echo $this->Number->format($credit) . ' ' . $account['currency']  ;
                                ?>
                            </td>
                            <td class="total_balance <?=$account['currency']?>" currency="<?=$account['currency']?>" totalBalance="<?= ($credit - $debit); ?>"><?= $this->Number->format($credit - $debit) . ' ' . $account['currency']; ?></td>
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