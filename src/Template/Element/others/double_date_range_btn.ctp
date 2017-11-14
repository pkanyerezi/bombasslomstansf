<button type="button" class="btn btn-warning" id="daterange-btn">
    <span>
      <?php $defaultStartDate = isset($defaultStartDate)?$defaultStartDate:date('Y-m-d');?>
      <?php $defaultEndDate = isset($defaultEndDate)?$defaultEndDate:date('Y-m-d');?>
      <i class="glyphicon glyphicon-calendar"></i>  
      <?=date('F d, Y',strtotime($defaultStartDate))?> - <?=date('F d, Y',strtotime($defaultEndDate))?>
    </span>
    <i class="glyphicon glyphicon-caret-down"></i>
</button>
<?=$this->Html->script("moment.min")?>
<?=$this->Html->script("daterangepicker")?>
<?=$this->Html->css("daterangepicker")?>
<script type="text/javascript">
  //Date range as a button
  $('#daterange-btn').daterangepicker(
      {
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: new Date('<?=$defaultStartDate?>'),
        endDate: new Date('<?=$defaultEndDate?>')
      },
      function (start, end) {
        $('#daterange-btn span').html('<i class="glyphicon glyphicon-calendar"></i>' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#new_date_from').val(start.format('YYYY-MM-DD'));
        $('#new_date_to').val(end.format('YYYY-MM-DD'));
      }
  );
</script>