<div>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-body table-responsive">
            	<div class="col-md-4">
                    
                </div>

                <div class="col-md-6">
		            <?= $this->Form->create($user,['class'=>'form','type'=>'file']) ?>
	                <fieldset>
	                    <legend><?= __('Edit ' . $title) ?></legend>
	                    <div class="well">
	                        <!-- <small> <b>Dimensions Recommended </b>: Width <?=$width?>px, Height:<?=$height?>px<br></small> -->
	                        <small> <b>Maximum Image Size </b>: <?=$max_file_size?> KB<br></small>
	                        <small> 
	                            <b>Image Types supported </b>: (
	                                <?php foreach ($fileExtensionsSupported as $value):?>
	                                    <?=$value?> &nbsp;&nbsp;
	                                <?php endforeach;?>
	                            )
	                        </small>
	                    </div>
	                    <?php
							echo $this->Form->input('fileField',[
								'type'=>'file',
								'label'=>'',
								'name'=>$type
							]);
	                    ?>
	                </fieldset>
	                <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
	                <?= $this->Form->end() ?><br>
                </div>

                <div class="col-md-4">
                    
                </div>
            </div>
        </div>
    </div>
</div>