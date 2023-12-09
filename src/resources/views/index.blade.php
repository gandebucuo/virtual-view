<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Redis-View</title>
    <link href='https://www.redis.net.cn/Application/Home/View/Public/img/icon.png' rel='shortcut icon' />
    <link rel="stylesheet" href="http://images.sugoujx.com/blog/bootstrap/css/bootstrap.min.css"  crossorigin="anonymous">
    <style>
        li{
            cursor: pointer;
            list-style-type: none;
            margin:5px 0;
        }
        li.active {
            background-color: #ecf5ff;
        }
        tr.active{
            background-color: #ecf5ff;
        }

        #table-container {
            height: 450px; /* 设置容器高度 */
            overflow-y: auto; /* 启用垂直滚动 */
        }

        #table-container2 {
            height: 320px; /* 设置容器高度 */
            overflow-y: auto; /* 启用垂直滚动 */
        }

        th {
            position: sticky;
            top: 0;
            z-index: 2;
            padding: 12px; /* 调整为你需要的内边距大小 */
        }

        ::-webkit-scrollbar {
            width: 3px; /* 设置滚动条宽度 */
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888; /* 设置滑块颜色 */
            border-radius: 4px; /* 设置滑块圆角 */
        }

        ::-webkit-scrollbar-track {
            background-color: #f1f1f1; /* 设置滚动条轨道颜色 */
        }

        /* 设置滚动条高度 */
        ::-webkit-scrollbar {
            height: 100px;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 18px;
            text-align: left;
        }

        .styled-table th, .styled-table td {
            padding: 4px 15px;
            border-bottom: 1px solid #ddd;
        }

        .styled-table th {
            background-color: #f2f2f2;
        }

        .styled-table tbody tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
<div class="container" style="max-width:1440px">
    <div class="row">
        <div class="col-2">
            @for ($i = 0; $i < 16; $i++)
                <div>
                    <li onclick="highlight(this,{{$i}})">
                        <svg t="1701755804116" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5609" width="20" height="20"><path d="M772.655 181.527C707.49 153.6 614.4 139.637 512 139.637c-102.4 0-195.49 13.963-260.655 41.89-74.472 32.582-111.709 69.818-111.709 111.71v432.872c0 41.891 37.237 83.782 111.71 111.71C316.508 870.4 409.6 884.363 512 884.363s195.49-13.964 260.655-41.891c69.818-27.928 111.709-69.818 111.709-111.71V293.237c0-41.89-37.237-79.127-111.71-111.709z m-41.891 190.837c-60.51 23.272-144.291 32.581-218.764 32.581-79.127 0-158.255-9.309-218.764-32.581-69.818-18.619-102.4-46.546-102.4-79.128 0-27.927 37.237-55.854 102.4-79.127 60.51-18.618 139.637-27.927 218.764-27.927 74.473 0 158.255 13.963 218.764 32.582 65.163 23.272 102.4 51.2 102.4 79.127 0 27.927-32.582 55.854-102.4 74.473z m4.654 134.981C674.91 525.964 591.128 539.927 512 539.927c-79.127 0-158.255-13.963-223.418-32.582-65.164-23.272-102.4-51.2-102.4-79.127v-51.2c46.545 18.618 79.127 41.891 130.327 51.2 60.51 13.964 125.673 23.273 195.491 23.273s134.982-9.31 195.49-23.273c51.2-9.309 83.783-32.582 130.328-51.2v55.855c0 23.272-37.236 51.2-102.4 74.472z m0 148.946C674.91 674.909 591.128 688.873 512 688.873c-79.127 0-158.255-13.964-223.418-32.582-65.164-18.618-102.4-46.546-102.4-79.127v-69.819c46.545 23.273 79.127 41.891 130.327 55.855 60.51 13.964 125.673 23.273 195.491 23.273s134.982-9.31 195.49-23.273c51.2-13.964 83.783-32.582 130.328-55.855v69.819c0 32.581-37.236 60.509-102.4 79.127zM512 837.818c-79.127 0-158.255-13.963-223.418-32.582-65.164-18.618-102.4-46.545-102.4-74.472v-69.819c46.545 23.273 79.127 41.891 130.327 55.855 55.855 13.964 125.673 23.273 190.836 23.273 69.819 0 134.982-9.31 195.491-23.273 55.855-13.964 88.437-32.582 134.982-55.855v69.819c0 27.927-37.236 55.854-102.4 79.127C674.91 828.509 586.473 837.818 512 837.818z" fill="#21A3DD" p-id="5610"></path></svg>
                        db{{$i}}
                    </li>
                </div>
            @endfor
        </div>
        <div class="col-10" id="table-container">
        </div>
    </div>
    <div class="row"  style="display: none" id="bottom">
        <div class="col-2">
        </div>
        <div class="col-10">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation" id="keys">
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">生存时间</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
            </div>
        </div>
    </div>

</div>

<script src="http://images.sugoujx.com/blog/bootstrap/js/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="http://images.sugoujx.com/blog/bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
var redis_db = 0;
var searchKey = '搜索名称';
var redis_key = '';
var redis_key_type  = '';
var laravel_redis_key = '';

$(document).ready(function() {
    // 绑定键盘事件
    $(document).on('keydown', function(event) {
        // 检查按下的键的键码
        var keyCode = event.keyCode;
        searchKey = $("#search").val();
        if (keyCode === 13) {
            renderData("redis-view/redis_keys_index?db="+redis_db+"&searchKey="+searchKey)
        }
    });
});


//redis库选择高亮显示
function highlight(clickedElement,db) {
    $('.alert').alert()
    // 移除之前所有 <li> 的 active 类
    var allLiElements = document.querySelectorAll('li');
    allLiElements.forEach(function(li) {
        li.classList.remove('active');
    });

    // 为被点击的 <li> 添加 active 类
    clickedElement.classList.add('active');
    redis_db  = db;
    searchKey = '搜索名称';
    renderData("redis-view/redis_keys_index?db="+redis_db);
    $("#bottom").hide();
}



//redis名称选择高亮显示
function trHighlight(clickedElement,key,name) {
    $('#pills-profile-tab').removeClass('active ');
    $('#pills-profile').removeClass('active ');

    // 移除之前所有 <li> 的 active 类
    var allTrElements = document.querySelectorAll('tr');
    allTrElements.forEach(function(tr) {
        tr.classList.remove('active');
    });

    redis_key = key;
    // 为被点击的 <li> 添加 active 类
    clickedElement.classList.add('active');

    $.ajax({
        type:"get",
        url:"redis-view/redis_key_show?db=" + redis_db + "&key=" + key,
        dataType:"json",
        success:function(data){
            const res = data.data;
            redis_key_type = res.key_type;
            let html = '';
            let bottom = '<button class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">';
            switch (res.key_type) {
                case 'string':
                    bottom += '字符串';
                    html += '<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">\n' +
                            '     <div class="form-group row">\n' +
                            '          <label for="inputEmail3" class="col-sm-1 col-form-label">键名</label>\n' +
                            '          <div class="col-sm-11">\n' +
                            '               <input type="email" class="form-control" id="inputEmail3"  placeholder="'+name+'" disabled>\n' +
                            '          </div>\n' +
                            '     </div>\n' +
                            '     <div class="form-group row">\n' +
                            '          <label class="col-sm-1 col-form-label" for="exampleFormControlTextarea1">键值</label>\n' +
                            '          <div class="col-sm-10">\n' +
                            '               <textarea class="form-control" id="val" rows="5">'+res.val+'</textarea>\n' +
                            '          </div>\n' +
                            '          <div class="col-sm-1">\n' +
                            '               <button type="submit" class="btn btn-primary my-1 btn-sm" id="save" onclick="save(1)">应用</button>\n' +
                            '               <button type="submit" class="btn btn-primary my-1 btn-sm" id="refresh"  onclick="refresh()">刷新</button>\n' +
                            '          </div>'+
                            '     </div>\n' +
                            '</div>\n';
                    break;
                case 'list':
                    bottom += '列表';
                    html += '<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">\n' +
                        '     <div class="form-group row">\n' +
                        '          <label for="inputEmail3" class="col-sm-1 col-form-label">键名</label>\n' +
                        '          <div class="col-sm-11">\n' +
                        '               <input type="email" class="form-control" id="val" placeholder="'+name+'"  disabled>\n' +
                        '          </div>\n' +
                        '     </div>\n' +
                        '     <div class="form-group row">\n' +
                        '          <label class="col-sm-1 col-form-label" for="exampleFormControlTextarea1"></label>\n' +
                        '          <div class="col-sm-10" id="table-container2">\n' +
                        '               <table class="styled-table">\n' +
                        '                      <thead>\n' +
                        '                             <tr>\n' +
                        '                                 <th>值</th>\n' +
                        '                                 <th style="width: 200px">操作</th>\n' +
                        '                             </tr>\n' +
                        '                      </thead>\n' +
                        '                      <tbody>\n';
                    for(var i=0;i<res.val.length;i++){
                        html += '<tr id="trList'+i+'">\n' +
                            '    <td contenteditable="true" id="td'+i+'">'+res.val[i]+'</td>'+
                            '    <td>\n' +
                            '        <button type="submit" class="btn btn-primary my-1 btn-sm" onclick=save(2,'+i+')>应用</button>\n' +
                            '        <button type="submit" class="btn btn-danger my-1 btn-sm" onclick=del(1,'+i+')>删除</button>\n' +
                            '    </td>\n' +
                            '</tr>\n';
                    }
                    html += '                      </tbody>\n' +
                        '               </table>\n' +
                        '          </div>\n' +
                        '          <div class="col-sm-1">\n' +
                        '               <button type="submit" class="btn btn-primary my-1" id="refresh"  onclick="refresh()">刷新</button>\n' +
                        '          </div>'+
                        '     </div>\n' +
                        '</div>\n';
                    break;
                case 'hash':
                    bottom += '哈希';
                    html += '<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">\n' +
                        '     <div class="form-group row">\n' +
                        '          <label for="inputEmail3" class="col-sm-1 col-form-label">键名</label>\n' +
                        '          <div class="col-sm-11">\n' +
                        '               <input type="email" class="form-control" id="val" placeholder="'+name+'"  disabled>\n' +
                        '          </div>\n' +
                        '     </div>\n' +
                        '     <div class="form-group row">\n' +
                        '          <label class="col-sm-1 col-form-label" for="exampleFormControlTextarea1"></label>\n' +
                        '          <div class="col-sm-10" id="table-container2">\n' +
                        '               <table class="styled-table">\n' +
                        '                      <thead>\n' +
                        '                             <tr>\n' +
                        '                                 <th>域</th>\n' +
                        '                                 <th>值</th>\n' +
                        '                                 <th style="width: 200px">操作</th>\n' +
                        '                             </tr>\n' +
                        '                      </thead>\n' +
                        '                      <tbody>\n';
                    let index_id = 0;
                    let value_id = 0;
                    $.each(res.val, function(index,value){
                        index_id++;
                        value_id++;
                        html += '<tr id="trList'+index_id+'">\n' +
                            '    <td contenteditable="true" id="tdIndex'+index_id+'">'+index+'</td>'+
                            '    <td contenteditable="true" id="tdValue'+value_id+'">'+value+'</td>'+
                            '    <td>\n' +
                            '        <button type="submit" class="btn btn-primary my-1 btn-sm" onclick=save(3,"'+index+'","'+value+'",'+index_id+','+value_id+')>应用</button>\n' +
                            '        <button type="submit" class="btn btn-danger my-1 btn-sm" onclick=del(2,'+index_id+',"'+index+'")>删除</button>\n' +
                            '    </td>\n' +
                            '</tr>\n';
                    });
                    html += '                      </tbody>\n' +
                        '               </table>\n' +
                        '          </div>\n' +
                        '          <div class="col-sm-1">\n' +
                        '               <button type="submit" class="btn btn-primary my-1" id="refresh"  onclick="refresh()">刷新</button>\n' +
                        '          </div>'+
                        '     </div>\n' +
                        '</div>\n';
                    break;
                case 'set':
                    bottom += '集合';
                    html += '<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">\n' +
                        '     <div class="form-group row">\n' +
                        '          <label for="inputEmail3" class="col-sm-1 col-form-label">键名</label>\n' +
                        '          <div class="col-sm-11">\n' +
                        '               <input type="email" class="form-control" id="val" placeholder="'+name+'"  disabled>\n' +
                        '          </div>\n' +
                        '     </div>\n' +
                        '     <div class="form-group row">\n' +
                        '          <label class="col-sm-1 col-form-label" for="exampleFormControlTextarea1"></label>\n' +
                        '          <div class="col-sm-10" id="table-container2">\n' +
                        '               <table class="styled-table">\n' +
                        '                      <thead>\n' +
                        '                             <tr>\n' +
                        '                                 <th>值</th>\n' +
                        '                                 <th style="width: 200px">操作</th>\n' +
                        '                             </tr>\n' +
                        '                      </thead>\n' +
                        '                      <tbody id="tbodys">\n';
                    for(var i=0;i<res.val.length;i++){
                        html += '<tr id="trList'+i+'">\n' +
                            '    <td contenteditable="true" id="td'+i+'">'+res.val[i]+'</td>'+
                            '    <td>\n' +
                            '        <button type="submit" class="btn btn-primary my-1 btn-sm" onclick=save(4,'+i+','+res.val[i]+')>应用</button>\n' +
                            '        <button type="submit" class="btn btn-danger my-1 btn-sm" onclick=del(3,'+i+','+res.val[i]+')>删除</button>\n' +
                            '    </td>\n' +
                            '</tr>\n';
                    }
                    html += '                      </tbody>\n' +
                        '               </table>\n' +
                        '          </div>\n' +
                        '          <div class="col-sm-1">\n' +
                        '               <button type="submit" class="btn btn-primary my-1" id="refresh"  onclick="refresh()">刷新</button>\n' +
                        '          </div>'+
                        '     </div>\n' +
                        '</div>\n';
                    break;
                case 'zset':
                    bottom += '有序集合';
                    html += '<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">\n' +
                        '     <div class="form-group row">\n' +
                        '          <label for="inputEmail3" class="col-sm-1 col-form-label">键名</label>\n' +
                        '          <div class="col-sm-11">\n' +
                        '               <input type="email" class="form-control" id="val" placeholder="'+name+'"  disabled>\n' +
                        '          </div>\n' +
                        '     </div>\n' +
                        '     <div class="form-group row">\n' +
                        '          <label class="col-sm-1 col-form-label" for="exampleFormControlTextarea1"></label>\n' +
                        '          <div class="col-sm-10" id="table-container2">\n' +
                        '               <table class="styled-table">\n' +
                        '                      <thead>\n' +
                        '                             <tr>\n' +
                        '                                 <th>分值</th>\n' +
                        '                                 <th>成员</th>\n' +
                        '                                 <th style="width: 200px">操作</th>\n' +
                        '                             </tr>\n' +
                        '                      </thead>\n' +
                        '                      <tbody>\n';
                    let index_i_i = 0;
                    let value_i_i = 0;
                    $.each(res.val, function(index,value){
                        index_i_i++;
                        value_i_i++;
                        html += '<tr id="trList'+value_i_i+'">\n' +
                                '    <td contenteditable="true" id="tdValue'+value_i_i+'">'+value+'</td>'+
                                '    <td contenteditable="true" id="tdIndex'+index_i_i+'">'+index+'</td>'+
                                '    <td>\n' +
                            '        <button type="submit" class="btn btn-primary my-1 btn-sm" onclick=save(5,"'+index+'","'+value+'",'+index_i_i+','+value_i_i+')>应用</button>\n' +
                                '        <button type="submit" class="btn btn-danger my-1 btn-sm" onclick=del(4,'+index_i_i+',"'+index+'")>删除</button>\n' +
                                '    </td>\n' +
                                '</tr>\n';
                    });
                    html += '                      </tbody>\n' +
                        '               </table>\n' +
                        '          </div>\n' +
                        '          <div class="col-sm-1">\n' +
                        '               <button type="submit" class="btn btn-primary my-1" id="refresh"  onclick="refresh()">刷新</button>\n' +
                        '          </div>'+
                        '     </div>\n' +
                        '</div>\n';
                    break;
            }
            bottom += '</button>';
            html += '<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">\n' +
                    '     <div class="form-group form-check">\n' +
                    '          <input type="checkbox" class="form-check-input" id="expireCheck" onclick="isExpire()">\n' +
                    '          <label class="form-check-label" for="exampleCheck1">过期</label>\n' +
                    '     </div>\n' +
                    '     <div class="form-group row">\n' +
                    '          <label for="inputEmail3" class="col-sm-2 col-form-label">生存时间（秒）</label>\n' +
                    '          <div class="col-sm-10">\n' +
                    '               <input type="email" class="form-control" id="expireTime" value="'+res.expire_time+'" disabled>\n' +
                    '          </div>\n' +
                    '     </div>\n' +
                    '     <div class="form-group row">\n' +
                    '          <label for="inputEmail3" class="col-sm-2 col-form-label"></label>\n' +
                    '          <div class="col-sm-10">\n' +
                    '               <button type="submit" class="btn btn-primary my-1" id="expireSave" onclick="expireSave()" disabled>应用</button>\n' +
                    '               <button type="submit" class="btn btn-primary my-1" id="refresh"  onclick="refresh()">刷新</button>\n' +
                    '          </div>\n' +
                    '      </div>\n' +
                    '</div>\n';

            $('#keys').empty();
            $('#keys').append(bottom);


            $('#pills-tabContent').empty();
            $('#pills-tabContent').append(html);
            $("#bottom").show();
        }
    });
}

function save(type,index,value,index_i,value_i){
    let param = '';
    switch (type) {
        //字符串
        case 1:
            param +=  "&val=" + $("#val").val();
            break;
        //列表
        case 2:
            param += "&val=" + $('#td'+index).html()  + "&index=" + index;
            break;
        //哈希
        case 3:
            param += "&old_value=" + value + "&new_value=" + $('#tdValue'+value_i).html() + "&old_index=" + index + "&new_index=" + $('#tdIndex'+index_i).html();
            break;
        //集合
        case 4:
            param += "&old_value=" + value + "&new_value=" + $('#td'+index).html();
            break;
        //有序集合
        case 5:
            param += "&old_value=" + value + "&new_value=" + $('#tdValue'+value_i).html() + "&old_index=" + index + "&new_index=" + $('#tdIndex'+index_i).html();
            break;
        default:
            break;
    }
    if(param){
        $.ajax({
            type:"get",
            url:"redis-view/redis_val_save?db=" + redis_db + "&key=" + redis_key + "&key_type=" + redis_key_type + param,
            dataType:"json",
            success:function(data){

            }
        });
    }
}

function del(type,index,value) {
    $('#trList'+index).remove();
    let param = '';
    switch (type) {
        //列表
        case 1:
            param += "&index=" + index;
            break;
        //哈希
        case 2:
            param +=  "&index=" + value;
            break;
        //集合
        case 3:
            param +=  "&value=" + value;
            break;
        //有序集合
        case 4:
            param +=  "&index=" + value;
            break;
    }
    if(param){
        $.ajax({
            type:"get",
            url:"redis-view/redis_del?db=" + redis_db + "&key=" + redis_key + "&key_type=" + redis_key_type  + param,
            dataType:"json",
            success:function(data){

            }
        });
    }
}

//过期时间选择是否展示
function isExpire() {
    var isChecked = $("#expireCheck").prop("checked");
    $("#expireTime").prop("disabled", isChecked?false:true);
    $("#expireSave").prop("disabled", isChecked?false:true);
}

//过期时间保存
function expireSave(){
    const expire_time = $("#expireTime").val();
    $.ajax({
        type:"get",
        url:"redis-view/redis_expire?db=" + redis_db + "&key=" + redis_key  + "&expire_time=" + expire_time,
        dataType:"json",
        success:function(data){

        }
    });
}

//刷新
function refresh() {
    $.ajax({
        type:"get",
        url:"redis-view/redis_val_refresh?db=" + redis_db + "&key=" + redis_key + "&key_type=" + redis_key_type,
        dataType:"json",
        success:function(data){

        }
    });
}

//渲染redis键值列表
function renderData($url){
    $.ajax({
        type:"get",
        url:$url,
        dataType:"json",
        success:function(data){
            const arr = data.data;
            let table = '<table class="table table-hover">\n' +
                '  <thead>\n' +
                '    <tr>\n' +
                '      <th scope="col" style="width: 400px">名称</th>\n' +
                '      <th scope="col" style="width: 100px">类型</th>\n' +
                '      <th scope="col" style="width: 100px">长度</th>\n' +
                '      <th scope="col" style="width:200px"><input type="text" id="search" class="form-control" placeholder="'+searchKey+'"></th>\n' +
                '    </tr>\n' +
                '  </thead>\n' +
                '  <tbody id="tbody1">';
            for(var i=0;i<arr.length;i++){
                table  +=      '                <tr onclick="trHighlight(this,\''+arr[i]['key']+'\',\''+arr[i]['name']+'\')">\n' +
                    '                    <td style="width: 400px"><svg t="1701755704450" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2848" width="20" height="20"><path d="M959.744 602.16l0.256 0.064v101.952c0 10.24-10.752 21.44-35.072 35.84-22.976 13.696-91.968 47.616-163.328 82.624l-35.712 17.536c-65.088 32-126.016 62.208-149.184 76.032-52.8 31.36-82.048 31.104-123.712 8.32-41.6-22.72-305.28-144.256-352.704-170.176-23.744-12.992-36.224-23.936-36.224-34.24v-103.424c0.384 10.368 12.48 21.248 36.224 34.24C147.776 676.8 411.328 798.4 452.992 821.12c41.664 22.784 70.912 23.04 123.712-8.32 52.672-31.36 300.416-147.712 348.224-176.128 23.232-13.824 34.56-24.768 34.88-34.56l-0.064 0.064z m0-168.576h0.192v101.952c0 10.24-10.752 21.44-35.072 35.968-47.808 28.416-295.552 144.768-348.224 176.128-52.8 31.36-82.048 31.04-123.712 8.32-41.6-22.72-305.28-144.32-352.704-170.24C76.48 572.8 64 561.92 64 551.536v-103.424c0.384 10.24 12.48 21.248 36.224 34.176 47.488 25.92 311.04 147.52 352.704 170.24 41.664 22.72 70.912 23.04 123.712-8.32 52.672-31.36 300.416-147.712 348.224-176.192 23.168-13.824 34.56-24.704 34.88-34.432zM462.656 81.84c55.36-22.72 74.56-23.488 121.664-3.776 47.168 19.776 293.376 131.648 339.968 151.104 24 10.048 35.84 19.2 35.456 29.632H960v101.952c0 10.176-10.816 21.44-35.072 35.904C877.056 425.072 629.376 541.44 576.64 572.8c-52.736 31.36-81.984 31.104-123.648 8.32-41.664-22.656-305.28-144.32-352.768-170.24C76.544 397.936 64 387.056 64 376.688V273.28c-0.32-10.304 11.072-19.968 34.368-30.464 46.656-20.8 308.8-138.24 364.288-160.896v-0.064z m129.792 238.4l-207.552 36.352 144.832 68.608 62.72-104.96z m128.704-113.6l-135.936 61.44 122.688 55.36 13.376-5.952 122.752-55.424-122.88-55.424z m-392.32 13.44c-61.248 0-110.912 22.016-110.912 49.152 0 27.072 49.664 49.088 110.976 49.088s110.912-21.952 110.912-49.088-49.6-49.088-110.912-49.088l-0.064-0.064z m134.656-101.888l20.096 42.304-66.88 27.52 89.6 9.216 28.032 53.248 17.408-47.744 77.632-9.216-60.16-25.728 16-43.712-59.136 22.08-62.592-27.968z" fill="#D82A1F" p-id="2849"></path></svg>'+arr[i]['name']+'</td>\n' +
                    '                    <td style="width: 100px">'+arr[i]['type']+'</td>\n' +
                    '                    <td style="width: 100px">'+arr[i]['size']+'</td>\n' +
                    '                    <td style="width: 200px"></td>\n' +
                    '                </tr>\n';
            }
            table +=     '                </tbody>\n' +
                '            </table>';
            // 移除子元素
            $('#table-container').empty();
            // 添加新的子元素
            $('#table-container').append(table);
        }
    });
}
</script>
</body>
</html>
