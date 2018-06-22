/**
 * Created by jixiang on 2018/5/8.
 */
var uploadImg = function(url, domIdArray){
    var
        uploadStatus = false;
        formData = new FormData();

    domIdArray.forEach(function(item){
        formData.append(item, $("#"+item)[0].files[0]);
    });
    $.ajax({
        url: url,
        type: 'POST',
        cache: false,
        data: formData,
        async : false,
        processData: false,
        contentType: false
    }).done(function(res) {
        var obj = $.parseJSON(res);
        if(obj.status == 'fail') {
            uploadStatus = false;
        } else {
            domIdArray.forEach(function(item){
                $('#'+item).attr('type','text');
                $('#'+item).val(obj.data[item]);
            });
            uploadStatus = true;
        }
    }).fail(function(res) {

    });
    return uploadStatus;
};