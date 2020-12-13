@extends('layouts.default')

@section('content')

<section class="page" id="page-media-edit">
    <h3 class="page-heading">
        <div class="container">
            Edit Media
            <small>Edit selected Media</small>
        </div>
    </h3>

    <div class="container">

        @if($errors->count())
            <div class="alert alert-warning text-center">{{ $errors->first() }}</div>
        @endif

        @if($media->type === 'photo')
            <div class="tab-pane fade{!! session()->get('type') === 'photo' || !session()->get('type')? ' show active' : '' !!}" id="add-photo" role="tabpanel" aria-labelledby="home-tab">
                <form method="post" action="{{ route('media.update-photo', [$media->id]) }}">
                    @method('put')
                    @csrf

                    <div class="preview">
                        <img src="{{ url('upload/images/f/' . $media->filename) }}" alt="{{ $media->name }}">
                    </div>

                    <div class="form-group">
                        <label for="photo-name">Name</label>
                        <input type="text" class="form-control{!! $errors->has('name') ? ' is-invalid' : '' !!}" id="photo-name" name="name" placeholder="Name" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="photo-description">Description</label>
                        <textarea class="form-control{!! $errors->has('description') ? ' is-invalid' : '' !!}" id="photo-description" name="description" placeholder="Photo description">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group tags-select">
                        <label for="photo-name">Tags</label>
                        <input type="hidden"name="tags" placeholder="tags" value="{{ old('tags', $media->tagsString()) }}">
                        <!-- Separate tags id by semicolon -->
                        <div class="form-control">
                            <ul class="selected-tags">
                                <!-- js -->
                            </ul>
                        </div>
                        <small class="form-text text-muted">
                            You can pick tags from the list below.
                            @if($tags->count())
                                <ul class="tags">
                                    @foreach($tags as $tag)
                                        <li><a href="#" class="badge badge-primary" data-action="tag-add" data-id="{{ $tag->id }}" data-name="{{ $tag->name }}">{{ $tag->name }} ({{ $tag->media->count() }})</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="photo-category">Category</label>
                        <select name="category_id" id="photo-category" class="selectpicker form-control{!! $errors->has('category_id') ? ' is-invalid' : '' !!}">
                            @if($categories->count())
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('parent_id', $media->category_id) == $category->id ? 'selected="selected"' : '' }}>{{ $category->name }} ({{ $category->count }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="photo-taken_at">Taken At</label>
                        <input type="text" class="form-control datepicker{!! $errors->has('taken_at') ? ' is-invalid' : '' !!}" id="photo-taken_at" name="taken_at" value="{{ old('taken_at', date('d/m/Y', $media->taken_at->timestamp)) }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Edit Photo</button>
                        <button type="button" class="btn btn-warning" onclick="window.location.href='{{ route('media.index') }}';">Cancel</button>
                    </div>
                </form>
            @elseif($media->type === 'youtube')
            <form method="post" action="{{ route('media.update-video', [$media->id]) }}">
                @method('put')
                @csrf

                <div class="form-group">
                    <label for="photo-name">Name</label>
                    <input type="text" class="form-control{!! $errors->has('name') ? ' is-invalid' : '' !!}" id="photo-name" name="name" placeholder="Name" value="{{ old('name', $media->name) }}">
                </div>

                <div class="form-group">
                    <label for="photo-description">Description</label>
                    <textarea class="form-control{!! $errors->has('description') ? ' is-invalid' : '' !!}" id="photo-description" name="description" placeholder="Description">{{ old('description', $media->description) }}</textarea>
                </div>

                <div class="form-group tags-select">
                    <label for="photo-name">Tags</label>
                    <input type="hidden"name="tags" placeholder="tags" value="{{ old('tags', $media->tagsString()) }}">
                    <!-- Separate tags id by semicolon -->
                    <div class="form-control">
                        <ul class="selected-tags">
                            <!-- js -->
                        </ul>
                    </div>
                    <small class="form-text text-muted">
                        You can pick tags from the list below.
                        @if($tags->count())
                            <ul class="tags">
                                @foreach($tags as $tag)
                                    <li><a href="#" class="badge badge-primary" data-action="tag-add" data-id="{{ $tag->id }}" data-name="{{ $tag->name }}">{{ $tag->name }} ({{ $tag->media->count() }})</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </small>
                </div>

                <div class="form-group">
                    <label for="photo-category">Category</label>
                    <select name="category_id" id="photo-category" class="selectpicker form-control{!! $errors->has('category_id') ? ' is-invalid' : '' !!}">

                        @if($categories->count())
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"{{ old('parent_id', $media->category_id) == $category->id ? ' selected="selected"' : '' }}>{{ $category->name }} ({{ $category->count }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="photo-taken_at">Taken At</label>
                    <input type="text" class="form-control datepicker{!! $errors->has('taken_at') ? ' is-invalid' : '' !!}" id="photo-taken_at" name="taken_at" value="{{ old('taken_at', date('d/m/Y', $media->taken_at->timestamp)) }}">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Edit Video</button>
                    <button type="button" class="btn btn-warning" onclick="window.location.href='{{ route('media.index') }}';">Cancel</button>
                </div>
            </form>
            @endif
        </div>


    </div>
</section>
@endsection

@section('script')
    <script>
        const availableTags = [
            @foreach($tags as $tag)
                '{{ $tag->name }}',
            @endforeach
        ];
    </script>
@endsection
