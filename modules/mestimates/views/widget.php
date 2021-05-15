<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$mestimates = [];
if (is_staff_member()) {
   $this->load->model('mestimates/mestimates_model');
   $mestimates = $this->mestimates_model->get_all_mestimates();
}
?>
<div class="widget<?php if(count($mestimates) == 0 || !is_staff_member()){echo ' hide';} ?>" id="widget-<?php echo create_widget_id('mestimates'); ?>">
   <?php if(is_staff_member()){ ?>
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body padding-10">
                  <div class="widget-dragger"></div>
                  <p class="padding-5">
                     <?php echo _l('mestimates'); ?>
                  </p>
                  <hr class="hr-panel-heading-dashboard">
                  <?php foreach($mestimates as $mestimate){
                     ?>
                     <div class="mestimate padding-5 no-padding-top">
                        <h4 class="pull-left font-medium no-mtop">
                           <?php echo $mestimate['mestimate_type_name']; ?>
                           <br />
                           <small><?php echo $mestimate['subject']; ?></small>
                        </h4>
                        <h4 class="pull-right bold no-mtop text-success text-right">
                           <?php echo $mestimate['achievement']['total']; ?>
                           <br />
                           <small><?php echo _l('mestimate_achievement'); ?></small>
                        </h4>
                        <div class="clearfix"></div>
                        <div class="progress no-margin progress-bar-mini">
                           <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $mestimate['achievement']['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $mestimate['achievement']['percent']; ?>">
                           </div>
                        </div>
                        <p class="text-muted pull-left mtop5"><?php echo _l('mestimate_progress'); ?></p>
                        <p class="text-muted pull-right mtop5"><?php echo $mestimate['achievement']['percent']; ?>%</p>
                     </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   <?php } ?>
</div>
