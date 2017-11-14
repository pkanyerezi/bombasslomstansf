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
                        <li><?= $this->Html->link(__('List Transaction'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Transaction Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Transaction Type'), ['controller' => 'TransactionTypes', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <button id="print-receipt" class="btn btn-primary" title="Print Receipt"><i class="glyphicon glyphicon-print"></i></button>
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-edit"></i>'), array('action' => 'edit',$transaction->id), array('class' => 'btn btn-primary', 'escape' => false,'title'=>'Edit Transaction')); ?>
                </div>
            </div>
            
            <script type="text/javascript">
                $(document).ready(function(){
                    $('#print-receipt').click(function(){
                        var prtContent = document.getElementById("receipt-section");
                        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

                        var receipt = prtContent.innerHTML;
                        receipt = '<link type="text/css" rel="stylesheet" href="<?=$this->request->webroot?>css/one.min.css" /><link type="text/css" rel="stylesheet" href="<?=$this->request->webroot?>css/desired-font.css" />' + receipt;

                        WinPrint.document.write(receipt);
                        WinPrint.document.close();
                        WinPrint.focus();
                        WinPrint.print();
                        WinPrint.close();
                    });
                });
            </script>
            <div id="receipt-section">
                <style type="text/css">
                    .receipt-body, .receipt-body table {font-size: 9px;line-height: 1;color: #333;}
                    .receipt-body table tr td{padding-right: 30px;}
                    .receipt-body table tr th, .receipt-body table tr td{line-height: 15px;text-align: left;}
                </style>
                <style type="text/css" media="print">
                    @page {size: auto;margin: 0;}
                    html {margin:0;}
                    body{padding: 0.25in 0.5in;}
                    .receipt-body{margin-top: 0px;}
                    .receipt-body {position:fixed;top:0px;}
                    #BrowserPrintDefaults{display:none} 
                </style>
                <center>
                    <div id="receipt-body" class="receipt-body">
                        <div class="row">
                            <?= __($appSettings->app_name) ?>, 
                            <?= $transaction->has('transaction_type') ? $transaction->transaction_type->name : '' ?><br>
                            <?= nl2br($transaction->has('branch') ? $transaction->branch->address : '') ?>
                            <br>
                            <?= __('PrintedOn: ') ?>
                            <?=$this->Html->formatTime($this->Time->format(date('Y-m-d H:i:s'),$authUser['time_format'],null,$authUser['time_zone']));?>
                        </div>
                        <table class="receipt-table">
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td><?= __('TransactionCode') ?></td>
                                <th><span style="border: 2px dotted;border-radius: 36%;padding: 5px"><?= $transaction->reference_code ?></span><br></th>
                            </tr>
                            <tr>
                                <td><?= __('Id') ?></td>
                                <th><?= h($transaction->id) ?></th>
                            </tr>

                            <tr>
                                <td><?= __('ServedBy') ?></td>
                                <th><?= $transaction->has('created_by') ? $transaction->created_by->name : '' ?></th>
                            </tr>
                           
                            
                            <?php if($transaction->has('customer')):?>
                                <tr>
                                    <td><?= __('SenderName') ?></td>
                                    <th><?= $transaction->has('customer') ? $transaction->customer->name : '' ?></th>
                                </tr>
                                <tr>
                                    <td><?= __('SenderPhone') ?></td>
                                    <th><?= h($transaction->customer->phone) ?></th>
                                </tr>
                            <?php endif;?>

                            <?php
                                $custom_fields_field = json_decode($transaction->custom_fields,true);
                                if (!empty($custom_fields_field)):
                                    foreach ($custom_fields_field as $key => $value):
                                        if (strtolower($key)=='commission') {
                                            continue;
                                        }
                            ?>
                                    <tr><td><?= __(ucwords(str_replace('_',' ',$key))) ?></td><th><?=h($value) ?></th></tr>
                            <?php 
                                    endforeach;
                                endif;
                            ?>

                            <tr>
                                <td><?= __((empty($transaction->transaction_type->amount_label)?'TotalAmount':'Total ' . $transaction->transaction_type->amount_label)) ?></td>
                                <th><?= $this->Number->format($transaction->value) ?></th>
                            </tr>

                            <?php foreach($childTransactions as $childTransaction):?>
                                <tr>
                                    <td><?= __($childTransaction->transaction_type->name) ?></td>
                                    <th><?= $this->Number->format($childTransaction->value) ?></th>
                                </tr>
                            <?php endforeach;?>

                            <tr>
                                <td><?= __('From') ?> </td>
                                <th>
                                <?= $transaction->has('from_branch') ? $transaction->from_branch->name : '' ?>
                                -
                                <?= $transaction->has('to_branch') ? $transaction->to_branch->name : '' ?>
                                </th>
                            </tr>

                            <tr>
                                <td><?= __('DateTime') ?></td>
                                <th>
                                    <?=$this->Html->formatTime($this->Time->format($transaction->date,$authUser['time_format'],null,$authUser['time_zone']));?>
                                </th>
                            </tr>
                            <tr>
                                <td><?= __('CustomerSign') ?></td>
                                <th>.....................................</th>
                            </tr>
                            <tr>
                                <td><?= __('TellerSign') ?></td>
                                <th>......................................</th>
                            </tr>
                            <?php if(!empty($transaction->system_comment) || !empty($transaction->user_comment)):?>
                            <tr>
                                <th><?= __('Comments') ?></th>
                                <td>
                                    <?= $this->Text->autoParagraph(h($transaction->system_comment)); ?>
                                    <?= $this->Text->autoParagraph(h($transaction->user_comment)); ?>
                                </td>
                            </tr>
                            <?php endif;?> 
                        </table>  

                        <div class="row"><br>
                            <h5><?= __($appSettings->app_name) ?>, <?= __('BranchContacts:') ?></h5>
                            <p>
                                <?= nl2br($appSettings->all_branches_contacts) ?>
                            </p>
                        </div>
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>