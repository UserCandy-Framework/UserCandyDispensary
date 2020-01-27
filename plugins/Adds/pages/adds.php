<?php
/**
* Adds Plugin Admin Home Page
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

use Helpers\{Form,ErrorMessages,SuccessMessages,Language,PageFunctions,Csrf,Request};

/** Get data for dashboard */
$data['current_page'] = $_SERVER['REQUEST_URI'];
$data['title'] = "Adds Settings";
$data['welcomeMessage'] = "Welcome to the Admin Panel Site Adds Settings!";

/** Check to see if Admin is submiting form data */
if(isset($_POST['submit'])){
	/** Check to see if site is a demo site */
	if(DEMO_SITE != 'TRUE'){
			/** Check to make sure the csrf token is good */
			if (Csrf::isTokenValid('settings')) {
					/** Check to make sure Admin is updating settings */
					if($_POST['update_settings'] == "true"){
							/** Get data sbmitted by form */
							$adds_top = $_POST['adds_top'];
							$adds_bottom = $_POST['adds_bottom'];
							$adds_sidebar_top = $_POST['adds_sidebar_top'];
							$adds_sidebar_bottom = $_POST['adds_sidebar_bottom'];

							if(!$AdminPanelModel->updateSetting('adds_top', $adds_top)){ $errors[] = 'Adds Main Top Error'; }
							if(!$AdminPanelModel->updateSetting('adds_bottom', $adds_bottom)){ $errors[] = 'Adds Main Bottom Error'; }
							if(!$AdminPanelModel->updateSetting('adds_sidebar_top', $adds_sidebar_top)){ $errors[] = 'Adds Sidebar Top Error'; }
							if(!$AdminPanelModel->updateSetting('adds_sidebar_bottom', $adds_sidebar_bottom)){ $errors[] = 'Adds Sidebar Bottom Error'; }

							// Run the update settings script
							if(!isset($errors) || count($errors) == 0){
									/** Success */
									SuccessMessages::push('You Have Successfully Updated Adds Settings', 'AdminPanel-Adds');
							}else{
									// Error
									if(isset($errors)){
											$error_data = "<hr>";
											foreach($errors as $row){
													$error_data .= " - ".$row."<br>";
											}
									}else{
											$error_data = "";
									}
									/** Error Message Display */
									ErrorMessages::push('Error Updating Adds Settings'.$error_data, 'AdminPanel-Adds');
							}
					}else{
							/** Error Message Display */
							ErrorMessages::push('Error Updating Adds Settings', 'AdminPanel-Adds');
					}
			}else{
					/** Error Message Display */
					ErrorMessages::push('Error Updating Adds Settings', 'AdminPanel-Adds');
			}
	}else{
			/** Error Message Display */
			ErrorMessages::push('Demo Limit - Add Settings Disabled', 'AdminPanel-Adds');
	}
}

/** Get Settings Data */
$data['adds_top'] = $AdminPanelModel->getSettings('adds_top');
$data['adds_bottom'] = $AdminPanelModel->getSettings('adds_bottom');
$data['adds_sidebar_top'] = $AdminPanelModel->getSettings('adds_sidebar_top');
$data['adds_sidebar_bottom'] = $AdminPanelModel->getSettings('adds_sidebar_bottom');

/** Setup Token for Form */
$data['csrfToken'] = Csrf::makeToken('settings');

/** Setup Breadcrumbs */
$data['breadcrumbs'] = "
	<li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
	<li class='breadcrumb-item active'><i class='fab fa-fw fa-adn'></i> ".$data['title']."</li>
";

?>

<div class='col-lg-12 col-md-12 col-sm-12'>
  <div class='row'>
    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			<?php echo $data['title'];  ?>
          <?php echo PageFunctions::displayPopover('Site Adds Settings', 'Site Adds Settings is used to impliment adds to the site.  You can copy and paste the add code below.  The site will then place that code at given locations on the site.  If left blank, add window will not display.', false, 'btn btn-sm btn-light'); ?>
    		</div>
    		<div class='card-body'>
    			<p><?php echo $data['welcomeMessage'] ?></p>

    			<?php echo Form::open(array('method' => 'post')); ?>

          <!-- Adds Main Top Code -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
        		<div class='input-group-prepend'>
        			<span class='input-group-text'>Google Auto Adds</span>
        		</div>
            <?php echo Form::textBox(array('type' => 'text', 'name' => 'adds_top', 'class' => 'form-control', 'value' => $data['adds_top'], 'placeholder' => 'Main Top Adds Code', 'rows' => '6')); ?>
          </div>


        </div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
        <button class="btn btn-md btn-success" name="submit" type="submit">
            Update Adds Settings
        </button>
        <!-- CSRF Token and What is Being Updated -->
        <input type="hidden" name="token_settings" value="<?php echo $data['csrfToken']; ?>" />
        <input type="hidden" name="update_settings" value="true" />
        <?php echo Form::close(); ?><Br><br>
    </div>
  </div>
</div>
