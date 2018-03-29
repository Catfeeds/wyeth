<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="/admin_style/flatlab/img/favicon.png">

    <title>主持人后台</title>

    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/admin_style/flatlab/js/html5shiv.js"></script>
    <script src="/admin_style/flatlab/js/respond.min.js"></script>
    <![endif]-->
</head>

<body >

<div class="container">

    <img src="/admin_style/img/anchor_logo.png" style="margin: 40px auto 0; display: block; width: 400px;"/>
    <img src="<?=$anchor_info->avatar?>" style="margin: 10px auto 5px; display: block; width: 80px;"/>
    <p style="text-align: center;"><?=$anchor_info->name?></p>
    <div style="width:400px; margin:20px auto;">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading tab-bg-dark-navy-blue" style="background-color: #EFC849">
                <ul class="nav nav-tabs nav-justified ">
                    <li class="active">
                        <a href="#course_enroll" data-toggle="tab" aria-expanded="true">
                            未开始的课程
                        </a>
                    </li>
                    <li class="">
                        <a href="#course_start" data-toggle="tab" aria-expanded="true">
                            进行中的课程
                        </a>
                    </li>
                    <li class="">
                        <a href="#course_end" data-toggle="tab" aria-expanded="false">
                            已结束的课程
                        </a>
                    </li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content tasi-tab">
                    <div class="tab-pane active" id="course_enroll">
                        <?php foreach($course_enroll as $v) { ?>
                            <article class="media">
                                <a class="pull-left thumb p-thumb" href="/anchor/live/<?=$v->id?>">
                                    <img src="<?=$v->img?>">
                                </a>
                                <div class="media-body">
                                    <a class="cmt-head" href="/anchor/live/<?=$v->id?>"><?=$v->title?></a>
                                    <p> <i class="fa fa-clock-o"></i>
                                        <?=date('Y年m月d日', strtotime($v->start_day))?>
                                        <?=$v->start_time?> ~
                                        <?=$v->end_time;?>
                                    </p>
                                </div>
                            </article>
                        <?php } ?>
                    </div>
                    <div class="tab-pane" id="course_start">
                        <?php foreach($course_start as $v) { ?>
                            <article class="media">
                                <a class="pull-left thumb p-thumb" href="/anchor/live/<?=$v->id?>">
                                    <img src="<?=$v->img?>">
                                </a>
                                <div class="media-body">
                                    <a class="cmt-head" href="/anchor/live/<?=$v->id?>"><?=$v->title?></a>
                                    <p> <i class="fa fa-clock-o"></i>
                                        <?=date('Y年m月d日', strtotime($v->start_day))?>
                                        <?=$v->start_time?> ~
                                        <?=$v->end_time;?>
                                    </p>
                                </div>
                            </article>
                        <?php } ?>
                    </div>
                    <div class="tab-pane" id="course_end">
                        <?php foreach($course_end as $v) { ?>
                            <article class="media" style="position: relative">
                                <a class="pull-left thumb p-thumb" href="/anchor/live/<?=$v->id?>">
                                    <img src="<?=$v->img?>">
                                </a>
                                <div class="media-body">
                                    <a class="cmt-head" href="/anchor/live/<?=$v->id?>"><?=$v->title?></a>
                                    <p> <i class="fa fa-clock-o"></i>
                                        <?=date('Y年m月d日', strtotime($v->start_day))?>
                                        <?=$v->start_time?> ~
                                        <?=$v->end_time;?>
                                    </p>
                                </div>
                                <div style="position: absolute; right: 0; top: 16px;">
                                    <a class="cmt-head" href="/anchor/msg/<?=$v->id?>">消息管理</a>
                                </div>
                            </article>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
        <!--widget end-->
    </div>
</div>
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
</body>
</html>
