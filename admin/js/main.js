/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function load_list_ajax(
        container_id,
        form_id,
        additional_data)
{
    var data_orgin;
    if (form_id)
    {
        data_orgin = $('#' + form_id).serialize() + additional_data;
    } else
    {
        data_orgin = additional_data;
    }

    $.ajax({
        type: 'GET',
        url: 'router.php',
        data: data_orgin,
        beforeSend: function () {
            $('#' + container_id).html('<div class="text-center" style="min-height: 100px; margin: auto;"><div class="spinner-border  text-primary" style="width: 4rem; height: 4rem;" role="status"><span class="sr-only">Loading...</span></div></div>');
        },
        success: function (response) {
            $('#' + container_id).html(response);
        },
        error: function (errorThrown, status, error) {
            console.log(status + error + errorThrown.responseText);
        }
    })
}
function reset_form(
        form_id,
        hidden_fields)
{
    $('#' + form_id)[0].reset();
    $("#" + hidden_fields).val('');
}

function save_ajax(
        _button,
        _form,
        _customer_id,
        _container_id,
        _additional_data)
{
    event.preventDefault();

    var $button = '#save-contact';
    var $form = '#customer-contact-form';

    $.ajax({
        type: 'POST',
        url: 'router.php',
        data: $($form).serialize() + '&section=customercontact&action=save&customer_id=' + customer_id,
        beforeSend: function () {
            $('#'+_button).html('<i class="fa fa-spinner fa-spin"></i> Guardando');
        },
        success: function (response) {
            console.log(response);
            $($button).html('Guardar');
            $("#customer_contact_id").val(response);
            load_customer_contact_list();
            $('#customer-contact-form-modal').modal('hide');
        },
        error: function (errorThrown, status, error) {
            alert("Error: No se pudo guardar la información");
            console.log(status + error + errorThrown.responseText);
        }
    })

}

function delete_ajax(
        container_id,
        form_id,
        additional_data)
{
    event.preventDefault();

    var result = confirm("Want to delete?");
    if (result)
    {
        var data_orgin;
        if (form_id)
        {
            data_orgin = $('#' + form_id).serialize() + additional_data;
        } else
        {
            data_orgin = additional_data;
        }
        //Logic to delete the item

        $.ajax({
            type: 'POST',
            url: 'router.php',
            data: data_orgin,
            //dataType: "json",
            beforeSend: function () {
                $('#' + container_id).html('<div class="text-center" style="min-height: 100px; margin: auto;"><div class="spinner-border  text-primary" style="width: 4rem; height: 4rem;" role="status"><span class="sr-only">Loading...</span></div></div>');
            },
            success: function (datar) {
                load_customer_identity_documents_list();
            },
            error: function (errorThrown, status, error) {
                console.log(status + error + errorThrown.responseText);
            }
        })
    }
}