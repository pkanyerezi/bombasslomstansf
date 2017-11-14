<?php 
    $accountsList = [];
    foreach ($accounts as $account){
        @$debit = $accountsList[''.$account->from_account->name]['debit'];
        @$credit = $accountsList[''.$account->to_account->name]['credit'];

        $debit = ($debit)?$debit:0;
        $credit = ($credit)?$credit:0;


        $accountsList[''.$account->from_account->name]['debit'] = $debit + $account->amount_from;
        $accountsList[''.$account->to_account->name]['credit'] = $credit + $account->amount_from;

        $accountsList[''.$account->from_account->name]['name'] = $account->from_account->name;
        $accountsList[''.$account->to_account->name]['name'] = $account->to_account->name;
    }
?>

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
                <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-plus"></i> New Account'), array('action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
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
                        <tr>
                            <td><?= h((is_array($account))?$account['name']:$account) ?></td>
                            <td>
                                <?php
                                    $debit = 0;
                                    if (isset($account['debit'])) {
                                        $debit = $account['debit'];
                                    }
                                    $total_debit +=$debit; 
                                    echo $debit;
                                ?>
                            </td>
                            <td>
                                <?php
                                    $credit = 0;
                                    if (isset($account['credit'])) {
                                        $credit = $account['credit'];
                                    }
                                    $total_credit += $credit; 
                                    echo $credit;
                                ?>
                            </td>
                            <td><?= $credit - $debit ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <!-- 
                        <tr>
                            <td style="text-align:right;">Total</td>
                            <td><?=$this->Number->format($total_debit)?></td>
                            <td><?=$this->Number->format($total_credit)?></td>
                            <td><?=$this->Number->format($total_credit - $total_debit)?></td>
                        </tr> 
                    -->
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



<?php echo $this->element('others/index_required_css',['pluralHumanName'=>'Accounts']); ?>
<?php echo $this->element('others/index_required_js',['pluralHumanName'=>'Accounts']); ?>