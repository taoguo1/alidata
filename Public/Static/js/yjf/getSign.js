/**
 * Created by jixiang on 2018/5/6.
 */
var getSign = function(url, data, formDom){
    var traversalDom = function(obj){
        $.each(obj,function(key,value){
            formDom.append(
                '<input type="hidden" name="'+key+'" value="'+value+'" />'
            )
        });
    };
    if(data === undefined || data === '') {
        data = {};
    }
    $.ajax({
        type : "post",
        url : url,
        data : data,
        async : false,
        success : function(item, status){
            var obj = $.parseJSON(item);
            if(obj.status == 'fail') {
                alert(obj.msg);
            } else{
                traversalDom(obj);
                $("#idFront").remove();
                $("#idBack").remove();
                formDom.submit();
            }
        }
    });

};
