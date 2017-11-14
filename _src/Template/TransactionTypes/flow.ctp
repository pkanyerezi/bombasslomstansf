<?php 
echo $this->Html->css(['jquery.flowchart.css']);
echo $this->Html->script(['jquery-ui.min']);
?>

<?php
  $data = ['operators'=>[],'links'=>[]];
  foreach($transactionTypes as $key=>$transaction_type){
    $data['operators']['operator'.$transaction_type->id] = [
      'top'=>20 + ($key*50),
      'left'=>20 + ($key*50),
      'properties'=>[
        'title'=>$transaction_type->name,
        'inputs'=>[
          'input_1'=>[
            'label'=> $transaction_type->from_account->name
          ]
        ],
        'outputs'=>[
          'output_1'=>[
            'label'=> $transaction_type->to_account->name
          ]
        ]
      ]
    ];
    
    if(!empty($transaction_type->linked_transaction_type_id)){
      $data['links']['link_'.$transaction_type->id] = [
        'fromOperator'=> 'operator' . $transaction_type->id,
        'fromConnector'=> 'output_1',
        'toOperator'=> 'operator' . $transaction_type->linked_transaction_type_id,
        'toConnector'=> 'input_1',
      ];
    }

    if(!empty($transaction_type->commission_structure_id)){
      $data['links']['link_'.$transaction_type->id] = [
        'fromOperator'=> 'operator' . $transaction_type->id,
        'fromConnector'=> 'output_1',
        'toOperator'=> 'operator' . $transaction_type->commission_structure_id,
        'toConnector'=> 'input_1',
        'color'=>'green',
      ];
    }
  }
?>

<style>
body { background-color:#fafafa;}
.container { margin:10px auto;}
.demo {height: 600px;margin-bottom: 10px;overflow-y: auto;}
</style>

<div class="container">
  <button class="btn btn-danger btn-xs" id="delete_selected_button"><i class="glyphicon glyphicon-trash"></i></button>
	<div class="demo" id="example"></div>
	<!-- <button class="btn btn-primary" id="create_operator">Create A New Operator</button> -->
</div>

<script src="<?=$this->request->webroot?>js/jquery.flowchart.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var data = <?php echo json_encode($data);?>;
    // Apply the plugin on a standard, empty div...
    $('#example').flowchart({
      data: data
    });
  });
  var operatorI = 0;
    $('#create_operator').click(function() {
      var operatorId = 'created_operator_' + operatorI;
      var operatorData = {
        top: 60,
        left: 500,
        properties: {
          title: 'Operator ' + (operatorI + 3),
          inputs: {
            input_1: {
              label: 'Input 1',
            }
          },
          outputs: {
            output_1: {
              label: 'Output 1',
            }
          }
        }
      };

      operatorI++;

      $('#example').flowchart('createOperator', operatorId, operatorData);
    });

    $('#delete_selected_button').click(function() {
      $('#example').flowchart('deleteSelected');
    });
</script>