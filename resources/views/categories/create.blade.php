@extends('layouts/default')

@section('content')

<section class="page" id="page-category-create">
    <h3 class="page-heading">
        <div class="container">
            Create Category
            <small>Create a new Category</small>
        </div>
    </h3>

    <div class="container">

        <form method="post" action="{{ route('categories.store') }}">
            @method('post')
            @csrf

            <div class="form-group">
                <label for="category-name">Category name</label>
                <input type="text" class="form-control{!! $errors->has('name') ? ' is-invalid' : '' !!}" id="category-name" name="name" placeholder="Category name" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label for="category-description">Category description</label>
                <textarea class="form-control{!! $errors->has('description') ? ' is-invalid' : '' !!}" id="category-description" name="description" placeholder="Category description">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="category-parent">Parent Category</label>
                <select name="parent_id" id="category-parent" class="selectpicker form-control{!! $errors->has('parent_id') ? ' is-invalid' : '' !!}">
                    <option value="">None</option>
                    @if($categories->count())
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group">
                <label for="category-status">Status</label>
                <select name="status" id="category-status" class="selectpicker form-control{!! $errors->has('status') ? ' is-invalid' : '' !!}">
                    <option value="pending"{!! old('status') == 'pending' ? ' selected="selected"' : '' !!}>Pending</option>
                    <option value="published"{!! old('status') == 'published' ? ' selected="selected"' : '' !!}>Published</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Add Category</button>
                <button type="button" class="btn btn-warning" onclick="window.location.href='{{ route('categories.index') }}';">Cancel</button>
            </div>
        </form>
    </div>
</section>
@endsection
