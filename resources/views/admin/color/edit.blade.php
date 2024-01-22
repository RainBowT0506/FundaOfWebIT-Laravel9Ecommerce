@extends('layouts.admin')


@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-header">
                    <h3> Edit Color
                        <a href="{{ url('admin/color') }}" class="btn btn-danger btn-sm text-white float-end">
                            Back</a>
                    </h3>
                </div>


                <div class="card-body">
                    <form action="{{ url('admin/color/' . $color->id . '') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label>Color Name</label>
                            <input type="text" name="name" value="{{ $color->name }}"class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Color Code</label>
                            <input type="text" name="code" value="{{ $color->code }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Status</label><br />
                            <input type="checkbox" name="status" {{ $color->status ? 'checked' : '' }}
                                style="width:30px;height:30px" />Checked=Hidden,
                            Un-Checked=Visible
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Upadte</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
