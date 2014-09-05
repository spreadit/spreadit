@extends('layout.pages')

@section('title')
    <title>spreadit.io :: Login</title>
@stop
@section('description')
    <meta name="description" content="login or register to spreadit right meow">
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-warning fade in">
            <div class="close" data-dismiss="alert" aria-hidden="true">&times;</div>
            <h4 class="text-center">{{ $errors->first() }}</h4>
        </div>
    @endif
    <div class="row-fluid">
        <div class="span3">
            <h2>Login to spreadit</h2>
            <br>
            <form id="login-form" action="{{ url('login') }}" method="post" class="flat-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <p class="username">
                    <input name="username" type="username" value="{{ Input::old('username') }}" data-validation="nonempty username" id="username" placeholder="username" />
                    {{ $errors->first('username') }}
                </p>
                <p class="text">
                    <input name="password" type="password" data-validation="nonempty" id="password" placeholder="Password" />
                    {{ $errors->first('password') }}
                </p>
                <div class="submit">
                    <button type="submit">Login</button>
                    <div class="ease"></div>
                </div>
            </form>
        </div>
        <div class="span3">
            <h2>Enable site interaction</h2>
            <br>
            <form id="register-form" action="{{ url('register') }}" method="post" class="flat-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <p class="username">
                    <input name="username" type="username" value="{{ Input::old('username') }}" data-validation="nonempty username" id="username" placeholder="username" />
                    {{ $errors->first('username') }}
                </p>
                <p class="text">
                    <input name="password_confirmation" type="password" data-validation="nonempty" id="password" placeholder="Password" />
                    {{ $errors->first('password') }}
                </p>
                <p class="text">
                    <input name="password" type="password" data-validation="nonempty confirmation" id="password" placeholder="Confirm Password" />
                    {{ $errors->first('password_confirmation') }}
                </p>
                <p class="captcha">
                    {{  HTML::image(Captcha::img(), 'Captcha image') }}
                    <input type="text" name="captcha" placeholder="Captcha text">
                </p>
                <div class="submit">
                    <button type="submit">Submit</button>
                    <div class="ease"></div>
                </div>
            </form>
        </div>
        <div class="span6">
            <h1>Welcome to spreadit</h1>
            <p>
                Hello, welcome to the most privacy centric &amp; most transparent link aggregator &amp; discussion board.
                <br>
                This is very much still in development, but we need <b>you</b> in order to create &amp; grow this community. 
                <br>
                Leave a few comments and start a discussion, if each person who visited this site left a single comment it wouldn't be a lonely place at all. I know we can create something really great: a tiny spec out in the internet's universe that is purely free.
                <br>
                Read the <a href="/about">about</a> page for a quick rundown on features, in the near future I will have a few help pages written up.
                <br>
                And you can always contact me by posting over in <a href="/s/spreadit">/s/spreadit</a>.
                <br>
                Cheers and hope you enjoy yourself. (remember to spreadit)
            </p>
        </div>
    </div>
@stop

@section('script')
<!-- todo validation -->
@stop
