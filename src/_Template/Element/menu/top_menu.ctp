<!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="<?=$this->request->webroot;?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <?=$appName?>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <?php //echo $this->element('menu/messages'); ?>
                        <?php //echo $this->element('menu/notifications'); ?>
                        <?php //echo $this->element('menu/tasks'); ?>
                        <?php echo $this->element('menu/user_account'); ?>
                    </ul>
                </div>
            </nav>
        </header>