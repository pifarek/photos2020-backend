@extends('layouts/default')

@section('content')

    <section class="page" id="page-category-create">
        <h3 class="page-heading">
            <div class="container">
                Edit Category
                <small>Edit selected Category</small>
            </div>
        </h3>

        <div class="container">

            <form method="post" action="{{ route('categories.update', [$category->id]) }}">
                @method('put')
                @csrf

                <div class="form-group">
                    <label for="category-name">Category name</label>
                    <input type="text" class="form-control{!! $errors->has('name') ? ' is-invalid' : '' !!}" id="category-name" name="name" placeholder="Category name" value="{{ old('name', $category->name) }}">
                </div>
                <div class="form-group">
                    <label for="category-description">Category name</label>
                    <textarea class="form-control{!! $errors->has('description') ? ' is-invalid' : '' !!}" id="category-description" name="description" placeholder="Category description">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="category-parent">Parent Category</label>
                    <select name="parent_id" id="category-parent" class="selectpicker form-control{!! $errors->has('parent_id') ? ' is-invalid' : '' !!}">
                        <option value="">None</option>
                        @if($categories->count())
                            @foreach($categories as $selectCategory)
                        <option value="{{ $selectCategory->id }}" {{ old('parent_id', $category->parent_id) == $selectCategory->id ? 'selected="selected"' : '' }}>{{ $selectCategory->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="category-status">Status</label>
                    <select name="status" id="category-status" class="selectpicker form-control{!! $errors->has('status') ? ' is-invalid' : '' !!}">
                        <option value="pending"{!! $category->status == 'pending' ? ' selected="selected"' : '' !!}>Pending</option>
                        <option value="published"{!! $category->status == 'published' ? ' selected="selected"' : '' !!}>Published</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Edit Category</button>
                    <button type="button" class="btn btn-warning" onclick="window.location.href='{{ route('categories.index') }}';">Cancel</button>
                </div>
            </form>
        </div>
    </section>
@endsection
