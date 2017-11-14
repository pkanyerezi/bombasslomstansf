<?php
    $transformedArray=[];
    foreach ($transactions as $value) {
        $transformedArray[$value->currency_id][]=$value;
    }

    $total_debit = 0;
    $total_credit = 0;
    $total_balance = 0;
    $previousSummaryArray = [];
    foreach ($previousSummary as $value) {

        if ((int)$value->balance_sheet_side==0) {
            $previousSummaryArray[$value->currency_id]['debit']=$value->total_value;
            if(!isset($previousSummaryArray[$value->currency_id]['credit'])){
                $previousSummaryArray[$value->currency_id]['credit']=0;
            }
        }elseif ((int)$value->balance_sheet_side==1) {
            $previousSummaryArray[$value->currency_id]['credit']=$value->total_value;
            if(!isset($previousSummaryArray[$value->currency_id]['debit'])){
                $previousSummaryArray[$value->currency_id]['debit']=0;
            }
        }

        $previousSummaryArray[$value->currency_id]['balance'] = 
        $previousSummaryArray[$value->currency_id]['credit'] - $previousSummaryArray[$value->currency_id]['debit'] ;
    }
?>
<div>
    <div class="box box-primary">
        <div class="box-body table-responsive">
            <?php foreach($transformedArray as $currency=>$transactions):?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                                    <th style="width:15%;"><?= $this->Paginator->sort('date') ?></th>
                                    <th style="width:10%;"><?= $this->Paginator->sort('id') ?></th>
                                    <th style="width:30%;"><?= $this->Paginator->sort('transaction_type_id') ?></th>
                                    <th style="text-align:right;">Debit</th>
                                    <th style="text-align:right;">Credit</th>
                                    <th style="text-align:right;">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php
                            $total_debit = 0;
                            $total_credit = 0;
                            $total_balance = 0;
                        ?>

                        <?php if($previousSummary->count()):?>
                            <?php foreach($previousSummaryArray as $currency=>$record):?>
                                <tr>
                                    <td>Previous</th>
                                    <td>---&nbsp;</th>
                                    <td>ALL&nbsp;</th>
                                    <td style="text-align:right;"><?=$this->Number->format($record['debit']) . ' ' . $currency?></td>
                                    <td style="text-align:right;"><?=$this->Number->format($record['credit']) . ' ' . $currency?></td>
                                    <td style="text-align:right;"><?=$this->Number->format($record['balance']) . ' ' . $currency?></td>
                                </tr>
                                <?php
                                    $total_debit += $record['debit'];
                                    $total_credit += $record['credit'];
                                    $total_balance += $record['credit']-$record['debit'];
                                ?>
                            <?php endforeach;?>
                        <?php endif;?>

                        <?php foreach ($transactions as $transaction): ?>
                        <?php $debit = 0;$credit = 0;?>
                        <tr>
                            <td><?=$this->Html->formatTime($this->Time->format($transaction->date,$authUser['time_format'],null,$authUser['time_zone']));?></td>
                            <td><?= $this->Number->format($transaction->id) ?></td>
                            <td><?= $transaction->has('transaction_type') ? $this->Html->link($transaction->transaction_type->name, ['controller' => 'TransactionTypes', 'action' => 'view', $transaction->transaction_type->id]) : '' ?></td>
                            <td style="text-align:right;">
                                <?php
                                    if ((int)$transaction->balance_sheet_side==0) {
                                        $debit = $transaction->value;
                                        $total_debit +=$transaction->value;
                                        echo $this->Number->format($transaction->value) . ' ' . $currency;
                                    }else{
                                        echo 0;
                                    }
                                ?>
                            </td>
                            <td style="text-align:right;">
                                <?php
                                    if ((int)$transaction->balance_sheet_side==1) {
                                        $credit = $transaction->value;
                                        $total_credit +=$transaction->value;
                                        echo $this->Number->format($transaction->value) . ' ' . $currency;
                                    }else{
                                        echo 0;
                                    }
                                ?>
                            </td>
                            <td style="text-align:right;">
                                <?php echo $total_balance +=$credit-$debit;?> <?=$currency?>
                                    </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <td style="border: 2px solid #777;border-right: 0px;border-left: 0px;text-align:right;"><?=$this->Number->format($total_debit)?> <?=$currency?></td>
                            <td style="border: 2px solid #777;border-left: 0px;border-right: 0px;text-align:right;"><?=$this->Number->format($total_credit)?> <?=$currency?></td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;"><span> Ledger Balance </span></td>
                            <td style="text-align:right;"><?=$this->Number->format($total_balance)?> <?=$currency?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach;?>
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



<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Transactions Statement']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Transactions Statement']); ?>