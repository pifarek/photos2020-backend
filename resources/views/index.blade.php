@extends('layouts/default')

@section('content')

<section class="page" id="page-index">
    <div class="container">
        @if($popular->count())
            <div class="row">
                @foreach($popular as $singleMediaItem)
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="thumbnail" style="background-image: url('{{ url('upload/images/s/' . $singleMediaItem->thumbnail()) }}')">

                            @if($singleMediaItem->name)
                                <p title="{{ $singleMediaItem->name }}">
                                    {{ $singleMediaItem->name }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning text-center">Sorry, there is nothing to display here yet.</div>
        @endif
    </div>
</section>

@endsection
