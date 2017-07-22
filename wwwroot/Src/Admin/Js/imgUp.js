$(function(){
    //头像上传
    var options = {
        thumbBox: '.thumb-box',
        spinner: '.spinner',
        imgSrc: '',
        btnClass:'#btnCrop'
    }
    var cropper = $('.image-box').cropbox(options);
    $('#upload-file').on('change', function() {
        //调用弹出层
        $('.img-up').modal('show');

        var reader = new FileReader();
        reader.onload = function(e) {
            options.imgSrc = e.target.result;
            cropper = $('.image-box').cropbox(options);
            setTimeout(function(){$("#btnCrop").click();},400);//上传后自动裁切
        }
        reader.readAsDataURL(this.files[0]);
        //this.files = [];
    })
    $('#btnCrop').on('click', function() {
        var img = cropper.getDataURL();
        $('.cropped').html('');
        $('.cropped').append('<img src="' + img + '" class="min"><p>50*50</p>');
        $('.cropped').append('<img src="' + img + '" class="medium"><p>100*100</p>');
        $('.cropped').append('<img src="' + img + '" class="max"><p>150*150</p>');
    })
    $('#btnZoomIn').on('click', function() {
        cropper.zoomIn();
    })
    $('#btnZoomOut').on('click', function() {
        cropper.zoomOut();
    })
    $("#imgUpCancel").on('click',function(){
        $('.img-up').modal('hide');
        $('#upload-file').val("");
    })
    //保存图片
    $("#imgUpApprove").on('click',function(){
        var img = cropper.getDataURL();
        var This = $(this);
        $.post(XMD.APP + This.attr("data-url"),{is_stream:1,uploadFile:img},function(data){
            var data = $.parseJSON(data);
            if(data.status){
                $('.img-up').modal('hide');
                $("#imgUp").attr("src",data.data.url);
                $('#upload-file').val("");
                $('#upload-file').attr({"data-img_id":data.data.img_id,"data-url":data.data.url});
                $.succeedAlert(data.msg);
            }else{
                $('#upload-file').removeAttr("data-img_id");
                $('#upload-file').removeAttr("data-url");
                $.errorAlert(data.msg);
            }
        })
    })
    //弹出层
    $('.img-up').modal({
        closable:false
    });
})
