<!-- resources/views/prizes.blade.php -->
@extends('layouts.app')

@section('content')
<!-- Bootstrap Boilerplate... -->
<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <!-- Display Validation Errors -->
        @include('common.errors')
        <div class="panel center">
            <input id="me" type="hidden" value="{{$me}}">
            <input id="form" type="hidden" value="{{$form}}">
            <button id="start" class="btn btn-danger">开始抽奖</button>
            <button id="again" class="btn btn-danger hide">重新开始</button>
        </div>
        <!-- TODO: 中奖用户 -->
        <div id="winners" class="panel panel-default hide">
            <div class="panel-heading">中奖用户</div>
            <div class="panel-body">
                <table id="list" class="able table-striped">
                    <!-- Table Headings -->
                    <thead></thead>
                    <!-- Table Body -->
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- TODO: 参与用户 -->
        @if (count($customers) > 0)
        <div id="customers"class="panel panel-default">
            <div class="panel-heading">参与用户</div>

            <div class="panel-body">
                <table class="table table-striped">

                    <!-- Table Headings -->
                    <thead>
                        <th>姓名</th>
                        <th>手机</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach ($customers as $customer)
                        <tr>
                            <!-- Prize Name -->
                            <td class="table-text">
                                <div>{{ $customer->name }}</div>
                            </td>
                            <td class="table-text">
                                <div class="phonenum" >{{ $customer->phone}}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div id="customers"class="panel panel-default">
            <div class="panel-heading">参与用户</div>

            <div class="panel-body">
                <table class="table table-striped">

                    <!-- Table Headings -->
                    <thead>
                        <th>关联表单的字段中没有用户数据~~</th>
                    </thead>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection