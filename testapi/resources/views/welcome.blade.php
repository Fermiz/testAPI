@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">抽奖小程序</div>

                <div class="panel-body center">
                <!-- <h3>欢迎使用抽奖小程序</h3> -->
                <div>
                    <form action="{{ url('prize') }}" method="POST" class="form-horizontal center">
                    {{ csrf_field() }}

                    <div id="select-form" class="form-group">
                        <label for="prize" class="col-sm-3 control-label">关联表单</label>

                        <div class="col-sm-6">
                            <select class="select-control"> 
                              @foreach ($forms as $form)
                              <option>{{$form['name']}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="select-name" class="form-group">
                        <label for="prize" class="col-sm-3 control-label">姓名字段</label>

                        <div class="col-sm-6">
                            <select class="select-control"> 
                              @foreach ($forms as $form)
                              <option>{{$form['name']}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="select-phone" class="form-group">
                        <label for="prize" class="col-sm-3 control-label">手机字段</label>

                        <div class="col-sm-6">
                            <select class="select-control"> 
                              @foreach ($forms as $form)
                              <option>{{$form['name']}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="submit" class="btn btn-default">进入抽奖</button>
                        </div>
                    </div>
                </form>
                </div>
                <!-- <div>
                    <a class="brand" href="{{ url('/prizes') }}"><img src="../img/home.png" class="logo" /></a>
                </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
