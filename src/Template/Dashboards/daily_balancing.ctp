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
	
	<div class="row">
		<div class="col-md-12">
			<div style="text-align:right;">
			<form class="date-ranger">
				<?php $defaultDate = (!empty($_GET['date_to']))?$_GET['date_to']:date('Y-m-d');?>
				<?php echo $this->element('others/double_date_range_btn',['defaultStartDate'=>$dateFrom,'defaultEndDate'=>$dateTo]); ?>
				<?php foreach($_GET as $key=>$val):?>
					<?php if(in_array($key, ['date_to','transaction_status_id'])) continue;?>
					<input type="hidden" name="<?=$key?>" value="<?=$val?>">
				<?php endforeach;?>
				
				<?php if(isset($appTransactionStatuses) && count($appTransactionStatuses)):?>
					<?php 
						$statuses = [0=>'All'];
						foreach($appTransactionStatuses as $id=>$status){
							$statuses[$id] = $status;
						}
					?>
					<?= $this->Form->select('transaction_status_id',$statuses,['class'=>'btn btn-warning','value'=>(!empty($_GET['transaction_status_id']))?$_GET['transaction_status_id']:'']) ?>
				<?php endif;?>
				
				<?php if(isset($branches) && count($branches)):?>
					<?php 
						$bras = [0=>'All'];
						foreach($branches as $id=>$bra){
							$bras[$id] = $bra;
						}
					?>
					<?= $this->Form->select('branch_id',$bras,['class'=>'btn btn-warning','value'=>(!empty($_GET['branch_id']))?$_GET['branch_id']:'']) ?>
				<?php endif;?>
				
				<input type="hidden" id="new_date_from" name="date_from_ranger" value="<?=$dateFrom?>">
				<input type="hidden" id="new_date_to" name="date_to_ranger" value="<?=$dateTo?>">
				<input type="hidden" id="use_date_range" name="use_date_range" value="1">
				<button class="btn btn-warning" type="submit"><i class="glyphicon glyphicon-refresh"></i></button>
			</form>
			</div>
		</div>
	</div>
	
	<h4>Total Transactions Sent Today</h4>
	<div class="row">
		<div class="col-sm-10">
		<?php
			echo '<table class="table">';
				echo '<tr><th>Currency</th><th>Amount</th><th>Count</th></tr>';
			foreach($totalTransactionsSentToday as $transaction){
				echo '<tr><td>'.$transaction->currency_id.'</td><td>'.$this->Number->format($transaction->total_amount).'</td><td>'.$this->Number->format($transaction->total_transactions).'</td></tr>';
			}
			echo '</table>';
		?>
		</div>
		<div class="col-sm-2">
		<?php
			echo '<table class="table">';
				echo '<tr><th>Commission</th></tr>';
			foreach($totalTransactionsSentTodayCommission as $transaction){
				echo '<tr><td>'.$this->Number->format($transaction->total_amount). ' ' . $transaction->currency_id.'</td></tr>';
			}
			echo '</table>';
		?>
		</div>
	</div>
	
	<h4>Total Transactions Paid Out Today</h4>
	<div class="row">
		<div class="col-sm-10">
		<?php
			echo '<table class="table">';
				echo '<tr><th>Currency</th><th>Amount</th><th>Count</th></tr>';
			foreach($totalTransactionsPaidOutToday as $transaction){
				echo '<tr><td>'.$transaction->currency_id.'</td><td>'.$this->Number->format($transaction->total_amount).'</td><td>'.$this->Number->format($transaction->total_transactions).'</td></tr>';
			}
			echo '</table>';
		?>
		</div>
		<div class="col-sm-2">
		<?php
			echo '<table class="table">';
				echo '<tr><th>Commission</th></tr>';
			foreach($totalTransactionsPaidOutTodayCommission as $transaction){
				echo '<tr><td>'.$this->Number->format($transaction->total_amount). ' ' . $transaction->currency_id.'</td></tr>';
			}
			echo '</table>';
		?>
		</div>
	</div>
	
	<h4>Total Transactions Paid Out Today For Previous</h4>
	<div class="row">
		<div class="col-sm-10">
		<?php
			echo '<table class="table">';
				echo '<tr><th>Currency</th><th>Amount</th><th>Count</th></tr>';
			foreach($totalTransactionsPaidOutTodayForPrevious as $transaction){
				echo '<tr><td>'.$transaction->currency_id.'</td><td>'.$this->Number->format($transaction->total_amount).'</td><td>'.$this->Number->format($transaction->total_transactions).'</td></tr>';
			}
			echo '</table>';
		?>
		</div>
		<div class="col-sm-2">
		<?php
			echo '<table class="table">';
				echo '<tr><th>Commission</th></tr>';
			foreach($totalTransactionsPaidOutTodayForPreviousCommission as $transaction){
				echo '<tr><td>'.$this->Number->format($transaction->total_amount). ' ' . $transaction->currency_id.'</td></tr>';
			}
			echo '</table>';
		?>
		</div>
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