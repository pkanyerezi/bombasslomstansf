
<div>
    <div class="col-xs-6 col-xs-offset-3">
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
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-th"></i> List Commission Structure'), array('action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->Form->create($commissionStructure,['class'=>'form']) ?>
                <fieldset>
                    <legend><?= __('Add Commission Structure') ?></legend>
                    <?php
                        echo $this->Form->input('name',['class'=>'form-control']);
                        echo $this->Form->input('description',['type'=>'text','class'=>'form-control']);
                        echo $this->Form->input('transaction_type_id',['class'=>'form-control']);
                        echo $this->Form->input('enabled',['class'=>'form-control']);
                    ?>
                    <br>
                    <label>Pricing Structure</label>
                    <table class="table my-price-options">
                        <tr>
                            <td>Min-Price</td>
                            <td>Max-Price</td>
                            <td>Comm-Amount</td>
                            <td>Comm-Perc(%)</td>
                            <td></td>
                        </tr>
                    </table>
                    <span class="btn btn-default btn-xs add-po"><i class="glyphicon glyphicon-plus-sign"></i> add</span>

                </fieldset>
                <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.remove-po').click(function(){$('.'+($(this).attr('id'))).remove();});
        $('.add-po').click(function(){
            var counter=$('.remove-po').length;
            if(counter>8) return false;
            var body = '<tr class="po'+(counter+2)+'">';
                body += '<td><input class="form-control" type="text" name="pricing_structure[min_price][]" value="0" /></td>';
                body += '<td><input class="form-control" type="text" name="pricing_structure[max_price][]" value="0" /></td>';
                body += '<td><input class="form-control" type="text" name="pricing_structure[comm_amount][]" value="0" /></td>';
                body += '<td><input class="form-control" type="text" name="pricing_structure[comm_perc][]" value="0" /></td>';
                body += '<td><span onclick="$(\'.\'+($(this).attr(\'id\'))).remove();" style="cursor:pointer;line-height: 35px;" class="remove-po" id="po'+(counter+2)+'"><i class="glyphicon glyphicon-trash"></i> </span></td>';
                body += '</tr>';
            $('.my-price-options').append(body);
        });
    });
</script>