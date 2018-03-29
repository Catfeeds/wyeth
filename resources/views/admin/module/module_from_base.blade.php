<style>
    .table td{
        max-width: 400px;
        word-break: break-all;
    }
</style>

@foreach ($fields as $k => $f)
@if ($f['input'] != 'hidden')
<div class="form-group" id="{{ $k }}-form-group">
    <label for="{{ $k }}" class="control-label col-lg-1">{{ $f['label'] }} </label>
    @if ($f['input'] == 'text')
    <div class="col-lg-6">
        <input type="text" name="{{ $k }}" id="{{ $k }}" value="{{ $f['default'] }}" class="form-control">
    @elseif ($f['input'] == 'textarea')
    <div class="col-lg-6">
        <textarea rows="5" id="{{ $k }}" name="{{ $k }}" class="form-control">{{$f['default']}}</textarea>
    @elseif ($f['input'] == 'link')
    <div class="col-lg-6">
        <input type="text" name="{{ $k }}" id="{{ $k }}" value="{{ $f['default'] }}" class="form-control linkVerifi" data="1">
        <label class="checkbox-inline" style="height:20px">
            <span class="linkErrorMsg" style="color:red;display:none;">请填写正确的链接 例如: http://www.baidu.com</span>
        </label>
    @elseif ($f['input'] == 'radio')
    <div class="col-lg-10">
        @foreach ($f['values'] as $kk => $vv)
        <label class="checkbox-inline">
            <input type="radio"  name="{{ $k }}" value="{{ $kk }}" @if ($kk == $f['default']) checked="checked" @endif> {{ $vv }}
        </label>
        @endforeach
    @elseif ($f['input'] == 'select')
    <div class="col-lg-6">
        <select class="form-control has-success" name="{{ $k }}" @if (isset($f['linkage'])) hw-select-linkage @endif>
            <!-- <option value="">请选择{{$f['label']}}</option> -->
            @foreach ($f['values'] as $kk => $vv)
            <option value="{{$kk}}" @if ($kk == $f['default']) selected="selected" @endif>{{$vv}}</option>
            @endforeach
        </select>
    @elseif ($f['input'] == 'image')
    <div class="col-md-9">
        <input type="hidden" name="{{ $k }}" value="{{ $f['default'] }}">
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                <img src="@if ($f['default']) {{ $f['default'] }} @else /admin_style/img/no_image.png @endif" alt="">
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
            <div>
               <span class="btn btn-white btn-file">
               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
               <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
               <input type="file" class="default file_prew" name="{{ $k }}">
               </span>
            </div>
        </div>
    @elseif ($f['input'] == 'video')
    <div class="controls col-md-9">
        <div id="container">
            <input class="fileupload-new thumbnail" style="width: 530px; height: 35px;" name="{{ $k }}" id="video" type="text" value="{{ $f['default'] }}">
            <a id="pickfiles" href="#">
                <span class="btn btn-white btn-file">
                    <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择视频文件</span>
                </span>
            </a>

            <table class="fileupload-preview" style=" margin-left:5px; display:inline">
                <thead></thead>
                <tbody id="fsUploadProgress"></tbody>
            </table>
        </div>
    @elseif ($f['input'] == 'subtable')
    <div class="col-lg-10">
        <section id="unseen">
            <div class="row-fluid">
                <div class="clearfix">
                    <div class="btn-group">
                        <a href="/admin/app_config/{{$page}}/{{ $k }}/subtable/0/edit" class="btn btn-primary btn-xs">
                            Add New <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    @foreach ($f['fields'] as $fn => $ff)
                    <th>{{ $ff['label'] }}</th>
                    @endforeach
                    <th >操作</th>
                </tr>
                </thead>
                <tbody>
                    @if ($f['default'])
                    @foreach ($f['default'] as $fk => $fv)
                    <tr id="{{ $k }}_{{ $fk }}">
                        @foreach ($f['fields'] as $fn => $ff)
                        <td>
                            @if ($ff['input'] == 'image')
                                <image src="{{ $fv[$fn] }}" width="40px" />
                            @elseif ($ff['input'] == 'select')
                                {{ $ff['values'][$fv[$fn]] }}
                            @elseif ($ff['input'] == 'radio')
                                {{$ff['values'][$fv[$fn]]}}
                            @else
                                {{ $fv[$fn] }}
                            @endif
                        </td>
                        @endforeach
                        <td>
                            <a href="/admin/app_config/{{$page}}/{{ $k }}/subtable/{{ $fk }}/edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i>编辑</a>
                            <a href="/admin/app_config/{{$page}}/{{ $k }}/subtable/{{ $fk }}/delete" onclick="return confirm('您确定要删除该内容吗？');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i>删除</a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </section>
    @endif
        <span class="help-block">{{ $f['desc'] }}</span>
    </div>
</div>
@endif
<div class="clearfix"></div>
@endforeach
