<div>
    <div class="col-md-1"></div>
    <div class="col-md-10">
    	<div class="box box-primary">
            <div class="box-body table-responsive">
            	<button class="btn-default btn-xs pull-right" id="load-all-statuses">
		    		<i class="glyphicon glyphicon-refresh" title="Load All Permission Statuses"></i> Load All
		    	</button>
                <table class="table table-stripped table-compressed">
                	<?php foreach($resources as $controllers):?>
                		<?php foreach($controllers as $controller=>$actions):?>
                			<tr>
                				<td style="width:200px;"><?=$controller?><br> 
                				<i class="glyphicon glyphicon-refresh load-status" id="load-status-<?=$controller?>" controller="<?=$controller?>" style="cursor:pointer;" title="Load Permission Status"></i>
                				<div class="load-status-<?=$controller?>"></div>
                				</td>
                				<td>
		                			<table class="table">
		                				<tr>
		                					<th style="width:150px;">Action</th>
		                					<?php foreach($roles as $role):?>
		                						<?php if($role->alias=='super_admin') continue;?>
		                						<th><?=$role->title?></th>
		                					<?php endforeach;?>
		                				</tr>

		                				<?php foreach($actions as $action):?>
		                				<tr id="load-status-<?=$controller?>-<?=$action?>" action="<?=$action?>" controller="<?=$controller?>">
		                					<td class="action-name"><?=$action?></td>
		                					<?php foreach($roles as $role):?>
		                						<?php if($role->alias=='super_admin') continue;?>
		                						<td class="action-status" id="<?=$controller?>-<?=$action?>-<?=$role->id?>" action="<?=$action?>" controller="<?=$controller?>" role="<?=$role->id?>">&nbsp;</td>
		                					<?php endforeach;?>
		                				</tr>
		                				<?php endforeach;?>
		                			</table>
	                			</td>
	                		</tr>
                		<?php endforeach;?>
                	<?php endforeach;?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
<script type="text/javascript">
	var baseUrl = '<?=$this->request->webroot?>role-permissions/';
</script>
<?=$this->Html->script('role_permissions')?>