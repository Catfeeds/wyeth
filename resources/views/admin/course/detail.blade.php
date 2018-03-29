<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <title>课程详细信息</title>
    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />
</head>

<body>

<section id="container" class="">
    <!--header start-->
    <?php echo $header; ?>
    <!--header end-->
    <!--sidebar start-->
    <?php echo $sidebar; ?>
    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            课程详细信息
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <div class="cmxform form-horizontal tasi-form">
                                    <div class="form-group ">
                                        <table>
                                            <tr>
                                                <td width="375">
                                                    &nbsp&nbsp&nbspid：{{$course->id}}
                                                </td>
                                                <td width="375">
                                                    &nbsp&nbsp&nbsp课程名称：{{$course->title}}
                                                </td>
                                                <td width="375">
                                                    &nbsp&nbsp&nbsp套课cid：{{$course->cid}}
                                                </td>
                                                <td width="375">
                                                    &nbsp&nbsp&nbsp期数：{{$course->number}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="form-group ">
                                        <table>
                                            <tr>
                                                <td width="375">
                                                    &nbsp&nbsp&nbsp课程日期：{{$course->start_day}}
                                                </td>
                                                <td width="375">
                                                    &nbsp&nbsp&nbsp时间：{{$course->start_time}}-{{$course->end_time}}
                                                </td>
                                                <td width="375">
                                                    &nbsp&nbsp&nbsp报名人数：{{$course->realnum}}/{{$course->sign_limit}}
                                                </td>
                                                <td width="375">
                                                    &nbsp&nbsp&nbsp
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group ">
                                        <br>
                                        <label class="control-label col-lg-2">音频地址</label>
                                        <div class="col-lg-6">
                                            @if ($isEnd)
                                                @foreach ($vodLists as $vodList)
                                                    <input type="text" style="width:500px" value="{{$vodList['m3u8']}} ">
                                                    &nbsp&nbsp&nbsp<button data-clipboard-text="{{$vodList['m3u8']}}">复制</button><br><br>
                                                @endforeach
                                            @else
                                                该课程还没有结束！
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <br>
                                        <label class="control-label col-lg-2">腾讯云视频地址</label>
                                        <div class="col-lg-6">
                                            @if ($isEnd)
                                                @if ($qcloudVodList->code == 0)
                                                    @foreach ($qcloudVodList->fileSet as $v)
                                                        <input type="text" style="width:500px" value="{{$v->playSet[0]->url}}">
                                                        &nbsp&nbsp&nbsp<button data-clipboard-text="{{$v->playSet[0]->url}}">复制</button><br><br>
                                                    @endforeach
                                                @else
                                                    返回码：{{$qcloudVodList->code}} 描述：{{$qcloudVodList->message}}
                                                @endif
                                            @else
                                                该课程还没有结束！
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->
    <!--footer start-->
    <?php echo $footer; ?>
    <!--footer end-->
</section>

<script src="/admin_style/flatlab/js/clipboard.min.js"></script>
<!-- <script src="/admin_style/flatlab/js/clipboard.min.js"></script> -->

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="/admin_style/flatlab/js/respond.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>

<script type="text/javascript">
    var btns = document.querySelectorAll('button');
            var clipboard = new Clipboard(btns);

            clipboard.on('success', function(e) {
                console.log(e);
                alert("复制成功");
            });

            clipboard.on('error', function(e) {
                console.log(e);
                alert("复制失败");
            });
</script>
</body>
</html>
