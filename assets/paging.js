function initPaging() {
    var limitpage = 0;
    limitpage = parseInt($("#limit").val())+10;
    if ($("#totaldata").val() <= limitpage) {
        $(".btn-next").attr("disabled", true);
        $(".btn-last").attr("disabled", true);
    } else {
        $(".btn-next").attr("disabled", false);
        $(".btn-last").attr("disabled", false);
    }
    
    if($('.pnumber').val() == '1'){ 
        $(".btn-first").addClass("hide");
        $(".btn-prev").addClass("hide");
    }else{
        $(".btn-first").removeClass("hide");
        $(".btn-prev").removeClass("hide");
    }
}

function searchdata(id, baseUrl, controllerName,obj,funcName) {
    if (!funcName) {
        funcName = 'paging';
    }
    $.ajax({
        url : baseUrl+controllerName+"/"+funcName,
        type: "POST",
        dataType:'json',
        data : {
                page:'first',
                totaldata:$("#totaldata").val(),
                'pnum':$('.pnumber').val(),
                'search':$(obj).val(),
                'fields':$(obj).attr('field'),
                jml_baris:$("#jml_baris").val()
            },
        success: function(data)
        {
            $("#"+id+" > tbody").html('');
            $("#"+id+" > tbody").html(data['template']);
            $("#limit").val(data['limit']);
            $(".pnumber").val(data['pnumber']);
            $("#totaldata").val(data['totaldata']);
            $("#totaldata_view").text(Math.ceil(data['totaldata']/10));
            initPaging();
        }
    });
}

function searchAccess(id, baseUrl, controllerName,obj,funcName,editFunc) {
    if (!funcName) {
        funcName = 'paging';
    }
//    var searchData = new Array();
//    $('.search_desc').each(function(index, value){
//        search={};
//        search[$(this).attr('field')] = $(this).val();
//        searchData.push(JSON.stringify(search));
//    });

    $.ajax({
        url : baseUrl+controllerName+"/"+funcName,
        type: "POST",
        dataType:'json',
        data : {
                page:'first',
                totaldata:$("#totaldata").val(),
                'pnum':$('.pnumber').val(),
                'search':$(obj).val(),
                'fields':$(obj).attr('field'),
                unit_search:$("#unit_search").val(),
                jml_baris:$("#jml_baris").val()
            },
        success: function(data)
        {
//                $("#static_content").html('');
            $("#"+id+" > tbody").html('');
            $("#"+id+" > tbody").html(data['template']);
            $("#limit").val(data['limit']);
            $(".pnumber").val(data['pnumber']);
            $("#totaldata").val(data['totaldata']);
            $("#totaldata_view").text(Math.ceil(data['totaldata']/10));
            
            $('.'+editFunc+'').click(function () {
                $.ajax({
                    url : baseUrl+controllerName+"/get_data",
                    type: "POST",
                    dataType:'json',
                    data : {
                            id:$(this).data('id')
                        },
                    success: function(data)
                    {
                        if (data['header'].length) {
                            $.each(data['header'][0], function( key, value ) {
                                if ($(".modal-body #"+key+"").length) {
                                    $(".modal-body #"+key+"").val(value);
                                }
                            });
                            
                            $(".modal-body #select2-ctgr_id-container").html(data['header'][0]['ctrg_id']);
                        }
                    }
                });
            });
            initPaging();
        }
    });
}

function updatelist(id, baseUrl, cntlName, page, pagenumber,ctrlName) {
    var pnumber = 0;
    var functionName = '';
    if (pagenumber) {
        pnumber = pagenumber;
    } else {
        pnumber = $('.pnumber').val();
    }
    
    if (ctrlName) {
        functionName = ctrlName;
    } else {
        functionName = 'paging';
    }
    
    $.ajax({
        url : baseUrl+cntlName+"/"+functionName,
        type: "POST",
        dataType:'json',
        data : { limit:$("#limit").val(),
                page:page,
                totaldata:$("#totaldata").val(),
                pnum:pnumber,
                search:$("#search_desc").val(), 
                'fields':$("#search_desc").attr('field')
        },
        success: function(data)
        {
            if ($("#"+id).children("tbody").length > 0) {
                $("#"+id+" > tbody").html('');
                $("#"+id+" > tbody").html(data['template']);
            } 
            else {
                $("#"+id+"").html('');
                $("#"+id+"").html(data['template']);
            }
            
            $("#limit").val(data['limit']);
            $(".pnumber").val(data['pnumber']);
            initPaging();
        }
    });
}
function updatelist_data(id, baseUrl, cntlName, page, pagenumber,ctrlName) {
    var pnumber = 0;
    var functionName = '';
    if (pagenumber) {
        pnumber = pagenumber;
    } else {
        pnumber = $('.pnumber').val();
    }
    
    if (ctrlName) {
        functionName = ctrlName;
    } else {
        functionName = 'paging';
    }
    
    $.ajax({
        url : baseUrl+cntlName+"/"+functionName,
        type: "POST",
        dataType:'json',
        data : { limit:$("#limit").val(),
                page:page,
                totaldata:$("#totaldata").val(),
                pnum:pnumber,
                search:$("#search_desc").val(), 
                'fields':$("#search_desc").attr('field'),
                'user_id':$("#user_id").val(),
                'datefrom':$("#datefrom").val(),
                'dateto':$("#dateto").val(),
                'user_divisi_id':$("#user_divisi_id").val(),
                'user_sub_divisi_id':$("#user_sub_divisi_id").val(),
        },
        success: function(data)
        {
            if ($("#"+id).children("tbody").length > 0) {
                $("#"+id+" > tbody").html('');
                $("#"+id+" > tbody").html(data['template']);
            } 
            else {
                $("#"+id+"").html('');
                $("#"+id+"").html(data['template']);
            }
            console.log(Math.ceil(data['totaldata']/data['limit']));
            $("#limit").val(data['limit']);
            $(".pnumber").val(data['pnumber']);
            $("#totaldata").val(data['totaldata']);
            $("#totaldata_view").text('of ' + Math.ceil(data['totaldata']/$("#limit_page").val()));
            initPaging();
        }
    });
}