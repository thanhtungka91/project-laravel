<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.fileupload.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class = 'panel-body'>
        {{--use laravel form --}}
        {!! Form::model(null,['method' => 'post','route' => ['course.create'],'id'=>'mainForm']) !!}
        <td>
            {!! Form::label('講座名　※必須') !!}
            {!! Form::text('course_name', null, [ 'class' => 'form-control']) !!}
        </td>
        <td>
            {!! Form::label('開催日時') !!}
            {!! Form::text('start_time', null, [ 'class' => 'form-control']) !!}
        </td>
        <td>
            {!! Form::label('カリキュラム名　※必須') !!}
            {!! Form::text('subject_name', null, [ 'class' => 'form-control']) !!}
        </td>
        <td>
            {!! Form::label('カリキュラム概要') !!}
            {!! Form::textarea('subject_overview', null, [ 'class' => 'form-control']) !!}
        </td>
        <td>
            {!! Form::label('講師名　※必須') !!}
            {!! Form::text('instructor_name', null, [ 'class' => 'form-control']) !!}
        </td>
        <td>
            {!! Form::label('講師プロフィール') !!}
            {!! Form::textarea('instructor_infor', null, [ 'class' => 'form-control']) !!}
        </td>
        <td>
            {!! Form::label('動画登録　※必須') !!}
            {!! Form::file('video', null, [ 'class' => 'form-control']) !!}
            ※ファイル形式：.MPEG4、.MOV、.MP4.WMV<br>
            ※ファイルサイズ：○○以下<br>
        </td>
        <td>
            {!! Form::label('サムネイル登録') !!}
            {!! Form::file('thumbnail', null, [ 'class' => 'form-control']) !!}
            ※ファイル形式：.JPEG、.PNG<br>
            ※ファイルサイズ：○○MB以下、横●●pix*縦●●pix <br>
        </td>
        <td>
            {!! Form::label('スライド資料登録') !!}
            {!! Form::file('slide', null, [ 'class' => 'form-control']) !!}
            ※ファイル形式：.PDF<br>
            ※ファイルサイズ：○○MB以下 <br>
        </td>
        <td>
            <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Add files...</span>
                    <!-- The file input field used as target for the file upload widget -->
            <input id="fileupload" type="file" name="files[]" multiple>
            </span>
            <br>
            <br>
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success"></div>
            </div>
            <div id="files" class="files"></div>
            <br>
        </td>
        <td>
            {!! Form::label('スライド資料登録') !!}
            <br>
            {!!Form::select('public', array(true => 'Public', false => 'Private'), 'Private')!!}
        </td>
        <div class="body">
            {!! Form::submit('Register!') !!}
        </div>

        {!! Form::close() !!}
    </div>

</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="/js/jquery.ui.widget.js"></script>
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="/js/jquery.iframe-transport.js"></script>
<script src="/js/jquery.fileupload.js"></script>
<script src="/js/jquery.fileupload-process.js"></script>
<script src="/js/jquery.fileupload-image.js"></script>
<script src="/js/jquery.fileupload-audio.js"></script>
<script src="/js/jquery.fileupload-video.js"></script>
<script src="/js/jquery.fileupload-validate.js"></script>
<script>
    $(function () {
        'use strict';
        var url = 'uploadfile',
            uploadButton = $('<button/>')
                .addClass('btn btn-primary')
                .prop('disabled', true)
                .text('Processing...')
                .on('click', function () {
                    var $this = $(this),
                            data = $this.data();
                    $this
                            .off('click')
                            .text('Abort')
                            .on('click', function () {
                                $this.remove();
                                data.abort();
                            });
                    data.submit().always(function () {
                        $this.remove();
                    });
                });
        $('#fileupload').fileupload({
            maxNumberOfFiles: 1,
            url: url,
            dataType: 'json',
            autoUpload:true,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            maxFileSize: 1000000,
            disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator.userAgent),
            previewMaxWidth: 100,
            previewMaxHeight: 100,
            previewCrop: true
        }).on('fileuploadadd', function (e, data) {
            data.context = $('<div/>').appendTo('#files');
            $.each(data.files, function (index, file) {
                var node = $('<p/>')
                        .append($('<span/>').text(file.name));
                if (!index) {
                    node
                            .append('<br>')
                            .append(uploadButton.clone(true).data(data));
                }
                node.appendTo(data.context);
            });
        }).on('fileuploadprocessalways', function (e, data) {
            var index = data.index,
                    file = data.files[index],
                    node = $(data.context.children()[index]);
            if (file.preview) {
                node
                        .prepend('<br>')
                        .prepend(file.preview);
            }
            if (file.error) {
                node
                        .append('<br>')
                        .append($('<span class="text-danger"/>').text(file.error));
            }
            if (index + 1 === data.files.length) {
                data.context.find('button')
                        .text('Upload')
                        .prop('disabled', !!data.files.error);
            }
        }).on('fileuploadprogressall', function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
            );
        }).on('fileuploaddone', function (e, data) {
            //get url -> apply when submit the form
            $.each(data.result.files, function (index, file) {
                if (file.url) {
                    alert("create new delete button");
                    var link = $('<a>')
                            .attr('target', '_blank')
                            .prop('href', file.url);
                    $(data.context.children()[index])
                            .wrap(link);
                } else if (file.error) {
                    var error = $('<span class="text-danger"/>').text(file.error);
                    $(data.context.children()[index])
                            .append('<br>')
                            .append(error);
                }
            });
        }).on('fileuploadfail', function (e, data) {
            $.each(data.files, function (index) {
                var error = $('<span class="text-danger"/>').text('File upload failed.');
                $(data.context.children()[index])
                        .append('<br>')
                        .append(error);
            });
        }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

</script>
</body>
</html>
