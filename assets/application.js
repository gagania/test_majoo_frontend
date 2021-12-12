function add_data(baseUrl, ctrlName, id,funcName) {
    if (!funcName || funcName === '') {
        funcName ='add';
    }
    if (id && id != '') {
        window.location.replace(baseUrl+ctrlName+"/"+funcName+"/"+id);
    } else {
        window.location.replace(baseUrl+ctrlName+"/"+funcName);
    }
    
}
function add_data_main(baseUrl, ctrlName, funcName,menuLink,id) {
    if (!funcName || funcName === '') {
        funcName ='add';
    }
    if (id && id != '') {
        window.location.replace(baseUrl+ctrlName+"/"+funcName+"/"+menuLink+'/'+id);
    } else {
        window.location.replace(baseUrl+ctrlName+"/"+funcName+"/"+menuLink);
    }
    
}

function check_pass(formName) {
    var valid = true;
    if ($("#user_pass").val() !== '' || $("#user_pass_retype").val() !== '') {
        if ($("#user_pass_retype").val() !== '') {
            if ($("#user_pass").val() !== $("#user_pass_retype").val()) {
                alert('Password dont match');
                valid = false;
            }
        } else {
            alert('Retype Password');
            valid = false;
        }
    }
    if (valid) {
        $("#"+formName+"").submit();
    }
}
function add_menu(baseUrl, id) {
    if (id && id != '') {
        window.location.replace(baseUrl+"menu/add/"+id);
    } else {
        window.location.replace(baseUrl+"menu/add");
    }
    
}

function add_content(baseUrl, id) {
    if (id && id != '') {
        window.location.replace(baseUrl+"content/add/"+id);
    } else {
        window.location.replace(baseUrl+"content/add");
    }
    
}

function backpage(baseUrl,ctrl){
    window.location.replace(baseUrl+ctrl+"/index");
}


function process_data(baseUrl,controller,formName,id) {
    $(".overlay").show();
    $.ajax({
        url : baseUrl+controller+"/paging",
        type: "POST",
        dataType:'json',
        data : $('#'+formName+'').serialize() ,
        success: function(data)
        {
            if (data['result']) {
                $(".overlay").hide();
                $("#"+id+" > tbody").html('');
                $("#"+id+" > tbody").html(data['template']);
                if (data['pnumber'] === 0) {
                    data['pnumber'] = 1;
                }
                $("#limit").val(data['limit']);
                $(".pnumber").val(data['pnumber']);
                $(".pnumber").html(data['pnumber']);
                $("#totaldata").val(data['totaldata']);
                $("#totaldata_view").text('of '+Math.ceil(data['totaldata']/20));
                initPaging();
            }
        }
    });
}

function process_outlet_data(baseUrl,controller,formName,id) {
    $(".overlay").show();
    $.ajax({
        url : baseUrl+controller+"/paging_outlet",
        type: "POST",
        dataType:'json',
        data : $('#'+formName+'').serialize() ,
        success: function(data)
        {
            if (data['result']) {
                $(".overlay").hide();
                $("#"+id+" > tbody").html('');
                $("#"+id+" > tbody").html(data['template']);
                if (data['pnumber'] === 0) {
                    data['pnumber'] = 1;
                }
                $("#limit").val(data['limit']);
                $(".pnumber").val(data['pnumber']);
                $(".pnumber").html(data['pnumber']);
                $("#totaldata").val(data['totaldata']);
                $("#totaldata_view").text('of '+Math.ceil(data['totaldata']/20));
                initPaging();
            }
        }
    });
}

function process_outlet_all_data(baseUrl,controller,formName,id) {
    $(".overlay").show();
    $.ajax({
        url : baseUrl+controller+"/paging_outlet_all",
        type: "POST",
        dataType:'json',
        data : $('#'+formName+'').serialize() ,
        success: function(data)
        {
            if (data['result']) {
                $(".overlay").hide();
                $("#"+id+" > tbody").html('');
                $("#"+id+" > tbody").html(data['template']);
                if (data['pnumber'] === 0) {
                    data['pnumber'] = 1;
                }
                $("#limit").val(data['limit']);
                $(".pnumber").val(data['pnumber']);
                $(".pnumber").html(data['pnumber']);
                $("#totaldata").val(data['totaldata']);
                $("#totaldata_view").text('of '+Math.ceil(data['totaldata']/20));
                initPaging();
            }
        }
    });
}

function get_outlets_by_merchant(baseUrl,controller,obj) {
    $(".overlay").show();
    $.ajax({
        url : baseUrl+controller+"/merchant_outlets",
        type: "POST",
        dataType:'json',
        data : {merchantid:$(obj).val()},
        success: function(data)
        {
            if (data['success']) {
                $(".overlay").hide();
                $("#outlet_id").html('');
                $("#outlet_id").html(data['template']);
            } else {
                $("#outlet_id").html('');
            }
        }
    });
}