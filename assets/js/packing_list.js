$(document).ready(function () {
  
    var project_id = $('#packing_list_project_id').val();
    initDataTable('.table-project_packing_list', admin_url + 'packing_list/table?project_id='+project_id, [], [0], '', ['0', 'DESC']);

    $('.table-project_packing_list').on('draw.dt', function() {
         var Table = $(this).DataTable();
         var sums = Table.ajax.json().sums;
         packing_list_add_common_footer_sums($(this),sums);
       });
});



$('body').on('click' , '#packing_list_add_new_btn', function(){
    $('#packing_list_modal .modal-title').text('Add New');
    $('input[name="packing_list_id"]').val('');
    $('select[name="packing_list[item_id]"]').val('');
    $('input[name="packing_list[description]"]').val('');
    $('textarea[name="packing_list[long_description]"]').val('');
    $('input[name="packing_list[qty]"]').val('');
    $('input[name="packing_list[rate]"]').val('');
    $('select[name="packing_list[item_id]"]').selectpicker('refresh');
    $('#packing_list_modal').modal('show');
});

$('body').on('change' , '#packing_list_modal select[name="packing_list[item_id]"]', function(){
    var item_id = $(this).val();
    const action = admin_url + "packing_list/get_item_data";
    $.ajax(action, {
      type: "POST",
      data: { item_id: item_id },
      async:false,
    }).done(function (data) {
      data = JSON.parse(data);
      $.each(data, function(k, v) {
            $('[name="packing_list['+k+']"]').val(v);
        });

      $('#packing_list_modal .selectpicker').selectpicker('refresh');
    });
});

$('#packing_list_modal #save_packing_list').click(function(){
    var id = $('input[name="packing_list_id"]').val();
    var item_id = $('select[name="packing_list[item_id]"]').val();
    var rate = $('input[name="packing_list[rate]"]').val();
    var qty = $('input[name="packing_list[qty]"]').val();
    var description = $('input[name="packing_list[description]"]').val();
    var long_description = $('textarea[name="packing_list[long_description]"]').val();
    var project_id = $('#packing_list_project_id').val();
    const action = admin_url + "packing_list/create";
    $.ajax(action, {
    type: "POST",
    data: { id: id , item_id: item_id , rate: rate, qty : qty, description : description, long_description : long_description, project_id : project_id}
    }).done(function (data) {
        location.reload();
    });
})

$('body').on('click' , '.packing_list_edit', function(){
    $('#packing_list_modal .modal-title').text('Edit');
    $('input[name="packing_list_id"]').val($(this).attr('data-id'));
    $('select[name="packing_list[item_id]"]').val('');
    $('input[name="packing_list[description]"]').val('');
    $('textarea[name="packing_list[long_description]"]').val('');
    $('input[name="packing_list[qty]"]').val('');
    $('input[name="packing_list[rate]"]').val('');
    $('select[name="packing_list[item_id]"]').selectpicker('refresh');


    var list_id = $(this).attr('data-id');
    const action = admin_url + "packing_list/get_list_data";
    $.ajax(action, {
      type: "POST",
      data: { list_id: list_id },
      async:false,
    }).done(function (data) {
      data = JSON.parse(data);
      $.each(data, function(k, v) {
            $('[name="packing_list['+k+']"]').val(v);
        });

      $('#packing_list_modal .selectpicker').selectpicker('refresh');
    });
    $('#packing_list_modal').modal('show');
});

$('body').on('click','.packing_list_delete',function(){
  if(confirm_delete()){
    var id = $(this).attr('data-id');
      var action =  admin_url + "packing_list/delete";
      $.ajax(action, {
          type: 'POST',
          data: {id : id}

      }).done(function(data){
          location.reload();
      });
  }
});

function packing_list_add_common_footer_sums(table,sums) {
       table.find('tfoot').addClass('bold');
       table.find('tfoot td').eq(0).html("Total");
       table.find('tfoot td.total').html(sums.total);
  }