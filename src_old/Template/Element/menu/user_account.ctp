 						<!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?=$authUser['name']?> (Branch - <?=$authUser['branch_id']?>) <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-black">
                                     <?= $this->Html->image('avatar04.png', array('class' => 'img-circle')); ?>
                                    <p>
                                        <?=$authUser['name']?> (<?=$authUser['role']?>)
                                        <small>Member since <?=date('M. Y',strtotime($authUser['created']))?></small>
                                        <small>Branch - <?=$authUser['branch_id']?></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="<?=$this->request->webroot?>">Home</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="<?=$this->request->webroot?>users/edit/<?=$authUser['id']?>">Settings</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="<?=$this->request->webroot?>users">Users</a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?=$this->request->webroot?>users/view/<?=$authUser['id']?>" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?=$this->request->webroot?>users/logout" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>