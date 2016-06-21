@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body center">
                <h3>欢迎使用抽奖小程序</h3>
                    <div>
                    <a class="brand" href="{{ url('/prizes') }}"><img src="../img/home.png" class="logo" /></a>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
