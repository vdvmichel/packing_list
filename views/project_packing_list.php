<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php    
if(is_admin()){ ?>
    <div class="row">
      <div class="col-md-12">
        <button class="btn btn-primary" id="packing_list_add_new_btn"><?php echo _l('add_new'); ?></button>
        <a href="<?php echo admin_url('packing_list/pdf/'.$project->id); ?>" target="_blank" class="btn btn-primary" id=""><?php echo _l('PDF'); ?></a>
      </div>
    </div>

      <hr class="hr-panel-heading" />
<?php } ?>

<div class="row mtop10">
    <div class="col-md-12" id="">


        <table class="table table-project_packing_list scroll-responsive">
           <thead>
              <tr>
                <th><?php echo _l('invoice_table_item_heading'); ?></th>
                 <th><?php echo _l('invoice_table_item_description'); ?></th>
                 <th><?php echo _l('invoice_table_quantity_heading'); ?></th>
                 <?php if(is_admin()){ ?>
                 <th><?php echo _l('invoice_table_rate_heading'); ?></th>
                 <th><?php echo _l('invoice_table_amount_heading'); ?></th>
             <?php } ?>
                 
              </tr>
           </thead>
           <tbody></tbody>

            <?php if(is_admin()){ ?>
           <tfoot>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="total"></td>
                
              </tr>
           </tfoot>
           <?php } ?>
        </table>
    </div>
</div>
<input type="hidden" id="packing_list_project_id" value="<?php echo $project->id; ?>">


<div class="modal fade" id="packing_list_modal" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $CI = &get_instance();
                        $CI->load->model('invoice_items_model');
                        $items = $CI->invoice_items_model->get_grouped();
                        //echo render_select('packing_list[item_id]',$items,array('id','description'), _l('add_item'));
                        ?>
                        <div class="form-group packing_list_items-wrapper">
                            <div class="packing_list_items-select-wrapper">
                                <label for="packing_list[item_id]"><?php echo _l('add_item'); ?></label>
                                <br/>
                             <select name="packing_list[item_id]" class="selectpicker" id="packing_list[item_id]" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" data-width="100%">
                              <option value=""></option>
                              <?php foreach($items as $group_id=>$_items){ ?>
                              <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?>">
                               <?php foreach($_items as $item){ ?>
                               <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'],0,200)).'...'; ?>">(<?php echo app_format_number($item['rate']); ; ?>) <?php echo $item['description']; ?></option>
                               <?php } ?>
                             </optgroup>
                             <?php } ?>
                           </select>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php
                        echo render_input('packing_list[description]', _l('invoice_table_item_description'));
                        ?>
                    </div>
                    <div class="col-md-12">
                        <?php
                        echo render_textarea('packing_list[long_description]', _l('invoice_item_long_description'));
                        ?>
                    </div>
                    <div class="col-md-12">
                        <?php
                        echo render_input('packing_list[qty]', _l('invoice_table_quantity_heading'),'','number',['min'=>1,'required' => 'required']);
                        ?>
                    </div>
                    <div class="col-md-12">
                        <?php
                        echo render_input('packing_list[rate]', _l('invoice_table_rate_heading'),'','number');
                        ?>
                    </div>
                    
                    <input type="hidden" name="packing_list_id" value="">
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_packing_list"><?php echo _l('Save'); ?></button>
                <button type="button" class="btn btn-default" id="" data-dismiss="modal"><?php echo _l('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>
