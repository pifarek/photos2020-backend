@extends('layouts/default')

@section('content')
<section id="login-page">
    <div class="container">

        @if($errors->count())
            <div class="alert alert-warning text-center">Please enter valid credentials.</div>
        @endif

        <form method="post">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="login-email">Email address</label>
                <input type="email" name="email" class="form-control" id="login-email" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="login-password">Password</label>
                <input type="password" name="password" class="form-control" id="login-password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</section>
@endsection
