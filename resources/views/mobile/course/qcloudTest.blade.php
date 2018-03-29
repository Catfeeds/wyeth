<html>
    <head>
        <title>qcloudTest!</title>
    </head>
    <body>
        ---------------------qcloudTest!---------------------
        <br><br><br>
        ---------------------<input type="button" value="点播" id="play">---------------------
        <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="{{config('course.static_url')}}/js/buzz.min.js"></script>
        <script>
            var sound = new buzz.sound('http://200025875.vod.myqcloud.com/200025875_7c8996328b8a43d0b913964498b36235.f0.mp4'),
                    sound2 = new buzz.sound('http://200025875.vod.myqcloud.com/200025875_9ef070ea25d74e9f9c3c4337f33c0fa6.f0.mp4');
            var soundGroup = new buzz.group([
                sound
            ]);
            $('#play').on('click', function() {
                soundGroup.play();
            });
        </script>
    </body>
<html>