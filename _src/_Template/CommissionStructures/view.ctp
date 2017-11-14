

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
                        <li><?= $this->Html->link(__('List Commission Structure'), ['action' => 'index']) ?></li>
                        <li role="separator" class="divider"></li>                                <li><?= $this->Html->link(__('List Transaction Types'), ['controller' => 'TransactionTypes', 'action' => 'index']) ?></li>
                                <li><?= $this->Html->link(__('New Transaction Type'), ['controller' => 'TransactionTypes', 'action' => 'add']) ?></li>
                                              </ul>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit Commission Structure'), array('action' => 'edit',$commissionStructure->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
               
                    <table class="table table-bordered table-striped">
                                                                        <tr>
                            <th><?= __('Name') ?></th>
                            <td><?= h($commissionStructure->name) ?></td>
                        </tr>
                                                                                                                        <tr>
                            <th><?= __('Id') ?></th>
                            <td><?= $this->Number->format($commissionStructure->id) ?></td>
                        </tr>
                                        <tr>
                            <th><?= __('Transaction Type Id') ?></th>
                            <td><?= $this->Number->format($commissionStructure->transaction_type_id) ?></td>
                        </tr>
                                                                                                        <tr>
                            <th><?= __('Enabled') ?></th>
                            <td><?= $commissionStructure->enabled ? __('Yes') : __('No'); ?></td>
                        </tr>
                                                    </table>
                                                    <div class="row">
                        <h4><?= __('Description') ?></h4>
                        <?= $this->Text->autoParagraph(h($commissionStructure->description)); ?>
                    </div>
                                    <div class="row">
                        <h4><?= __('Pricing Structure') ?></h4>
                        <?= $this->Text->autoParagraph(h($commissionStructure->pricing_structure)); ?>
                    </div>
                                            </div>
        </div>
    </div>
</div>