@extends('backend.layout.main')
@section('content')
<section class="py-4">
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Add Product Model</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('model.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Model Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    {{-- <div class="mb-3">
                        <label for="product_id" class="form-label">Product</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="mb-3">
                        <label for="image" class="form-label">Model Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Add Model</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Model List</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Product</th>
                            <th>Image</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($models as $model)
                            <tr>
                                <td>{{ $model->name }}</td>
                                <td>{{ $model->product->name ?? '' }}</td>
                                <td>
                                    @if($model->image)
                                        <img src="{{ asset($model->image) }}" width="100">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('model.edit', $model->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('model.delete', $model->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No models found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
