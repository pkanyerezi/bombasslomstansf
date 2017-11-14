<?php

@$cakeDescription = __d('cake_dev', (isset($appSettings->app_name)?$appSettings->app_name:''));
?>
<?php echo $this->Html->docType('html5'); ?> 
<html>
    <head>
        <?= $this->Html->charset() ?>
       <?=$this->Html->meta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no']);?>
        <title>
            <?= $cakeDescription ?>:
            <?= $this->fetch('title') ?>
        </title>
        <?= $this->Html->meta('icon') ?>

        <?php
            echo $this->Html->css(['one.min.css','_all-skins.min.css','desired-font.css']);
            echo $this->Html->script('jquery.min');
        ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
    </head>

    <body class="<?=(isset($appSettings->app_theme)?$appSettings->app_theme:'skin-blue');?> fixed">

        <?php if($online):?>
            <?php echo $this->element('menu/top_menu'); ?>
        <?php endif;?>
        <div class="<?=($online?'wrapper row-offcanvas row-offcanvas-left':'')?>">
            <?php if($online):?>
                <?php echo $this->element('menu/left_sidebar',['menuTransactionTypes'=>$menuTransactionTypes]); ?>
            <?php endif;?>
        
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="<?=($online?'right-side':'')?>" style="<?=(!$online?'margin-top: 5%;':'')?>">  
                <?php if($online):?>
                    
                    <section class="content-header">
                        <h1>
                            <a href="<?=$this->request->webroot?><?php echo $this->fetch('title'); ?>"><?php echo $this->fetch('title'); ?></a>
                        </h1>

                       

                        <ol class="breadcrumb" style="top: 10px;">
                            <li><a href="<?=$this->request->webroot?>"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li><a href="<?=$this->request->webroot?><?=$this->request->params['controller']?>"><?=$this->request->params['controller']?></a></li>
                            <li class="active">
                                <a href="<?=$this->request->webroot?><?=$this->request->params['controller']?>/<?=$this->request->params['action']?>">
                                    <?=str_replace('_',' ',ucwords(strtolower($this->request->params['action'])));?>
                                </a>
                            </li>
                        </ol>
                    </section> 

                    <?php if(in_array($this->request->params['controller'], ['Transactions']) && !in_array($this->request->params['action'],['add','edit','view','receipt'])):?>
                    <section class="content-header">
                        <div class="row">
                            <div class="col-md-12">
								<button class="btn btn-default pull-left" onClick="window.print()"><i class="glyphicon glyphicon-print"></i></button>
                                <div style="text-align:center;">
                                <form class="date-ranger">
                                    <?php $defaultDate = (!empty($_GET['date_to']))?$_GET['date_to']:date('Y-m-d');?>
                                    <?php echo $this->element('others/double_date_range_btn',['defaultStartDate'=>$dateFrom,'defaultEndDate'=>$dateTo]); ?>
                                    <?php foreach($_GET as $key=>$val):?>
                                        <?php if(in_array($key, ['date_to','transaction_status_id'])) continue;?>
                                        <input type="hidden" name="<?=$key?>" value="<?=$val?>">
                                    <?php endforeach;?>
                                    
                                    <?php if(isset($appTransactionStatuses) && count($appTransactionStatuses)):?>
                                        <?= $this->Form->select('transaction_status_id',$appTransactionStatuses,['class'=>'btn btn-warning','value'=>(!empty($_GET['transaction_status_id']))?$_GET['transaction_status_id']:'']) ?>
                                    <?php endif;?>
                                    <?php if(isset($appTransactionCurrencies) && count($appTransactionCurrencies)):?>
                                        <?= $this->Form->select('currency_id',$appTransactionCurrencies,['class'=>'btn btn-warning','value'=>(!empty($_GET['currency_id']))?$_GET['currency_id']:'']) ?>
                                    <?php endif;?>
                                    
                                    <input type="hidden" id="new_date_from" name="date_from_ranger" value="<?=$dateFrom?>">
                                    <input type="hidden" id="new_date_to" name="date_to_ranger" value="<?=$dateTo?>">
                                    <input type="hidden" id="use_date_range" name="use_date_range" value="1">
                                    <button class="btn btn-warning" type="submit"><i class="glyphicon glyphicon-refresh"></i></button>
                                </form>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php endif;?>

                <?php endif;?>
                <section class="container content" style="min-height: 700px;"> 
                <?= $this->Flash->render() ?>
                <?= $this->Flash->render('auth') ?>
                <?php echo $this->fetch('content'); ?>
                </section>
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <?php
           echo $this->Html->script('one.min');
           // echo $this->Html->script('bootstrap.min');
           //echo $this->Html->script('CakeAdminLTE/app',['async'=>true]);
        ?>
        <?= $this->fetch('script') ?>
    </body>
    <style type="text/css">
        .row{margin-left: 0px;}
    </style>
	<script type="text/javascript">
     $('form').on('submit',function(e){
        var $form = $(this);
		$form.find(':submit').attr('disabled','disabled');
        if ($form.data('submitted') === true) {
          // Previously submitted - don't submit again
          e.preventDefault();
        } else {
          // Mark it so that the next submit can be ignored
          $form.data('submitted', true);
        }
      });
    </script>
    <?php if(isset($offline) && $offline && !empty($authUser['role']) && in_array($authUser['role'], ['super_admin'])):?>    
        <script type="text/javascript">
            var syncingTransactions = false;
            var syncURL = '<?=$this->request->webroot?>transactions-api/sendOfflineOnline';

            function syncTransactions(){
                if(!syncingTransactions){
                    syncingTransactions = true;
                    $.ajax({
                        url: syncURL,
                        dataType: 'json',
                        error: function(){
                            syncingTransactions = false;
                        },
                        complete:function(resp){
                            syncingTransactions = false;
                        },
                    });
                }
            }

            setInterval(syncTransactions, 10000);
        </script>
    <?php endif;?>
</html>