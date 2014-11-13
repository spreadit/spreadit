@include ('shared.googlesitesearch')

@if (Auth::check())
    
@else
    <form id="login-form" action="{{ url('login') }}" method="post">
        <p class="username">
            <input name="username" type="username" value="" data-validation="nonempty username" id="username" placeholder="username" />
        </p>
        <p class="text">
            <input name="password" type="password" data-validation="nonempty" id="password" placeholder="Password" />
        </p>
        <div class="submit">
            <button type="submit">Login</button>
        </div>
    </form>
@endif