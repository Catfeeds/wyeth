<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width,user-scalable=no" name="viewport" />
        <link href="{{config('course.static_url')}}/year/css/reset.css?v={{$resource_version}}" rel="styleSheet" type="text/css" />
        <link href="{{config('course.static_url')}}/year/css/mainpc.css?v={{$resource_version}}" rel="styleSheet" type="text/css" />
        <link href="{{config('course.static_url')}}/year/css/swiper.min.css?v={{$resource_version}}" rel="styleSheet" type="text/css" />
        <title>年会</title>
    </head>
    <body>
    <div class="content">
        <header>
            <div class="logo logo_txt"></div>
            <div class="logo"></div>
            <div class="rightArea">
                <div class="boss">
                    <img src="{{config('course.static_url')}}/year/images/pc/robin.png" alt=""/>
                </div>
                <div class="text">
                    <img src="{{config('course.static_url')}}/year/images/pc/text.png" alt=""/><br />
                    Robin
                </div>
                <div class="functionSwitch">

                </div>
            </div>
        </header>
        <div class="livingBox">
            <div class="livingBoxList">
                <div id="barrage"></div>
            </div>
        </div>
        <footer>
            <div class="mic"></div>
            <div class="userList">
                <div class="userListInner" id="avatars">
                    @foreach ($messages as $message)
                        <img src="{{$message->user->avatar}}">
                    @endForeach
                </div>
            </div>
        </footer>
    </div>
    <script src="{{config('course.static_url')}}/year/js/main.min.js"></script>
    <script src="{{config('course.static_url')}}/year/js/pcliving.js?v={{$resource_version}}"></script>
    <script type="text/javascript">
    var uid = {{$user->id}};

    $(document).ready(function(){
        $.getJSON('/token', function (data) {
            if ('token' in data) {
                token = data.token;
                $.ajaxSetup({
                    beforeSend: function(xhr) {
                        if (!token) {
                            console.log('token empty before ajax send');
                            return false;
                        }
                        xhr.setRequestHeader('Authorization', 'bearer ' + token);
                    }
                });
                var options = {
                    uid: uid,
                };
                PcLiving.init(options);
            }
        });
    });
    </script>
    </body>
</html>