
//write by joris
$(function(){
    $("input.datepicker" ).datepicker({'dateFormat':'dd/mm/yy' ,'changeMonth': true,'changeYear': true });

    $("select.select2").select2({'width':'100%'});

    $("select.select2[multiple]").select2({
      templateResult: function formatState (state) {
        if (!state.id) { return state.text; }
        if(state.selected){
          var $state = $('<span><input type="checkbox" checked/>' + state.text + '</span>');
        }else{
          var $state = $('<span><input type="checkbox"/>' + state.text + '</span>');
        }
        return $state;
      }
    });

    $('body').on('click','.lego-edit-in-place',function(){
        $(this).hide();
        $('#span-in-'+ $(this).attr('data-column-name') + '-'+ $(this).attr('data-item-id')).show();
        $('#input-'+ $(this).attr('data-column-name') + '-'+ $(this).attr('data-item-id')).focus();

        $("select.select2").select2();
    });

    $('body').on('click','.lego-edit-in-place-close',function(){
        var columnName = $(this).attr('data-column-name');
        var id = $(this).attr('data-item-id');
        var span = $('#span-'+ columnName + '-' + id);
        var span_in = $('#span-in-'+ columnName + '-' + id);
        span.show();
        span_in.hide();
    });

    $('body').on('click','.lego-edit-in-place-eraser',function(){
        $('#input-'+ $(this).attr('data-column-name') + '-'+ $(this).attr('data-item-id')).val(null);
        $(this).siblings('.lego-edit-in-place-ok').click();
    });    

    $('body').on('click','.lego-edit-in-place-bool',function(){
        var elm = $(this);
        var id = elm.attr('data-item-id');
        var columnName = elm.attr('data-column-name');
        var val = (parseInt(elm.attr('data-value')) > 0)? 0:1;
        var reload = ($(this).attr('data-reload'))? $(this).attr('data-reload'):'td';
        var line = ($(this).attr('data-line'))? $(this).attr('data-line'):null;
        $.ajax({
          method: "POST",
          url: $(this).attr('data-target'),
          data: { id: id, columnName: columnName,value: val,cls: '',reload: reload },
          dataType: "json",
        }).done(function( retour ) {
            if(retour.code == 'NOK'){
                alert('Une erreur est survenue ('+retour.err+')');
            }else{
              if(reload == 'tr' && line){
                $('#'+line).replaceWith(retour.val);
                $("select.select2").select2();
              }else{
                if(retour.val == 1 || retour.val == "1" || retour.val == "oui" || retour.val == 'true'){
                  elm.removeClass('fa-square-o');
                  elm.addClass('fa-check-square-o');
                  elm.attr('data-value',1);
                }else{
                  elm.removeClass('fa-check-square-o');
                  elm.addClass('fa-square-o');
                  elm.attr('data-value',0);
                }
              }

            }
        }).fail(function( error ){
            elm.html('<i style="color:red" class="fa fa-warning"></i>');
        });
    });

    $('body').on('click','.lego-edit-in-place-ok',function(){
        var elm = $(this);
        elm.html('<i class="fa fa-spinner"></i>');
        var id = $(this).attr('data-item-id');
        var callback = $(this).attr('data-callback');
        var columnName = $(this).attr('data-column-name');
        var cls = $(this).attr('data-class');
        var reload = ($(this).attr('data-reload'))? $(this).attr('data-reload'):'td';
        var line = ($(this).attr('data-line'))? $(this).attr('data-line'):null;
        var input = $('#input-'+ columnName + '-' + id);
        var val = 0;
        if(input.attr('type') == 'checkbox'){
            val = input.is(':checked')
        }else{
            val = input.val();
        }
        var span = $('#span-'+ columnName + '-' + id);
        var span_in = $('#span-in-'+ columnName + '-' + id);
        $.ajax({
          method: "POST",
          url: $(this).attr('data-target'),
          data: { id: id, columnName: columnName,value: val,cls: cls,reload: reload },
          dataType: 'json',
        }).done(function( retour ) {
            if(retour.code == 'NOK'){
                elm.html('<i style="color:red" class="fa fa-check-circle"></i> ('+retour.err+')');
                input.val(retour.val);
            }else{
                if(reload == 'tr' && line){
                  $('#'+line).replaceWith(retour.val);
                  $("select.select2").select2();
                }else{
                  elm.html('<i class="fa fa-check-circle"></i>');
                  input.val(retour.val);
                  span_in.hide();
                  if(retour.val) span.html(retour.val); else span.html('<em>&nbsp;</em>');
                  span.show();
                }
            }
            if(callback) window[callback](retour,elm);
        }).fail(function( error ){
            elm.html('<i style="color:red" class="fa fa-warning"></i>');
        });
    });

});

var lego = {
    post: function(url, params, callback, errorCallback){

        $.ajax({
            type        : 'POST',
            url         : url,
            data        : params,
            dataType    : 'json',
            success     : function(data) {
                callback(data);
            },
            error : function(xhr, ajaxOptions, thrownError){
                if (errorCallback) errorCallback(elm, xhr, ajaxOptions, thrownError);
            }
        });
    },

    filter_set_button: function(id, id_button, val) {

        $(id).val(val);
        $(id_button + ' .fa').hide();
        $(id_button + ' .'+val).show();
        return false;
    },

    success: function(message){
        $('<div>'+message+'</div>').dialog({title:'Success'});
    },

    error: function(message){
        $('<div>'+message+'</div>').dialog({title:'Error'});
    }
};

if(jsa) {
    jsa.evt.reloadLine = function (elm, data) {
        $('#' + elm.attr('data-line')).replaceWith(data);
    };

    jsa.evt.deleteLine = function (elm, data) {
        if (data.status == 'ok') {
            $('#' + elm.attr('data-line')).hide();
        } else {
            this.error(data.message);
        }
    };
}