<!-- resources/views/prizes.blade.php -->
@extends('layouts.app')

@section('content')
<!-- Bootstrap Boilerplate... -->

<div class="panel-body">
    <!-- Display Validation Errors -->
    @include('common.errors')
    <!-- New Task Form -->
    <form action="{{ url('prize') }}" method="POST" class="form-horizontal">
        {{ csrf_field() }}
        <!-- Task Name -->
        <div class="form-group">
            <label for="prize" class="col-sm-3 control-label">Prize</label>

            <div class="col-sm-6">
                <input type="text" name="name" id="prize-name" class="form-control"></div>
        </div>

        <div class="col-sm-6">
            <input type="text" name="number" id="prize-number" class="form-control"></div>
    </div>

    <!-- Add Task Button -->
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-default"> <i class="fa fa-plus"></i>
                Add Prize
            </button>
        </div>
    </div>
    </form>

<!-- TODO: Current Prizes -->
@if (count($prizes) > 0)
<div class="panel panel-default">
<div class="panel-heading">Current Prizes</div>

<div class="panel-body">
    <table class="table table-striped task-table">

        <!-- Table Headings -->
        <thead>
            <th>Prize</th>
            <th>&nbsp;</th>
        </thead>

        <!-- Table Body -->
        <tbody>
            @foreach ($prizes as $prize)
            <tr>
                <!-- Prize Name -->
                <td class="table-text">
                    <div>{{ $prize->name }}</div>
                </td>
                <td class="table-text">
                    <div>{{ $prize->number}}</div>
                </td>
                <!-- TODO: Delete Button -->
                <td>
                    <form action="{{ url('prize/'.$prize->
                        id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i>
                            Delete
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
@endsection