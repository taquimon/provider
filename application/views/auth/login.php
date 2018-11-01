<?= link_tag(site_url().'css/bootstrap-cerulean.min.css')?>
<?php echo form_open("auth/login");?>
<div style="text-align:left;">
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4"><h1><?php echo lang('login_heading');?></h1><p><?php echo lang('login_subheading');?></p></div>
    <div class="col-md-2"></div>
  </div>
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4"><div id="infoMessage"><?php echo $message;?></div></div>
    <div class="col-md-2"></div>
  </div>

  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-2"><?php echo lang('login_identity_label', 'identity');?></div>
    <div class="col-md-4"><?php echo form_input($identity, '', 'class="form-control" placeholder="identity" id="identity"');?></div>
    <div class="col-md-2"></div>
  </div>
    
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-2"><?php echo lang('login_password_label', 'password');?></div>
    <div class="col-md-4"><?php echo form_input($password, '', 'class="form-control" placeholder="password" id="password"');?></div>
    <div class="col-md-2"></div>
 </div>

 <div class="row">
   <div class="col-md-4"></div>
   <div class="col-md-4">    
    <?php echo lang('login_remember_label', 'remember');?>
    <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
   </div>
    <div class="col-md-2"></div>
 </div>

  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <?php echo form_submit('submit', lang('login_submit_btn'), "class='btn btn-minimize btn-round btn-primary'");?>
    </div>
    <div class="col-md-2"></div>
  </div>
</div>  
<?php echo form_close();?>
</div>
<!--<p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>-->