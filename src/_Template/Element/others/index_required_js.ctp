<?php
    echo $this->Html->script('jquery.min');
    echo $this->Html->script('plugins/datatables/jquery.dataTables');
    echo $this->Html->script('plugins/datatables/dataTables.bootstrap');
?>
<script type="text/javascript">
    $(function() {
        $("#<?php echo str_replace(' ', '', $pluralHumanName); ?>").dataTable();
    });
</script>