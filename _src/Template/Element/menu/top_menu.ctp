        <header class="main-header header">
            <a href="<?=$this->request->webroot;?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <!-- <?php echo $this->Html->image('logo.png', ['alt'=>isset($appSettings->app_name)?$appSettings->app_name:'','class-removed'=>'img-circle','width'=>'40px','style'=>'width: 50px;margin-top: -5px;']); ?> -->
                <span>
                    <?php echo h(substr(isset($appSettings->app_name)?$appSettings->app_name:'Transfer',0,11));?>
                </span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <button type="button" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <i class="glyphicon glyphicon-tasks"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <form action="<?=$this->request->webroot?>transactions" method="get" class="navbar-form navbar-left">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="search transactions" required="" />
                            <span class="input-group-btn">
                                <button type='submit' id='search-btn' class="btn btn-flat"><i class="glyphicon glyphicon-search"></i></button>
                            </span>
                        </div>
                    </form>
                    <form action="<?=$this->request->webroot?>customers" method="get" class="navbar-form navbar-left">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="search customers" required=""/>
                            <span class="input-group-btn">
                                <button type='submit' id='search-btn' class="btn btn-flat"><i class="glyphicon glyphicon-search"></i></button>
                            </span>
                        </div>
                    </form>

                    <div class="navbar-right">
                        <ul class="nav navbar-nav">
                            <?php //echo $this->element('menu/messages'); ?>
                            <?php //echo $this->element('menu/notifications'); ?>
                            <?php //echo $this->element('menu/tasks'); ?>
                            <?php echo $this->element('menu/user_account'); ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>