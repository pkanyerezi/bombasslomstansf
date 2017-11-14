<div>
	<?php if(!empty($unSyncedTransactionsCount)):?>
		<div class="alert alert-warning alert-dismissible">
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		    <h4>(<?=$unSyncedTransactionsCount?>) Transactions not synched yet!</h4>
		</div>
	<?php endif;?>
	<div class="row">
		<?php $pieChartDataCommissionsPerServiceSold = [];?>
		<?php $colors    = ['605ca8','dd4b39','00a65a','00c0ef','f39c12','111111'];?>
		<?php $counter=0;?>
		
		<?php $total = 0;?>
		<?php foreach($stats as $stat):?>
			<?php $total +=$stat->total_transactions;?>
		<?php endforeach?>

		<?php foreach($stats as $stat):?>
			<?php @$percetage = round(($stat->total_transactions/$total)*100);?>
			<?php 
				if(!isset($colors[$counter])) $counter=0;											
				$_color = $colors[$counter];
			?>
			<div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon" style="background-color: #<?=$_color?> !important; color:#fff">
	            	<small><small><?=$percetage?>%</small></small>
	            </span>

	            <div class="info-box-content">
	              <span class="info-box-text">
	              	<?= $this->Html->link(__( $appTransactionStatuses[$stat->transaction_status_id] .
	              	 ' Transactions'), ['controller'=>'Transactions','action' => 'index', 'transaction_status_id'=>$stat->transaction_status_id],['style'=>'color:inherit;','escape'=>false]) ?>
	              </span>
	              <span class="info-box-number">
	              	<?=$this->Number->format($stat->total_transactions)?>
	              </span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
			<?php $counter++;?>
		<?php endforeach?>
	</div>
</div>
<style type="text/css">
	.content, .container, .content-header{
		width: 100%;
	}
	.info-box {
	    display: block;
	    min-height: 90px;
	    background: #fff;
	    width: 100%;
	    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
	    border-radius: 2px;
	    margin-bottom: 15px;
	}
	.info-box-icon {
	    border-top-left-radius: 2px;
	    border-top-right-radius: 0;
	    border-bottom-right-radius: 0;
	    border-bottom-left-radius: 2px;
	    display: block;
	    float: left;
	    height: 90px;
	    width: 90px;
	    text-align: center;
	    font-size: 45px;
	    line-height: 90px;
	    background: rgba(0,0,0,0.2);
	}
	.info-box-content {
	    padding: 5px 10px;
	    margin-left: 90px;
	}
	.info-box-text {
	    display: block;
	    font-size: 14px;
	    white-space: nowrap;
	    overflow: hidden;
	    text-overflow: ellipsis;
	}
	.info-box-number {
	    display: block;
	    font-weight: bold;
	    font-size: 18px;
	}
</style>