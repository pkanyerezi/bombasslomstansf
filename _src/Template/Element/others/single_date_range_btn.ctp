<button type="button" class="btn btn-warning" id="daterange-btn">
    <span>
    <?php $defaultDate = isset($defaultDate)?$defaultDate:date('Y-m-d');?>
    <i class="glyphicon glyphicon-calendar"></i> <?=date('F d, Y',strtotime($defaultDate))?>
    </span>
    <i class="glyphicon glyphicon-caret-down"></i>
</button>
<?=$this->Html->script("moment.min")?>
<?=$this->Html->script("daterangepicker")?>
<?=$this->Html->css("daterangepicker")?>
<script type="text/javascript">
    $('#daterange-btn').daterangepicker(
    	{
	        singleDatePicker: true,
	        showDropdowns: true,
	        startDate: new Date('<?=$defaultDate?>')
	    }, 
	    function(start, end, label) {
	    	console.log(start);
	    	$('#new_date_to').val(start.format('YYYY-MM-DD'));
	    	$('#daterange-btn span').html('<i class="glyphicon glyphicon-calendar"></i> ' + start.format('MMMM D, YYYY'));
		}
	);
	// $('#daterange-btn').data('daterangepicker').setStartDate(new Date('2014-03-01'));
</script>