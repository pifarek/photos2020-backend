@extends('layouts.default')

@section('content')

<section class="page" id="page-media-index">
    <h3 class="page-heading">
        <div class="container">
            Manage
            <small>Manage all your media here</small>
        </div>
    </h3>

    <div class="container">

        @if($errors->count())
            <div class="alert alert-warning text-center">{{ $errors->first() }}</div>
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success text-center">{{ session()->get('success') }}</div>
        @endif

        <div class="filters">
            <form method="get">
                <div class="row">
                    <div class="col">
                        <select class="form-control selectpicker" name="category">
                            <option value="">All Categories</option>
                            @if($categories->count())
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"{!! $category->id == $category_id? ' selected="selected"' : '' !!}>{{ $category->name }} ({{ $category->count }})</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-control selectpicker" name="order">
                            <option value="newest"{!! $order == 'newest'? ' selected="selected"' : '' !!}>Newest to Oldest</option>
                            <option value="oldest"{!! $order == 'oldest'? ' selected="selected"' : '' !!}>Oldest to Newest</option>
                        </select>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                        <button type="button" class="btn btn-warning" onclick="window.location.href='{{ route('media.index') }}'"><i class="far fa-times-circle"></i> Clear</button>
                    </div>
                </div>
            </form>
        </div>


        @if($media->count())
            <div class="row">
                @foreach($media as $singleMediaItem)
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="thumbnail" style="background-image: url('{{ url('upload/images/s/' . $singleMediaItem->thumbnail()) }}')">
                            <div class="controls">
                                @if($singleMediaItem->type === 'photo')
                                <a data-fancybox href="{{ url('upload/images/f/' . $singleMediaItem->thumbnail()) }}" target="_blank" class="controls-edit" title="View Media"><i class="fas fa-image"></i></a>
                                @elseif($singleMediaItem->type === 'youtube')
                                <a data-fancybox href="https://www.youtube.com/watch?v={{ $singleMediaItem->youtube->code }}" target="_blank" class="controls-edit" title="View Media"><i class="fas fa-video"></i></a>
                                @endif
                                <a href="{{ route('media.edit', [$singleMediaItem->id]) }}" class="controls-edit" title="Edit Media"><i class="fas fa-pencil-alt"></i></a>
                                <a href="#" class="controls-remove" data-action="media-remove" data-id="{{ $singleMediaItem->id }}" title="Remove Media"><i class="fas fa-trash-alt"></i></a>
                            </div>

                            @if($singleMediaItem->name)
                            <p title="{{ $singleMediaItem->name }}">
                                {{ $singleMediaItem->name }}
                            </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $media->appends(['category' => $category_id, 'order' => $order])->render() }}
        @else
            <div class="alert alert-warning text-center">Sorry, there is nothing to display here yet.</div>
        @endif

    </div>
</section>
@endsection
