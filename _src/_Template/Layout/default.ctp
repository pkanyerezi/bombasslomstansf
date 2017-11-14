<?php

$cakeDescription = __d('cake_dev', str_replace('/', '', $this->request->webroot));
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
            /*echo $this->Html->css([
                'bootstrap.min.css',
                '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css',
                'ionicons.min.css',
                '//fonts.googleapis.com/css?family=Droid+Serif:400,700,700italic,400italic',
                'CakeAdminLTE',
                'cake.css',
                //'base.css',
            ]);*/
            echo $this->Html->css(['one.min.css']);
            echo $this->Html->script('jquery.min');

            //echo $this->Html->script('libs/bootstrap.min');
            
        ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
    </head>

    <body class="skin-blue fixed">

        <?php if($online):?>
            <?php echo $this->element('menu/top_menu'); ?>
        <?php endif;?>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <?php if($online):?>
                <?php echo $this->element('menu/left_sidebar',['menuTransactionTypes'=>$menuTransactionTypes]); ?>
            <?php endif;?>
        
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">  
                <?php if($online):?>
                    
                    <section class="content-header">
                        <h1>
                            <?php echo $this->fetch('title'); ?>
                            <small>Control panel</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li><a href="<?=$this->request->webroot?>"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li><a href="<?=$this->request->webroot?><?=$this->request->params['controller']?>"><?=$this->request->params['controller']?></a></li>
                            <li class="active">
                                <a href="<?=$this->request->webroot?><?=$this->request->params['controller']?>/<?=$this->request->params['action']?>">
                                    <?=str_replace('_',' ',ucwords(strtolower($this->request->params['action'])));?>
                                </a>
                            </li>
                        </ol>
                    </section> 

                    <?php if(!in_array($this->request->params['action'],['add','edit','view'])):?>
                    <section class="content-header">
                      <?= $this->Form->create('Dashboards',['class'=>'date-ranger','type'=>'get']) ?>
                      <fieldset>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $this->Form->input('date_from_ranger',['label'=>'From','type'=>'date','value'=>$dateFrom]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input('date_to_ranger',['label'=>'To','type'=>'date','value'=>$dateTo]) ?>
                                <?php foreach($_GET as $key=>$val):?>
                                    <?php if(in_array($key, ['date_to_ranger','date_from_ranger','use_date_range'])) continue;?>
                                    <?= $this->Form->input($key,['type'=>'hidden','value'=>$val]) ?>
                                <?php endforeach;?>
                                <?= $this->Form->input('use_date_range',['type'=>'hidden','value'=>true]) ?>
                                <?= $this->Form->button(__('Go'),['class' => 'btn btn-xs btn-primary']); ?>
                            </div>
                            <div class="col-md-4"></div> 
                        </div> 
                      </fieldset>
                      <?= $this->Form->end() ?>
                    </section>
                    <?php endif;?>

                <?php endif;?>
                <section class="content"> 
                <?= $this->Flash->render() ?>
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
</html>