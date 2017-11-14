

<div>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('<i class="glyphicon glyphicon-pencil"></i> Edit Currency'), array('action' => 'edit',$currency->id), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
               
                    <table class="table table-bordered table-striped">
                                                                        <tr>
                            <th><?= __('Id') ?></th>
                            <td><?= h($currency->id) ?></td>
                        </tr>
                                                                        <tr>
                            <th><?= __('Name') ?></th>
                            <td><?= h($currency->name) ?></td>
                        </tr>
                                                                                                                        <tr>
                            <th><?= __('Priority') ?></th>
                            <td><?= $this->Number->format($currency->priority) ?></td>
                        </tr>
                                                                                    </table>
                            </div>
        </div>
    </div>
</div>