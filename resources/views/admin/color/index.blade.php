@extends('layouts.admin')


@section('content')
    <div class="row">
        <div class="col-md-12 ">
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3>Color List
                        <a href="{{ url('admin/color/create') }}" class="btn btn-primary btn-sm text-white float-end">
                            Add Color</a>
                    </h3>
                </div>


                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Color Name</th>
                                <th>Color Color</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($colors as $color)
                                <tr>
                                    <td>{{ $color->id }}</td>
                                    <td>{{ $color->name }}</td>
                                    <td>{{ $color->code }}</td>
                                    <td>{{ $color->status ? 'Hidden' : 'Visible' }}</td>
                                    <td>
                                        <a href="{{ url('admin/color/' . $color->id . '/edit') }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <a href="{{ url('admin/color/' . $color->id . '/delete') }}"
                                            onclick="return confirm('Are you sure you want to delete this color ?')"
                                            class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
