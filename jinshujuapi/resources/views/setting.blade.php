@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">添加奖品</div>

            <div class="panel-body">
                <!-- Display Validation Errors -->
                @include('common.errors')
                <!-- New Prize Form -->
                <form action="{{ url('setting') }}" method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                    <!-- Prize Name -->
                    <div class="form-group">
                        <label for="prize-name" class="col-sm-3 control-label">奖品名称</label>

                        <div class="col-sm-6">
                            <input type="text" name="name" id="prize-name" class="form-control" value="{{ old('prize') }}"></div>
                    </div>

                    <!-- Prize Number -->
                    <div class="form-group">
                        <label for="prize-number" class="col-sm-3 control-label">奖品数量</label>

                        <div class="col-sm-6">
                            <input type="text" name="number" id="prize-number" class="form-control" value="{{ old('prize') }}"></div>
                    </div>

                    <!-- Prize Chance-->
                    <div class="form-group">
                        <label for="prize-chance" class="col-sm-3 control-label">中奖概率</label>

                        <div class="col-sm-6">
                            <input type="text" name="chance" id="prize-chance" class="form-control" value="{{ old('prize') }}"></div>
                    </div>

                    <!-- Add Prize Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="submit" class="btn btn-default"> <i class="fa fa-btn fa-plus"></i>
                                添加奖品
                            </button>
                            <a href="/prizes" class="btn btn-primary">进入抽奖</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Current Prizes -->
        @if (count($prizes) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">奖品设置</div>

            <div class="panel-body">
                <table class="table table-striped prize-table">
                    <thead>
                        <th>奖品</th>
                        <th>名称</th>
                        <th>数量</th>
                        <th>中奖概率</th>
                        <th>&nbsp;</th>
                    </thead>
                    <tbody>
                        @foreach ($prizes as $prize)
                        <tr>
                            <td class="table-text">
                                <div>{{ $prize->pid }}</div>
                            </td>

                            <td class="table-text">
                                <div>{{ $prize->name }}</div>
                            </td>
                            
                            <td class="table-text">
                                <div>{{ $prize->number }}</div>
                            </td>

                            <td class="table-text">
                                <div>{{ $prize->chance }}</div>
                            </td>

                            <!-- Prize Delete Button -->
                            <td>
                                <form action="{{url('setting/' . $prize->
                                    id)}}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                    <input name="prizeid" type="hidden" value="{{$prize->id}}">
                                    <button type="submit" id="delete-prize-{{ $prize->
                                        id }}" class="btn btn-danger"> <i class="fa fa-btn fa-trash"></i>
                                        删除
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection