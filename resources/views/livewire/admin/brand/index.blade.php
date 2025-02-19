<div>

    @include('livewire.admin.brand.modal-form')

    <div class="row">
        <div class="col-md-12">

            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>
                        Brands List
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addBrandModal"
                            class="btn btn-primary btn-sm float-end">Add Brands</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td>{{ $brand->id }}</td>
                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $brand->slug }}</td>
                                    <td>{{ $brand->status == '1' ? 'hidden' : 'visible' }}</td>
                                    <td>
                                        <a href="#" wire:click='setEditBrand({{ $brand->id }})'
                                            data-bs-toggle="modal" data-bs-target="#updateBrandModal"
                                            class="btn btn-sm btn-success">Edit</a>
                                        <a href="#" wire:click='setDeleteBrand({{ $brand->id }})'
                                            data-bs-toggle="modal" data-bs-target="#deleteBrandModal"
                                            class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>

                                @empty($brand)
                                    <tr>
                                        <td colspan="5">No Brands Found</td>
                                    </tr>
                                @endempty
                            @endforeach
                        </tbody>
                    </table>

                    <div>
                        {{ $brands->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('script')
    <script>
        window.addEventListener('close-modal', event => {
            $('#addBrandModal').modal('hide');
            $('#updateBrandModal').modal('hide');
            $('#deleteBrandModal').modal('hide');
        });
    </script>
@endpush
