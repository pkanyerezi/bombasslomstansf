var RolePermissions = {
	makeRequest: function(url,data,callback){
		$.getJSON(url, data)
		.done(function( json ) {
		    callback(false,json);
		})
		.fail(function( jqxhr, textStatus, error ) {
		    callback(error,{});
		});
	},
	getPermissionStatuses: function(data,loadStatusContainer){
		var url = baseUrl + 'getPermissionStatuses';
		var container = '';
		RolePermissions.toggleLoading(loadStatusContainer,true);
		RolePermissions.makeRequest(url,data,function(err,response){
			RolePermissions.toggleLoading(loadStatusContainer,false);
			if(err) {
				RolePermissions.showErrorMessage(loadStatusContainer,'<i class="glyphicon glyphicon-refresh load-status" id="load-status-'+data.controller+'" controller="'+data.controller+'" style="cursor:pointer;" title="Load Status"></i>');
				return false;
			}

			if (response.status && response.data.length) {
				RolePermissions.renderStatus(response.data);
			}else{
				RolePermissions.showErrorMessage(loadStatusContainer,'<i class="glyphicon glyphicon-refresh load-status" id="load-status-'+data.controller+'" controller="'+data.controller+'" style="cursor:pointer;" title="Load Status"></i>');
			}
		});
	},
	togglePermissionStatus: function(data){
		var id = data.controller + '-' + data.action + '-' + data.role;
		$('#' + id).html('loading...');
		var url = baseUrl + 'togglePermissionStatus';
		RolePermissions.makeRequest(url,data,function(err,response){
			$('#' + id).html('loaded');
			if(err) {
				$('#' + id).html('failed');
				return false;
			}

			if (response.status && response.data.length) {
				RolePermissions.renderStatus(response.data);
			}else{
				RolePermissions.showErrorMessage(loadStatusContainer,'<i class="glyphicon glyphicon-refresh load-status" id="load-status-'+data.controller+'" controller="'+data.controller+'" style="cursor:pointer;" title="Load Status"></i>');
			}
		});
	},
	renderStatus: function(data){
		$.each(data,function(){
			var container ='#'+this.controller+'-' + this.action + '-' + this.role_id;
			var content = '';
			if(this.enabled){
				content = '<i style="cursor:pointer;" controller="'+this.controller+'" action="'+this.action+'" role="'+this.role_id+'" title="click to disable" class="glyphicon glyphicon-ok action-status-icon" actionStatus="'+this.enabled+'" pkid="'+this.id+'"></i>';
			}else{
				content = '<i style="cursor:pointer;" controller="'+this.controller+'" action="'+this.action+'" role="'+this.role_id+'" title="click to enable" class="glyphicon glyphicon-remove action-status-icon" actionStatus="'+this.enabled+'" pkid="'+this.id+'"></i>';
			}
			$(container).html(content);
		});
	},
	showErrorMessage(container,msg){
		$(container).html('<span style="cursor:pointer;" class="alert alert-error '+refreshClass+'">' + msg + ' <i class="glyphicon glyphicon-refresh" class=""></i></span>');
	},
	showSuccessMessage(container,msg){
		$(container).html('<span>' + msg + '</span>');
	},
	toggleLoading(container,show){
		if (show) $(container).html('loading ...');
		else $(container).html('');
	}
};

$(document).ready(function(){
	$(document).on('click','#load-all-statuses',function(){
		$.each($('.load-status'),function(){
			var controller = $(this).attr('controller');
			var loadStatusContainer = '.load-status-' + controller;
			var data = {controller:controller};
			RolePermissions.getPermissionStatuses(data,loadStatusContainer);
		});
	});

	$(document).on('click','.load-status',function(){
		var controller = $(this).attr('controller');
		var loadStatusContainer = '.load-status-' + controller;
		var data = {controller:controller};
		RolePermissions.getPermissionStatuses(data,loadStatusContainer);
	});
	$(document).on('click','.action-status-icon',function(){
		var controller = $(this).attr('controller');
		var action = $(this).attr('action');
		var role = $(this).attr('role');
		var pkid = $(this).attr('pkid');
		
		var data = {controller:controller,action:action,role:role,pkid:pkid};
		RolePermissions.togglePermissionStatus(data);
	});
});