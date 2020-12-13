@extends('layouts.default')

@section('content')

<section class="page" id="page-tags-index">
    <h3 class="page-heading">
        <div class="container">
            Manage
            <small>Manage all your tags here</small>
        </div>
    </h3>

    <div class="container">
        @if(session()->has('success'))
            <div class="alert alert-success text-center">{{ session()->get('success') }}</div>
        @endif

        @if($tags->count())
            <div class="row">
                @foreach($tags as $tag)
                    <span class="badge badge-light mr-2 mb-2 p-1">
                        {{ $tag->name }} ({{ $tag->media->count() }})
                        <a href="#" data-action="tag-remove" data-id="{{ $tag->id }}"><i class="fas fa-times"></i></a>
                    </span>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning text-center">Sorry, there is nothing to display here yet.</div>
        @endif

            <hr>

            <div class="row">
                <form method="post" class="form-inline" action="{{ route('tags.add') }}">
                    @csrf

                    <label for="tag-name">Tag Name</label>
                    <input type="text" name="name" class="form-control mx-3" id="tag-name" placeholder="New tag name ...">
                    <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
                </form>
            </div>
    </div>
</section>
@endsection
