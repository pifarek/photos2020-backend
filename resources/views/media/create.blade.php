@extends('layouts.default')

@section('content')

<section class="page" id="page-media-create">
    <h3 class="page-heading">
        <div class="container">
            Add Media
            <small>Add a new Media</small>
        </div>
    </h3>

    <div class="container">

        @if($errors->count())
            <div class="alert alert-warning text-center">{{ $errors->first() }}</div>
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success text-center">{{ session()->get('success') }}</div>
        @endif

        <ul class="nav nav-tabs" id="media-createTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link{!! session()->get('type') === 'photo' || !session()->get('type')? ' active' : '' !!}" id="add-photo-tab" data-toggle="tab" href="#add-photo" role="tab" aria-controls="home" aria-selected="true">
                    <i class="fas fa-image"></i>
                    Photo
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{!! session()->get('type') === 'youtube'? ' active' : '' !!}" id="add-youtube-tab" data-toggle="tab" href="#add-youtube" role="tab" aria-controls="profile" aria-selected="false">
                    <i class="fab fa-youtube"></i>
                    Youtube
                </a>
            </li>
        </ul>
        <div class="tab-content" id="media-createTabContent">
            <div class="tab-pane fade{!! session()->get('type') === 'photo' || !session()->get('type')? ' show active' : '' !!}" id="add-photo" role="tabpanel" aria-labelledby="home-tab">
                <form method="post" action="{{ route('media.store-photo') }}">
                    <input type="hidden" name="filename" value="">
                    @method('post')
                    @csrf

                    <div class="image-preview"></div>

                    <span class="btn btn-success fileinput-button">
                        <i class="fas fa-plus"></i>
                          <span>Select Image ...</span>
                          <input type="file" name="photo" class="fileupload" data-url="{{ route('media.temporary') }}">
                    </span>

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
                        <input type="hidden"name="tags" placeholder="tags" value="{{ old('tags') }}">
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
                                    <option value="{{ $category->id }}" {{ old('parent_id', request('category')) == $category->id ? 'selected="selected"' : '' }}>{{ $category->name }} ({{ $category->count }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="photo-taken_at">Taken At</label>
                        <input type="text" class="form-control datepicker{!! $errors->has('taken_at') ? ' is-invalid' : '' !!}" id="photo-taken_at" name="taken_at" value="{{ old('taken_at', session('last_date', date('d/m/Y'))) }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Add Photo</button>
                        <button type="button" class="btn btn-warning" onclick="window.location.href='{{ route('media.index') }}';">Cancel</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade{!! session()->get('type') === 'video'? ' show active' : '' !!}" id="add-youtube" role="tabpanel" aria-labelledby="profile-tab">
                <form method="post" action="{{ route('media.store-video') }}">
                    @method('post')
                    @csrf
                    <div class="form-group">
                        <label for="youtube-link">Youtube link</label>
                        <input type="text" class="form-control{!! $errors->has('link') ? ' is-invalid' : '' !!}" id="youtube-link" name="link" placeholder="Youtube link" value="{{ old('link') }}">
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
                        <input type="hidden"name="tags" placeholder="tags" value="{{ old('tags') }}">
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
                                    <option value="{{ $category->id }}" {{ old('parent_id', request('category')) == $category->id ? 'selected="selected"' : '' }}>{{ $category->name }} ({{ $category->count }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="photo-taken_at">Taken At</label>
                        <input type="text" class="form-control datepicker{!! $errors->has('taken_at') ? ' is-invalid' : '' !!}" id="photo-taken_at" name="taken_at" value="{{ old('taken_at', session('last_date', date('d/m/Y'))) }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Add Video</button>
                        <button type="button" class="btn btn-warning" onclick="window.location.href='{{ route('media.index') }}';">Cancel</button>
                    </div>
                </form>
            </div>
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
