@extends('layouts/default')

@section('content')

<section class="page" id="page-categories">
    <h3 class="page-heading">
        <div class="container">
            Categories
            <small>List of existing categories</small>
        </div>
    </h3>

    <div class="container">

        @if(session()->has('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>

        @endif

        @if($categories->count())
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @include('categories.partial.category', ['categories' => $categories, 'level' => 0])
                </tbody>
            </table>
            <ul>

            </ul>
        @else
            <div class="alert alert-info text-center">There are no defined categories.</div>
        @endif
    </div>
</section>

@endsection
