var options = {
    type: "post",   //默认是form的method（get or post），如果申明，则会覆盖
    beforeSubmit: beforeCheck, //提交前的回调函数
    success: successFun,  //提交成功后的回调函数
    error:errorFun,
    target: "#output",  //把服务器返回的内容放入id为output的元素中
    dataType: "json", //html(默认), xml, script, json...接受服务端返回的类型
    // clearForm: true,  //成功提交后，是否清除所有表单元素的值
    // resetForm: true,  //成功提交后，是否重置所有表单元素的值
    timeout: 3000     //限制请求的时间，当请求大于3秒后，跳出请求
}
// $('#myForm2').ajaxForm(options)
$("#myForm").submit(function () {
    var index = layer.load(1, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
    });

    $('#myForm').ajaxSubmit(options)

    return false;
})

//表单提交前，数据校验
/*
 * formData：表单元素数组对象，数组里面每一个元素都是一个<input>元素，可以通过.name、.value的方式访问元素
 * 提交表单时，Form插件会以Ajax方式自动提交这些数据，格式如：[{name:user,value:val },{name:pwd,value:pwd}]
 * form: jQuery对象，封装了表单的元素
 * options: options对象
 * */
function beforeCheck(formData, form, options){


    //利用formData校验
    //$.param(formData) 可以和formSerialize方法一样，实现表单元素的序列化
    var queryString = $.param(formData);


    for (var i = 0; i < formData.length; i++) {
        //打印每一个元素的name属性和value值
        //alert(formData[i].name + "  " + formData[i].value)
    }

    //利用form校验
    var formDom = form[0];

    //formDom是jQuery表单对象，感觉类似数组，可以通过下标访问元素
    //比如：formDom[0].targetName 表示元素标签名，INPUT


    //把表单的元素序列化，：name=姓名&age=年龄；以字符串的方式拼接所有表单元素
    var formSerializeStr = $("#myForm").formSerialize();
    // alert(formSerializeStr)
    //序列化某些表单元素 $("#form1 .ss")通过选择器选择某些元素
    //这里就是选择表单里面，有specialFields样式的元素；但是这里我用属性选择器没有生效，比如$("#form1[name='name']")
    var formSerializeStr = $("#myForm .specialFields").fieldSerialize();
    // alert(formSerializeStr)


    return true;//如果return false，则校验不通过，表单不会提交
};

function successFun(data, status){
    //捕获页

    if(data.status == 1){
        layer.msg(data.msg, {icon: 1},function(){
            layer.closeAll('loading')
            window.location.href=document.referrer;

        });
    }else if (data.status == 2){
        layer.msg(data.msg, {icon: 6},function(){
            layer.closeAll('loading')
            window.location.reload();
        });
    }else {
        layer.msg(data.msg, {icon: 2},function(){
            layer.closeAll('loading')
            window.location.reload();
        });
    }


    //data是提交成功后的返回数据，status是提交结果 比如success
    //返回数据的类型是通过options对象里面的dataType定义的，比如json、xml，默认是html

    //这里data.success是因为我后天返回的json数据的一个属性 String jsonText = "{'success':'提交成功'}";


};
function errorFun(XMLHttpRequest, textStatus, errorThrown){
    // 状态码
    console.log(XMLHttpRequest.status);
    // 状态
    console.log(XMLHttpRequest.readyState);
    // 错误信息
    console.log(textStatus);
}

function delOne(_this,table,value) {
    layer.confirm('您真的确定要删除吗？', {
        btn: ['删除','取消'] //按钮
    }, function(){
        var index = layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });
        $.ajax({
            type: "post",
            url: $(_this).attr('data-url'),
            data: {table:table, id:value, '__token__':$("input[name='__token__']").val(),},
            dataType: "json",
            success: function(data){

                if(data.status == 1){
                    layer.msg(data.msg, {icon: 1},function(){
                        layer.closeAll('loading');
                        // $("#"+value).remove();// 目前页面返回
                        window.location.reload();
                    });
                }else if (data.status == 2){
                    layer.msg(data.msg, {icon: 6},function(){
                        layer.closeAll('loading')

                    });
                }else {
                    layer.msg(data.msg, {icon: 2},function(){
                        layer.closeAll('loading')

                    });
                }



            }
        });
    }, function(){
        // layer.msg(已取消, {icon: 5});
    });
}
function setTvalue(_this,table,field,name,id){

    $(_this).bind('blur', function(){
        var index = layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });
        $.ajax({
            url:$(_this).attr('data-url'),
            type:'post',
            dataType:'json',
            data:{
                table:table,
                field:field,
                name:name,
                id:id,
                value:$(_this).val(),
                '__token__':$("input[name='__token__']").val(),
            },
            success:function(res){
                $(_this).unbind('blur');
                $("input").each(function(){
                    if($(this).attr('data-id') != id){
                        $(this).unbind('blur');
                        $(this).unbind('click');
                    }
                });

                if(res.status == 1){
                    layer.msg(res.msg, {icon: 1},function(){
                        layer.closeAll('loading');
                        window.location.reload();

                    });
                }else if (res.status == 2){
                    layer.msg(res.msg, {icon: 6},function(){
                        layer.closeAll('loading')
                        window.location.reload();
                    });
                }else {
                    layer.msg(res.msg, {icon: 2},function(){
                        layer.closeAll('loading')
                        window.location.reload();
                    });
                }
            }
        });
    });

}

// 软删除
function to_del(_this,id){

    layer.confirm('您真的确定要删除吗？', {
        btn: ['删除','取消'], //按钮
        icon:0
    }, function(){
        var index = layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });

        $.ajax({
            url:TO_DEL,
            type:'post',
            dataType:'json',
            data:{
                id:id,
                '__token__':$("input[name='__token__']").val(),
            },
            success:function(res){
                if(res.status == 1){
                    layer.msg(res.msg, {icon: 1},function(){
                        layer.closeAll('loading');
                        // $("#"+value).remove();// 目前页面返回
                        window.location.reload();
                    });
                }else if (res.status == 2){
                    layer.msg(res.msg, {icon: 6},function(){
                        layer.closeAll('loading')
                        window.location.reload();
                    });
                }else {
                    layer.msg(res.msg, {icon: 2},function(){
                        layer.closeAll('loading')
                        window.location.reload();
                    });
                }
            }
        });
    })

}

// 恢复软删除
function to_restore(_this,id){

    layer.confirm('您真的确定要恢复吗？', {
        btn: ['恢复','取消'], //按钮
        icon:3
    }, function(){
        var index = layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });
        $.ajax({
            url:TO_RESTORE,
            type:'post',
            dataType:'json',
            data:{
                id:id,
                '__token__':$("input[name='__token__']").val(),
            },
            success:function(res){
                if(res.status == 1){
                    layer.msg(res.msg, {icon: 1},function(){
                        layer.closeAll('loading');
                        // $("#"+value).remove();// 目前页面返回
                        window.location.reload();
                    });
                }else if (res.status == 2){
                    layer.msg(res.msg, {icon: 6},function(){
                        layer.closeAll('loading')
                        window.location.reload();
                    });
                }else {
                    layer.msg(res.msg, {icon: 2},function(){
                        layer.closeAll('loading')
                        window.location.reload();
                    });
                }
            }
        });
    })

}

// 真实删除
function to_delete(_this,id){

    layer.confirm('您真的确定要彻底删除吗？', {
        btn: ['彻底删除','取消'], //按钮
        icon:3
    }, function(){
        var index = layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });
        $.ajax({
            url:TO_DELETE,
            type:'post',
            dataType:'json',
            data:{
                id:id,
                '__token__':$("input[name='__token__']").val(),
            },
            success:function(res){
                if(res.status == 1){
                    layer.msg(res.msg, {icon: 1},function(){
                        layer.closeAll('loading');
                        // $("#"+value).remove();// 目前页面返回
                        window.location.reload();
                    });
                }else if (res.status == 2){
                    layer.msg(res.msg, {icon: 6},function(){
                        layer.closeAll('loading')
                        window.location.reload();
                    });
                }else {
                    layer.msg(res.msg, {icon: 2},function(){
                        layer.closeAll('loading')
                        window.location.reload();
                    });
                }
            }
        });
    })

}
