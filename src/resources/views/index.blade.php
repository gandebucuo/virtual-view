<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css"  crossorigin="anonymous">
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
    </style>
</head>
<body>
<div class="container" style="max-width:1440px">
    <div class="row">
        <div class="col-3">
            @for ($i = 0; $i < 16; $i++)
                <div>
                    <li onclick="highlight(this,{{$i}})">
                        <svg t="1701755804116" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5609" width="20" height="20"><path d="M772.655 181.527C707.49 153.6 614.4 139.637 512 139.637c-102.4 0-195.49 13.963-260.655 41.89-74.472 32.582-111.709 69.818-111.709 111.71v432.872c0 41.891 37.237 83.782 111.71 111.71C316.508 870.4 409.6 884.363 512 884.363s195.49-13.964 260.655-41.891c69.818-27.928 111.709-69.818 111.709-111.71V293.237c0-41.89-37.237-79.127-111.71-111.709z m-41.891 190.837c-60.51 23.272-144.291 32.581-218.764 32.581-79.127 0-158.255-9.309-218.764-32.581-69.818-18.619-102.4-46.546-102.4-79.128 0-27.927 37.237-55.854 102.4-79.127 60.51-18.618 139.637-27.927 218.764-27.927 74.473 0 158.255 13.963 218.764 32.582 65.163 23.272 102.4 51.2 102.4 79.127 0 27.927-32.582 55.854-102.4 74.473z m4.654 134.981C674.91 525.964 591.128 539.927 512 539.927c-79.127 0-158.255-13.963-223.418-32.582-65.164-23.272-102.4-51.2-102.4-79.127v-51.2c46.545 18.618 79.127 41.891 130.327 51.2 60.51 13.964 125.673 23.273 195.491 23.273s134.982-9.31 195.49-23.273c51.2-9.309 83.783-32.582 130.328-51.2v55.855c0 23.272-37.236 51.2-102.4 74.472z m0 148.946C674.91 674.909 591.128 688.873 512 688.873c-79.127 0-158.255-13.964-223.418-32.582-65.164-18.618-102.4-46.546-102.4-79.127v-69.819c46.545 23.273 79.127 41.891 130.327 55.855 60.51 13.964 125.673 23.273 195.491 23.273s134.982-9.31 195.49-23.273c51.2-13.964 83.783-32.582 130.328-55.855v69.819c0 32.581-37.236 60.509-102.4 79.127zM512 837.818c-79.127 0-158.255-13.963-223.418-32.582-65.164-18.618-102.4-46.545-102.4-74.472v-69.819c46.545 23.273 79.127 41.891 130.327 55.855 55.855 13.964 125.673 23.273 190.836 23.273 69.819 0 134.982-9.31 195.491-23.273 55.855-13.964 88.437-32.582 134.982-55.855v69.819c0 27.927-37.236 55.854-102.4 79.127C674.91 828.509 586.473 837.818 512 837.818z" fill="#21A3DD" p-id="5610"></path></svg>
                        db{{$i}}
                    </li>
                </div>
            @endfor
        </div>
        <div class="col-9" id="table">

        </div>
    </div>
</div>
<script src="../bootstrap/js/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="../bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
    var redis_db = 0;
    var searchKey = '搜索名称';
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

    //redis库高亮显示
    function highlight(clickedElement,db) {
        // 移除之前所有 <li> 的 active 类
        var allLiElements = document.querySelectorAll('li');
        allLiElements.forEach(function(li) {
            li.classList.remove('active');
        });

        // 为被点击的 <li> 添加 active 类
        clickedElement.classList.add('active');
        redis_db  = db;
        searchKey = '搜索名称';
        renderData("redis-view/redis_keys_index?db="+redis_db)
    }

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
                    '      <th scope="col">名称</th>\n' +
                    '      <th scope="col">类型</th>\n' +
                    '      <th scope="col">长度</th>\n' +
                    '      <th scope="col" style="width:200px"><input type="text" id="search" class="form-control" placeholder="'+searchKey+'"></th>\n' +
                    '    </tr>\n' +
                    '  </thead>\n' +
                    '  <tbody>';
                for(var i=0;i<arr.length;i++){
                    table  +=      '                <tr onclick="trHighlight(this,\''+arr[i]['key']+'\')">\n' +
                        '                    <td><svg t="1701755704450" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2848" width="20" height="20"><path d="M959.744 602.16l0.256 0.064v101.952c0 10.24-10.752 21.44-35.072 35.84-22.976 13.696-91.968 47.616-163.328 82.624l-35.712 17.536c-65.088 32-126.016 62.208-149.184 76.032-52.8 31.36-82.048 31.104-123.712 8.32-41.6-22.72-305.28-144.256-352.704-170.176-23.744-12.992-36.224-23.936-36.224-34.24v-103.424c0.384 10.368 12.48 21.248 36.224 34.24C147.776 676.8 411.328 798.4 452.992 821.12c41.664 22.784 70.912 23.04 123.712-8.32 52.672-31.36 300.416-147.712 348.224-176.128 23.232-13.824 34.56-24.768 34.88-34.56l-0.064 0.064z m0-168.576h0.192v101.952c0 10.24-10.752 21.44-35.072 35.968-47.808 28.416-295.552 144.768-348.224 176.128-52.8 31.36-82.048 31.04-123.712 8.32-41.6-22.72-305.28-144.32-352.704-170.24C76.48 572.8 64 561.92 64 551.536v-103.424c0.384 10.24 12.48 21.248 36.224 34.176 47.488 25.92 311.04 147.52 352.704 170.24 41.664 22.72 70.912 23.04 123.712-8.32 52.672-31.36 300.416-147.712 348.224-176.192 23.168-13.824 34.56-24.704 34.88-34.432zM462.656 81.84c55.36-22.72 74.56-23.488 121.664-3.776 47.168 19.776 293.376 131.648 339.968 151.104 24 10.048 35.84 19.2 35.456 29.632H960v101.952c0 10.176-10.816 21.44-35.072 35.904C877.056 425.072 629.376 541.44 576.64 572.8c-52.736 31.36-81.984 31.104-123.648 8.32-41.664-22.656-305.28-144.32-352.768-170.24C76.544 397.936 64 387.056 64 376.688V273.28c-0.32-10.304 11.072-19.968 34.368-30.464 46.656-20.8 308.8-138.24 364.288-160.896v-0.064z m129.792 238.4l-207.552 36.352 144.832 68.608 62.72-104.96z m128.704-113.6l-135.936 61.44 122.688 55.36 13.376-5.952 122.752-55.424-122.88-55.424z m-392.32 13.44c-61.248 0-110.912 22.016-110.912 49.152 0 27.072 49.664 49.088 110.976 49.088s110.912-21.952 110.912-49.088-49.6-49.088-110.912-49.088l-0.064-0.064z m134.656-101.888l20.096 42.304-66.88 27.52 89.6 9.216 28.032 53.248 17.408-47.744 77.632-9.216-60.16-25.728 16-43.712-59.136 22.08-62.592-27.968z" fill="#D82A1F" p-id="2849"></path></svg>'+arr[i]['name']+'</td>\n' +
                        '                    <td>'+arr[i]['type']+'</td>\n' +
                        '                    <td>'+arr[i]['size']+'</td>\n' +
                        '                </tr>\n';
                }
                table +=     '                </tbody>\n' +
                    '            </table>';
                // 移除子元素
                $('#table').empty();
                // 添加新的子元素
                $('#table').append(table);
            }
        });
    }
    function trHighlight(clickedElement,key) {
        // 移除之前所有 <li> 的 active 类
        var allTrElements = document.querySelectorAll('tr');
        allTrElements.forEach(function(tr) {
            tr.classList.remove('active');
        });

        // 为被点击的 <li> 添加 active 类
        clickedElement.classList.add('active');
    }
</script>
</body>
</html>
