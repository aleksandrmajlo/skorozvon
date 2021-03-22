<div class="row">
    <div class="col-md-12">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        @if($error=Session::get('error'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{$error}}</strong>
                </div>
        @endif
        <div class="card">
            <div class="card-header">ЗАГРУЗИТЬ (загрузка EXCEL)</div>
            <div class="card-body">
                <form method="post" action="{{ route('file.upload.excel') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Файл</label>
                        <input type="file" name="file" required class="form-control-file" id="exampleFormControlFile1">
                    </div>
                    <button type="submit" class="btn-primary btn">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
</div>
