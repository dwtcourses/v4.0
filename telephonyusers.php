<?php	

	###################################################
	### Name: telephonyusers.php 	   ###
	### Functions: Manage Users 	   ###
	### Copyright: GOAutoDial Ltd. (c) 2011-2016	   ###
	### Version: 4.0 	   ###
	### Written by: Alexander Jim H. Abenoja	   ###
	### License: AGPLv2	   ###
	###################################################

	require_once('./php/UIHandler.php');
	require_once('./php/CRMDefaults.php');
    require_once('./php/LanguageHandler.php');
    include('./php/Session.php');

	$ui = \creamy\UIHandler::getInstance();
	$lh = \creamy\LanguageHandler::getInstance();
	$user = \creamy\CreamyUser::currentUser();
	$perm = $ui->goGetPermissions('user', $_SESSION['usergroup']);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php $lh->translateText("portal_title"); ?> <?php $lh->translateText("users"); ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        
        <?php print $ui->standardizedThemeCSS(); ?>

        <?php print $ui->creamyThemeCSS(); ?>

		<!-- DATA TABLES -->
        <link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
	
    	<!-- Wizard Form style -->
		<link href="css/style.css" rel="stylesheet" type="text/css" />

		<!-- Data Tables -->
        <script src="js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
		
        <!-- CHOSEN-->
   		<link rel="stylesheet" href="theme_dashboard/chosen_v1.2.0/chosen.min.css">
        <!-- SELECT2-->
   		<link rel="stylesheet" href="theme_dashboard/select2/dist/css/select2.css">
   		<link rel="stylesheet" href="theme_dashboard/select2-bootstrap-theme/dist/select2-bootstrap.css">
   		<!-- SELECT2-->
   		<script src="theme_dashboard/select2/dist/js/select2.js"></script>
		<style>
			.wizard > .steps > ul > li{
				width: 33.33%!important;
			}
		</style>
    </head>

    <?php print $ui->creamyBody(); ?>

        <div class="wrapper">
        <!-- header logo: style can be found in header.less -->
		<?php print $ui->creamyHeader($user); ?>
            <!-- Left side column. contains the logo and sidebar -->
			<?php print $ui->getSidebar($user->getUserId(), $user->getUserName(), $user->getUserRole(), $user->getUserAvatar()); ?>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?php $lh->translateText("telephony"); ?>
                        <small><?php $lh->translateText("users_management"); ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="./index.php"><i class="fa fa-phone"></i> <?php $lh->translateText("home"); ?></a></li>
                        <li><?php $lh->translateText("telephony"); ?></li>
						<li class="active"><?php $lh->translateText("users"); ?>
                    </ol>
                </section>
		
                <!-- Main content -->
                <section class="content">
                <?php if ($perm->user_read !== 'N') { ?>
                    <div class="panel panel-default">
						<div class="panel-body">
						<legend><?php $lh->translateText("users"); ?></legend>
							<div role="tabpanel">
								<ul role="tablist" class="nav nav-tabs nav-justified">
								<!-- Users panel tab -->
									 <li role="presentation" class="active">
										<a href="#users_tab" aria-controls="users_tab" role="tab" data-toggle="tab" class="bb0">
											<?php $lh->translateText("users"); ?> </a>
									 </li>
									 <?php
										 if(isset($_SESSION['use_webrtc']) && $_SESSION['use_webrtc'] == 0){
									 ?>
									 
									 <!-- Phones panel tabs-->
									 <li role="presentation" >
										<a href="#phone_tab" aria-controls="phone_tab" role="tab" data-toggle="tab" class="bb0">
											<?php $lh->translateText("phones"); ?></a>
									 </li>
									 <?php	
										}
									 ?>
								</ul>
								<!-- Tab panes-->
								<div class="tab-content bg-white">
									<!--==== users ====-->
									<div id="users_tab" role="tabpanel" class="tab-pane active">
										<?php print $ui->goGetAllUserList($_SESSION['user'], $perm); ?>
									</div>
									
									<?php
										if(isset($_SESSION['use_webrtc']) && $_SESSION['use_webrtc'] == 0){
									?>
									<!--==== Phones ====-->
									<div id="phone_tab" role="tabpanel" class="tab-pane">
										<?php print $ui->getPhonesList(); ?>
									</div>
									<?php
										}
									?>
								</div><!-- END tab content-->
							</div><!-- end of tabpanel -->
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				<!-- /fila con acciones, formularios y demás -->
				<?php
					} else {
						print $ui->calloutErrorMessage($lh->translationFor("you_dont_have_permission"));
					}
				?>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
			<?php print $ui->getRightSidebar($user->getUserId(), $user->getUserName(), $user->getUserAvatar()); ?>
        </div><!-- ./wrapper -->
<?php
	if(isset($_SESSION['use_webrtc']) && $_SESSION['use_webrtc'] == 0){
?>
    <!-- FIXED ACTION BUTTON --> 
		<div class="bottom-menu skin-blue <?=($perm->user_create === 'N' ? 'hidden' : '')?>">
			<div class="action-button-circle" data-toggle="modal">
				<?php print $ui->getCircleButton("users", "plus"); ?>
			</div>
			<div class="fab-div-area" id="fab-div-area">
				<ul class="fab-ul" style="height: 170px;">
					<li class="li-style"><a class="fa fa-user-plus fab-div-item" data-toggle="modal" data-target="#user-wizard-modal" title="Add User"></a></li><br/>
					<li class="li-style"><a class="fa fa-phone fab-div-item" data-toggle="modal" data-target="#phone-wizard-modal" title="Add Phone"></a></li><br/>
				</ul>
			</div>
		</div>
<?php
	}else{
?>
	<div class="action-button-circle <?=($perm->user_create === 'N' ? 'hidden' : '')?>" data-toggle="modal" data-target="#user-wizard-modal">
		<?php print $ui->getCircleButton("calls", "user-plus"); ?>
	</div>
<?php
	}
?>
	
<!-- MODALS -->
<?php
	$output = $ui->API_goGetAllUserLists();
	$user_groups = $ui->API_goGetUserGroupsList();
	$phones = $ui->API_getPhonesList();
	$max = max($phones->extension);
	$suggested_extension = $max + 1;
	$count_users = count($output->user);
	$license_seats = intval($output->licensedSeats);
	$avail_seats = $license_seats-$count_users;
?>
	<!-- ADD USER MODAL -->
	    <div class="modal fade" id="user-wizard-modal" aria-labelledby="T_User" >
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
					
					<div class="modal-header">
						
						<h4 class="modal-title animated bounceInRight" id="T_User">
							<i class="fa fa-info-circle" title="<?php $lh->translateText("user_wizard_desc"); ?>"></i> 
							<b><?php $lh->translateText("user_wizard"); ?> » <?php $lh->translateText("add_new_user"); ?></b>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</h4>
					</div>
					<div class="modal-body" style="overflow:hidden;">
					
					<form id="wizard_form" action="#">
						<input type="hidden" name="log_user" value="<?=$_SESSION['user']?>" />
						<input type="hidden" name="log_group" value="<?=$_SESSION['usergroup']?>" />
						<div class="row">
	                        <h4>Getting Started
	                           <br>
	                           <small>Number of Users to Create</small>
	                        </h4>
	                        <fieldset>
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php $lh->translateText("Current Users"); ?>: </label>
									<div class="col-lg-8 reverse_control_label mb">
										<?php echo $count_users; ?>
									</div>
								</div>
							<!-- ENABLE IF ADD MULTIPLE IS AVAILABLE -->
								<div class="form-group">
									<label class="col-sm-4 control-label">Number of Seat(s): </label>
									<div class="col-sm-8">
										<select name="seats" id="seats" class="form-control">
										<?php
											if($avail_seats > 0 || $license_seats == 0){
												if($avail_seats >= 99 || $license_seats == 0)
													$max_create = 99;
												else
													$max_create = $avail_seats;
												
												for($i=1; $i <= $max_create; $i++){
													echo '<option value="'.$i.'">'.$i.'</option>';
												}
											}else{
												echo '<option value="1">1</option>';
											}
										?>
										</select>
									</div>
								</div>
	                        </fieldset>
	                        <h4><?php $lh->translateText("account_details"); ?>
	                           <br>
	                           <small><?php $lh->translateText("account_details_sub_header"); ?></small>
	                        </h4>
	                        <fieldset>
	                           <?php
								$agent_num = $output->last_count;

								$num_padded = sprintf("%03d", $agent_num);
								
								$fullname = "Agent ".$num_padded;
								$user_id_for_form = "agent".$num_padded;
								?>
								<div class="form-group">
									<label class="col-sm-4 control-label"> <?php $lh->translateText("user_id"); ?> </label>
									<div class="col-sm-8 mb">
										<input type="text" class="form-control" name="user_form" id="user_form" placeholder="<?php $lh->translateText("user_id"); ?> (<?php $lh->translateText("mandatory"); ?>)" 
											value="<?php echo $user_id_for_form;?>" title="<?php $lh->translateText("alphanumberic_only_instruction"); ?>" maxlength="20" required>
										<label id="user-duplicate-error"></label>
									</div>
								</div>
								<div class="form-group mt">
									<label class="col-sm-4 control-label" for="user_group"> <?php $lh->translateText("user_group"); ?> </label>
									<div class="col-sm-8 mb">
										<select id="user_group" class="form-control select2-1" name="user_group" style="width:100%;">
											<?php
												for($i=0;$i<count($user_groups->user_group);$i++){
											?>
												<option value="<?php echo $user_groups->user_group[$i];?>" <?php if($user_groups->user_group[$i] == "AGENTS"){echo "selected";}?>>  <?php echo $user_groups->group_name[$i];?>  </option>
											<?php
												}
											?>
										</select>
									</div>
								</div>
								<div class="form-group" id="phone_logins_form" style="display:none;">
									<label class="col-sm-4 control-label" for="phone_logins"> <?php $lh->translateText("phone_login"); ?> </label>
									<div class="col-sm-8 mb">
										<input type="number" name="phone_logins" id="phone_logins" class="form-control" minlength="3" placeholder="<?php $lh->translateText("phone_login"); ?> (<?php $lh->translateText("mandatory"); ?>)" 
											value="<?php echo $output->last_phone_login;?>" pattern=".{3,}" title="Minimum of 3 characters" maxlength="20" required>
										<label id="phone_login-duplicate-error"></label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="fullname"> <?php $lh->translateText("full_name"); ?> </label>
									<div class="col-sm-8 mb">
										<input type="text" name="fullname" id="fullname" class="form-control" placeholder="<?php $lh->translateText("full_name"); ?> (<?php $lh->translateText("mandatory"); ?>)"
											   value="<?php echo $fullname;?>" title="<?php $lh->translateText("alphanumberic_only_instruction"); ?>" maxlength="50" required>
									</div>
								</div>
								<div class="form-group" id="password_div">
									<label class="col-sm-4 control-label" for="password"><i class="fa fa-info-circle" title="<?php $lh->translateText("default_pass_is"); ?>: Go<?php echo date('Y');?>"></i>  <?php $lh->translateText("password"); ?> </label>
									
									<div class="col-sm-8 mb">
										<input type="password" class="form-control" name="password" id="password" placeholder="Password (<?php $lh->translateText("mandatory"); ?>)" value="Go<?php echo date('Y');?>" maxlength="10" required>
									</div>
								</div>
								<div class="form-group" id="confirm_div">
									<label class="col-sm-4 control-label" for="confirm"> <?php $lh->translateText("confirm_password"); ?> </label>
									<div class="col-sm-8 mb">
										<input type="password" class="form-control" id="confirm" name="confirm" placeholder="<?php $lh->translateText("reenter_pass"); ?> (<?php $lh->translateText("mandatory"); ?>)" value="Go<?php echo date('Y');?>" required>
									</div> 
								</div>
								<!--
								<div class="row">
									<p class="col-sm-12"><small class="pull-right" style="padding-right:20px;"><i><span id="pass_result" class="control-label"></span></i></small></p>
								</div>-->
								<input type="hidden" name="phone_pass" id="phone_pass" class="form-control">

								<div class="form-group">
									<label class="col-sm-4 control-label" for="status"><?php $lh->translateText("active"); ?> </label>
									<div class="col-sm-8 mb">
										<select name="status" id="status" class="form-control">
											<option value="Y" selected><?php $lh->translateText("go_yes"); ?></option>
											<option value="N" ><?php $lh->translateText("go_no"); ?></option>
										</select>
									</div>
								</div>
	                        </fieldset>
	                        <h4><?php $lh->translateText("review_and_submit"); ?>
	                           <br>
	                           <small><?php $lh->translateText("review_and_submit_sub_header"); ?></small>
	                        </h4>
	                        <fieldset>
							<div class="form-group">
								<label class="col-lg-6 control-label"><?php $lh->translateText("user_group"); ?>: </label>
								<div class="col-lg-6 reverse_control_label mb">
									<span id="submit-usergroup"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-6 control-label"><?php $lh->translateText("user_id"); ?>: </label>
								<div class="col-lg-6 reverse_control_label mb">
									<span id="submit-userid"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-6 control-label"><?php $lh->translateText("full_name"); ?>: </label>
								<div class="col-lg-6 reverse_control_label mb">
									<span id="submit-fullname"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-6 control-label" id="submit-password-lbl"><?php $lh->translateText("password"); ?>: </label>
								<div class="col-lg-6 reverse_control_label mb">
									<span id="submit-password"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-6 control-label"><?php $lh->translateText("active"); ?>: </label>
								<div class="col-lg-6 reverse_control_label mb">
									<span id="submit-active"></span>
								</div>
							</div>
	                        </fieldset>
	                     </div>
					</form>
			
					</div> <!-- end of modal body -->
				</div>
			</div>
		</div>
	<!-- end of add user modal -->

 <?php
	if(isset($_SESSION['use_webrtc']) && $_SESSION['use_webrtc'] == 0){
		$servers = $ui->getServers();
?>
	<!-- ADD PHONE MODAL -->
	    <div class="modal fade" id="phone-wizard-modal" aria-labelledby="T_Phones" >
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
					<div class="modal-header">
						
						<h4 class="modal-title animated bounceInRight" id="T_Phones">
							<i class="fa fa-info-circle" title="<?php $lh->translateText("phone_wizard_desc"); ?>"></i> 
							<b><?php $lh->translateText("phone_wizard"); ?> » <?php $lh->translateText("add_new_phone"); ?></b>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</h4>
					</div>
					<div class="modal-body wizard-content">
					
					<form name="create_form" id="create_form" role="form">
						<input type="hidden" name="log_user" value="<?=$_SESSION['user']?>" />
						<input type="hidden" name="log_group" value="<?=$_SESSION['usergroup']?>" />
						<div class="row">
							<h4>
								<?php $lh->translateText("add_new_phone"); ?> <br/>
								<small><?php $lh->translateText("add_phone_sub_header"); ?></small>
							</h4>
							<fieldset>
								<div class="form-group mt">
									<label class="col-sm-4 control-label" for="add_phones">Additional Phone(s):</label>
									<div class="col-sm-6 mb">
										<select class="form-control" name="add_phones" id="add_phones">
											<option value="1"> 1 </option>
											<option value="2"> 2 </option>
											<option value="3"> 3 </option>
											<option value="4"> 4 </option>
											<option value="5"> 5 </option>
											<option value="CUSTOM">CUSTOM</option>
										</select>
									</div>
									<div class="col-sm-2" id="custom_seats" style="display:none;">
										<input type="number" class="form-control" name="custom_seats" value="1" min="1" max="99" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="start_ext"><?php $lh->translateText("starting_extention"); ?></label>
									<div class="col-sm-8 mb">
										<input type="number" name="start_ext" id="start_ext" placeholder="<?php $lh->translateText("starting_extention"); ?>" value="<?php echo $output->last_phone_login;?>" class="form-control">
									</div>
								</div>
							</fieldset>
					<!-- end of step 1-->
					<!-- STEP 2 -->
							<h4>
								<small></small>
							</h4>
							<fieldset>
								<div class="form-group mt">
									<label class="col-sm-4 control-label" for="phone_ext"><?php $lh->translateText("phone_login"); ?></label>
									<div class="col-sm-8 mb">
										<input text="number" name="phone_ext" id="phone_ext" class="form-control" placeholder="<?php $lh->translateText("phone_login"); ?> (<?php $lh->translateText("mandatory"); ?>)" title="Must be 3 - 20 characters and contains only numerical values." minlength="3" maxlength="20" required/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="phone_pass"><?php $lh->translateText("phone_login_password"); ?></label>
									<div class="col-sm-8 mb">
										<input type="text" name="phone_pass" id="phone_pass" class="form-control"  placeholder="<?php $lh->translateText("phone_login_password"); ?> (<?php $lh->translateText("mandatory"); ?>)" title="<?php $lh->translateText("default_pass_is"); ?>: Go<?php echo date('Y');?>" value="Go<?php echo date('Y');?>" maxlength="20" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="start_ext"><?php $lh->translateText("user_group"); ?></label>
									<div class="col-sm-8 mb">
										<select name="user_group" class="form-control select2-1" style="width:100%;" required>
											<option value="ALL">ALL USER GROUPS</option>
											<?php
												for($i=0; $i < count($user_groups->user_group); $i++){
											?>
												<option value="<?php echo $user_groups->user_group[$i];?>"> <?php echo $user_groups->user_group[$i]." - ".$user_groups->group_name[$i]; ?></option>
											<?php
												}
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="ip"><?php $lh->translateText("server_ip"); ?></label>
									<div class="col-sm-8 mb">
										<select name="ip" id="ip" class="form-control" required>
											<?php
												for($i=0;$i < count($servers->server_id);$i++){
											?>
											<option value="<?php echo $servers->server_ip[$i];?>">
												<?php echo $servers->server_ip[$i].' - '.$servers->server_id[$i].' - '.$servers->server_description[$i];?>
											</option>
											<?php
												}
											?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label" for="pfullname"><?php $lh->translateText("full_name"); ?></label>
									<div class="col-sm-8 mb">
										<input type="text" name="pfullname" id="pfullname" placeholder="Full Name (Mandatory)" class="form-control" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="gmt"></label>
									<div class="col-sm-8 mb">
										<div class="row">
											<div class="col-sm-6">
												<select name="gmt" id="gmt" class="form-control" required>
													<option value="12:75"> 12:75 </option>
													<option value="12:00"> 12:00 </option>
													<option value="11:00"> 11:00 </option>
													<option value="10:00"> 10:00 </option>
													<option value="9:50"> 9:50 </option>
													<option value="9:00"> 9:00 </option>
													<option value="8:00"> 8:00 </option>
													<option value="7:00"> 7:00 </option>
													<option value="6:50"> 6:50 </option>
													<option value="6:00"> 6:00 </option>
													<option value="5:75"> 5:75 </option>
													<option value="5:50"> 5:50 </option>
													<option value="5:00"> 5:00 </option>
													<option value="4:50"> 4:50 </option>
													<option value="4:00"> 4:00 </option>
													<option value="3:50"> 3:50 </option>
													<option value="3:00"> 3:00 </option>
													<option value="2:00"> 2:00 </option>
													<option value="1:00"> 1:00 </option>
													<option value="0:00"> 0:00 </option>
													<option value="-1:00"> -1:00 </option>
													<option value="-2:00"> -2:00 </option>
													<option value="-3:00"> -3:00 </option>
													<option value="-4:00"> -4:00 </option>
													<option value="-5:00" selected> -5:00 </option>
													<option value="-6:00"> -6:00 </option>
													<option value="-7:00"> -7:00 </option>
													<option value="-8:00"> -8:00 </option>
													<option value="-9:00"> -9:00 </option>
													<option value="-10:00"> -10:00 </option>
													<option value="-11:00"> -11:00 </option>
													<option value="-12:00"> -12:00 </option>
												</select>
											</div>
											<div class="col-sm-6">
												<p class="text-muted">( <?php $lh->translateText("do_not_adjust_gmt"); ?> )</p>
											</div>
										</div>
									</div>
								</div>
							</fieldset><!-- end of step 2-->
						</div><!-- end of row -->
					</form>

					</div> <!-- end of modal body -->
				</div> <!-- end of modal content -->
			</div> <!-- end of modal dialog -->
		</div>
	<!-- end of add phone modal -->
<?php
	}
?>
<!-- end of modals -->

		<?php print $ui->standardizedThemeJS();?>
		<!-- JQUERY STEPS-->
  		<script src="theme_dashboard/js/jquery.steps/build/jquery.steps.js"></script>
  		<!-- SELECT2-->
        <script src="theme_dashboard/select2/dist/js/select2.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		
		/*********************
		** INITIALIZATION
		*********************/

			var checker = 0;

			/* init data tables */

				//users
				$('#T_users').dataTable({
					stateSave: true,
					"aaSorting": [[ 1, "asc" ]],
					"aoColumnDefs": [{
						"bSearchable": false,
						"aTargets": [ 0, 5 ]
					},{
						"bSortable": false,
						"aTargets": [ 0, 5 ]
					}]
				});
				//phones
				$('#T_phones').dataTable({
					stateSave: true
				});

			/* init wizards */
				var uform = $("#wizard_form"); // init user form wizard 
				var pform = $("#create_form"); // init phone form wizard 

			/* enable on hover event for FAB */
				// loads the fixed action button
				$(".bottom-menu").on('mouseenter mouseleave', function () {
				  $(this).find(".fab-div-area").stop().slideToggle({ height: 'toggle', opacity: 'toggle' }, 'slow');
				});

			
		/*********
		** Add Wizard
		*********/
			//users form validate
		    uform.validate({
		        errorPlacement: function errorPlacement(error, element) { element.after(error); },
		        rules: {
		            confirm: {
		                equalTo: "#password"
		            }
		        }
		    });

		    //phones form validate
		    pform.validate({
		        errorPlacement: function errorPlacement(error, element) { element.after(error); }
		    });

		    //users
		    uform.children("div").steps({
		        headerTag: "h4",
		        bodyTag: "fieldset",
		        transitionEffect: "slideLeft",
		        onStepChanging: function (event, currentIndex, newIndex)
		        {
		        	// Allways allow step back to the previous step even if the current step is not valid!
			        if (currentIndex > newIndex) {
			        	checker = 0;
			            return true;
			        }
					
			        console.log(checker);
			        // Disable next if there are duplicates
			        if(checker > 0){
				        $(".body:eq(" + newIndex + ") .error", uform).addClass("error");
			        	return false;
			        }
					
			        // form review
					show_form_review();
					
					if($('#seats').val() > 1){
						$('#password_div').hide();
						$('#confirm_div').hide();
						$('#submit-password-lbl').text('<?php $lh->translateText("default_pass_is"); ?>: ');
						$('#submit-password').html('<i><?php echo 'Go'.date("Y")?></i>');
					}else{
						$('#submit-password-lbl').text('<?php $lh->translateText("password"); ?>: ');
					}
					
					// Clean up if user went backward before
				    if (currentIndex < newIndex)
				    {
				        // To remove error styles
				        $(".body:eq(" + newIndex + ") label.error", uform).remove();
				        $(".body:eq(" + newIndex + ") .error", uform).removeClass("error");
				    }

		            uform.validate().settings.ignore = ":disabled";
		            return uform.valid();
		        },
		        onFinishing: function (event, currentIndex)
		        {
		            uform.validate().settings.ignore = ":disabled";
		            return uform.valid();
		        },
		        onFinished: function (event, currentIndex)
		        {
					$('#finish').text("<?php $lh->translateText("loading"); ?>");
					$('#finish').attr("disabled", true);
					
		            // Submit form via ajax
		            $.ajax({
						url: "./php/CreateTelephonyUser.php",
						type: 'POST',
						data: $("#wizard_form").serialize(),
						success: function(data) {
						  // console.log(data);
						$('#finish').text("Submit");
    					$('#finish').attr("disabled", false);
							  if(data == 1){
							  	  swal(
									{
										title: "<?php $lh->translateText("add_user_success"); ?>",
										text: "<?php $lh->translateText("user_has_been_saved"); ?>",
										type: "success"
									},
									function(){
										window.location.href = 'telephonyusers.php';
									}
								  );
							  }else{
							  	  sweetAlert("<?php $lh->translateText("add_user_failed"); ?>", data, "error");
							  }
						}
					});
		        }
		    });
			
			//phones
			pform.children("div").steps({
		        headerTag: "h4",
		        bodyTag: "fieldset",
		        transitionEffect: "slideLeft",
		        onStepChanging: function (event, currentIndex, newIndex)
		        {
		        	$("#phone_ext").val($("#start_ext").val());

		        	// Allways allow step back to the previous step even if the current step is not valid!
			        if (currentIndex > newIndex) {
			            return true;
			        }

					// Clean up if user went backward before
				    if (currentIndex < newIndex)
				    {
				        // To remove error styles
				        $(".body:eq(" + newIndex + ") label.error", pform).remove();
				        $(".body:eq(" + newIndex + ") .error", pform).removeClass("error");
				    }

		            pform.validate().settings.ignore = ":disabled,:hidden";
		            return pform.valid();
		        },
		        onFinishing: function (event, currentIndex)
		        {
		            pform.validate().settings.ignore = ":disabled";
		            return pform.valid();
		        },
		        onFinished: function (event, currentIndex)
		        {

		        	$('#finish').text("<?php $lh->translateText("loading"); ?>");
		        	$('#finish').attr("disabled", true);

		        	/*********
		        	** ADD EVENT
		        	*********/

		            // Submit form via ajax
			            $.ajax({
							url: "./php/AddSettingsPhones.php",
							type: 'POST',
							data: $("#create_form").serialize(),
							success: function(data) {
							  // console.log(data);
							$('#finish').text("Submit");
							$('#finish').attr("disabled", false);
								  if(data == 1){
									swal({title: "<?php $lh->translateText("add_phone_success"); ?>",text: "<?php $lh->translateText("phone_has_been_saved"); ?>",type: "success"},function(){window.location.href = 'telephonyusers.php';});
								  }else{
									sweetAlert("<?php $lh->translateText("add_phone_failed"); ?>", "<?php $lh->translateText("something_went_wrong"); ?> "+data, "error");
								  }
							}
						});
		        }
		    });

	//--------------------

		/*********
		** Edit Event
		*********/
			//user edit event
				$(document).on('click','.edit-T_user',function() {
					var url = 'edittelephonyuser.php';
					var userid = $(this).attr('data-id');
					var role = $(this).attr('data-role');
					//alert(userid);
					var form = $('<form action="' + url + '" method="post"><input type="hidden" name="userid" value="'+userid+'" /><input type="hidden" name="role" value="'+role+'"></form>');
					$('body').append(form);  // This line is not necessary
					$(form).submit();
				 });
			//phone edit event
				$(document).on('click','.edit-phone',function() {
					var url = './editsettingsphones.php';
					var extenid = $(this).attr('data-id');
					//alert(extenid);
					var form = $('<form action="' + url + '" method="post"><input type="hidden" name="extenid" value="'+extenid+'" /></form>');
					$('body').append(form);  // This line is not necessary
					$(form).submit();
				});

	//--------------------

		/*********
		** Emergency Logout Event
		*********/
			//user edit event
				$(document).on('click','.emergency-logout',function() {
					var userid = $(this).attr('data-emergency-logout-username');
					var name = $(this).attr('data-name');
					var log_user = '<?=$_SESSION['user']?>';
					var log_group = '<?=$_SESSION['usergroup']?>';
					swal({   
	                	title: "<?php $lh->translateText("emergency_logout"); ?> : " + name,
	                	type: "warning",   
	                	showCancelButton: true,   
	                	confirmButtonColor: "#DD6B55",   
	                	confirmButtonText: "<?php $lh->translateText("agent_logout"); ?>",   
	                	cancelButtonText: "<?php $lh->translateText("cancel_agent_logout"); ?>",   
	                	closeOnConfirm: false,   
	                	closeOnCancel: false 
	                	}, 
	                	function(isConfirm){   
	                		if (isConfirm) { 
	                			$.ajax({
									type: 'POST',
									url: "php/emergency_logout.php",
									data: {
										goUserAgent: userid,
										log_user: log_user,
										log_group: log_group
									},
									cache: false,
									//dataType: 'json',
									success: function(data){
										if(data == "success"){
											sweetAlert("<?php $lh->translateText("agent_logout_notif"); ?>", "", "success");
										}else{
											sweetAlert("<?php $lh->translateText("emergency_logout"); ?>",data, "warning");
										}
									}
								}); 
							} else {     
	                			swal("<?php $lh->translateText("cancelled"); ?>", "<?php $lh->translateText("cancel_msg"); ?>", "error");   
	                		} 
	                	}
	                );
					
				});
				
	// ------------------
				
		/*********
		** Delete Event
		*********/
			//delete user 
				$(document).on('click','.delete-T_user',function() {
					var id = $(this).attr('data-id');
					swal({
						title: "<?php $lh->translateText("are_you_sure"); ?>",
						text: "<?php $lh->translateText("action_cannot_be_undone"); ?>",
						type: "warning",
						showCancelButton: true, 
						confirmButtonColor: "#DD6B55", 
						confirmButtonText: "<?php $lh->translateText("confirm_delete_user"); ?>", 
						cancelButtonText: "<?php $lh->translateText("cancel_please"); ?>", 
						closeOnConfirm: false,
						closeOnCancel: false
					},
					function(isConfirm){
						if (isConfirm) {
							$.ajax({
								url: "./php/DeleteTelephonyUser.php",
								type: 'POST',
								data: { 
								userid: id,
								log_user: '<?=$_SESSION['user']?>',
								log_group: '<?=$_SESSION['usergroup']?>'
							},
							success: function(data) {
								console.log(data);
								if(data == 1){
									swal({title: "<?php $lh->translateText("delete_user_success"); ?>",text: "<?php $lh->translateText("user_has_been_deleted"); ?>",type: "success"},function(){window.location.href = 'telephonyusers.php';});
								}else{
									sweetAlert("<?php $lh->translateText("delete_user_failed"); ?>", "<?php $lh->translateText("something_went_wrong"); ?> "+data, "error");
								}
							}
							});
						} else {     
						swal("<?php $lh->translateText("cancelled"); ?>", "<?php $lh->translateText("cancel_msg"); ?>", "error");   
						}
					}
					);
				});
			
			//delete phone
				$(document).on('click','.delete-phone',function() {
				 	var id = $(this).attr('data-id');
					var log_user = '<?=$_SESSION['user']?>';
					var log_group = '<?=$_SESSION['usergroup']?>';
	                swal({   
	                    title: "<?php $lh->translateText("are_you_sure"); ?>",
						text: "<?php $lh->translateText("action_cannot_be_undone"); ?>",
	                    type: "warning",   
	                    showCancelButton: true,   
	                    confirmButtonColor: "#DD6B55",   
	                    confirmButtonText: "<?php $lh->translateText("confirm_delete_phone"); ?>",   
	                    cancelButtonText: "<?php $lh->translateText("cancel_please"); ?>",   
	                    closeOnConfirm: false,   
	                    closeOnCancel: false 
	                    }, 
	                    function(isConfirm){   
	                        if (isConfirm) { 
							$.ajax({
							  url: "./php/DeleteSettingsPhones.php",
							  type: 'POST',
							  data: { 
								exten_id: id,
								log_user: log_user,
								log_group: log_group
							  },
							  success: function(data) {
									// console.log(data);
									if(data == 1){
										swal({title: "<?php $lh->translateText("delete_phone_success"); ?>",text: "<?php $lh->translateText("phone_has_been_deleted"); ?>",type: "success"},function(){window.location.href = 'telephonyusers.php';});
									}else{
										sweetAlert("<?php $lh->translateText("cancel_please"); ?>", "<?php $lh->translateText("delete_phone_failed"); ?>"+data, "error");
									}
								}
							});
	                        } else {     
	                                swal("<?php $lh->translateText("cancelled"); ?>", "<?php $lh->translateText("something_went_wrong"); ?>", "error");   
	                        } 
	                    }
	                );
				});

	// -------------------------
	
		$(document).on('change','#user_group',function() {
			if($("#user_group").val() == "ADMIN" || $("#user_group").val() == "SUPERVISOR"){
				$("#password").attr('maxlength','20');
				$("#password").val('Go'+<?php echo date("Y");?>);
			}else {
				$("#password").attr('maxlength','10');
				$("#password").val('Go'+<?php echo date("Y");?>);
			}
		});
	
		// disable special characters on User ID
		$('#user_form').bind('keypress', function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9]+$");
		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		    if (!regex.test(key)) {
		       event.preventDefault();
		       return false;
		    }
		});

		// disable special characters on Fullname for Users
		$('#fullname').bind('keypress', function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 ]+$");
		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		    if (!regex.test(key)) {
		       event.preventDefault();
		       return false;
		    }
		});

		// disable special characters on phone extension
			$('#phone_ext').bind('keypress', function (event) {
			    var regex = new RegExp("^[0-9]+$");
			    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
			    if (!regex.test(key)) {
			       event.preventDefault();
			       return false;
			    }
			});

		// disable special characters on Fullname for Phones
			$('#pfullname').bind('keypress', function (event) {
			    var regex = new RegExp("^[a-zA-Z0-9 ]+$");
			    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
			    if (!regex.test(key)) {
			       event.preventDefault();
			       return false;
			    }
			});

		/*********
		** validations
		*********/
		// check duplicates
			$("#user_form").keyup(function() {
				clearTimeout($.data(this, 'timer'));
				var wait = setTimeout(validate_user, 500);
				$(this).data('timer', wait);
			});

			function validate_user(){
				var user_form_value = $('#user_form').val();
				var phone_logins_value = "";
		        if(user_form_value != ""){
				    $.ajax({
					    url: "php/checkUser.php",
					    type: 'POST',
					    data: {
					    	user : user_form_value,
					    	phone_login : phone_logins_value
					    },
						success: function(data) {
							console.log(data);
							if(data == "success"){
								checker = 0;
								$( "#user_form" ).removeClass("error");
								$( "#user-duplicate-error" ).text( "<?php $lh->translateText("dup_check_success"); ?>" ).removeClass("error").addClass("avail");
							}else{
								if(data == "user"){
									$( "#user_form" ).removeClass("valid").addClass( "error" );
									$( "#user-duplicate-error" ).text( "<?php $lh->translateText("dup_check_error"); ?>" ).removeClass("avail").addClass("error");
								}
								
								checker = 1;
							}
						}
					});
				}
			}

		// form review
		function show_form_review(){
			$('#submit-usergroup').text($('#user_group').val());
			$('#submit-userid').text($('#user_form').val());
			$('#submit-fullname').text($('#fullname').val());
			$('#submit-password').text("******");

			if($('#status').val() == "Y"){
				$('#submit-active').text("YES");
			}else{
				$('#submit-active').text("NO");
			}
		}

	// -------------------------

		/*********
		** On Action Events
		*********/

		/* additional number custom */
			$('#seats').on('change', function() {
				if(this.value == "custom_seats") {
				  $('#custom_seats').show();
				}
				if(this.value != "custom_seats") {
				  $('#custom_seats').hide();
				}
			});

		/* initialize select2 */
			$('.select2-1').select2({
		        theme: 'bootstrap'
		    });
		   
		    //document.on("jqueryui-configure-dialog", function(e) { e.allowInteraction.push(".select2-1"); });
		
	});
	
</script>
		
		<?php print $ui->creamyFooter();?>
    </body>
</html>
