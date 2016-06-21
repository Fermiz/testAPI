@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">抽奖小程序</div>

                <div class="panel-body">
                    <!-- <h3>欢迎使用抽奖小程序</h3>
                -->
                <div>
                <button id="nochoice" class="btn btn-primary btn-lg hide" data-toggle="modal" 
                               data-target="#formModal">
                               选择表单
                        </button>
                    <form action="{{ url('prize') }}" method="POST" class="form-horizontal">
                        <div id="select-form" class="form-group center">
                            <label for="prize" class="col-sm-3 control-label">关联表单</label>
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <input name ="access_token" id="access_token" type="hidden" value="{{$token}}">
                            <input name ="me" id="me" type="hidden" value="{{$me}}">
                            <div class="col-sm-6">
                                <select name="form" id="selectForm" class="select-control">
                                    <option value="nochoice" selected="selected">请选择表单</option>
                                    @foreach ($forms as $form)
                                    <option value="{{$form['token']}}">{{$form['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="select-name" class="form-group hide">
                            <label for="prize" class="col-sm-3 control-label">姓名字段</label>

                            <div class="col-sm-6">
                                <select name="name" id="selectName" class="select-control"></select>
                            </div>
                        </div>

                        <div id="select-phone" class="form-group hide">
                            <label for="prize" class="col-sm-3 control-label">手机字段</label>

                            <div class="col-sm-6">
                                <select name="phone" id="selectPhone" class="select-control"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button id="begin" type="submit" class="btn btn-default">进入抽奖</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <div>
                <a class="brand" href="{{ url('/prizes') }}">
                    <img src="../img/home.png" class="logo" />
                </a>
            </div>
            -->
        </div>
    </div>
</div>
</div>
</div>

 <!-- 模态框 --> 
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" 
                aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" 
                            data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">提示</h4>
            </div>
            <div class="modal-body">请选择关联表单和关键字段</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

@endsection