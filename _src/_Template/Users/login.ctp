<div class="">
    <div class="col-md-6 col-md-offset-3">
        <div class="box box-primary">
			<div class="box-body table-responsive">
				<?= $this->Flash->render('auth') ?>
			    <form method="post" accept-charset="utf-8" action="<?=$this->request->webroot;?>/users/login">
			    <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>			    <fieldset>
			        <h5>Login</h5>
			        <div class="input text"><label for="username">Username</label><input type="text" name="username" class="form-control" id="username"/></div>			        <div class="input password"><label for="password">Password</label><input type="password" name="password" class="form-control" id="password"/></div>			    </fieldset>
			    <hr>
			    <button class="btn btn-large btn-primary pull-right" type="submit">Login</button>			    
			    </form>	
			</div>
		</div>
	</div>
</div>
