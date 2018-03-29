<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width,user-scalable=no" name="viewport" />
    <title>年会PPT</title>
</head>
<body>
	<div class="pre" id="pre" style="width:100%; height:20%; position: absolute; top:10%; left:0;background:#000; color:#fff; text-align: center; font-size:3em; ">
        上一页
	</div>
	<div class="next" id="next" style="width:100%; height:20%; position: absolute; top:40%; left:0;background:#000; color:#fff; text-align: center; font-size:3em; ">
	    下一页   
    </div>
<script src="{{config('course.static_url')}}/year/js/jquery-1.8.3.js"></script>
<script src="{{config('course.static_url')}}/mobile/js/lodash.min.js"></script>
<script src="./js/control_ppt.js"></script>
<script type="text/javascript">
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
                uid: 10004,
            };
            Control.init(options);
        }
    });
});
</script>
</body>
</html>