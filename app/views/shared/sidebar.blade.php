<form id="search-box" action="/search">
    <input type="text" name="query" size="31" />
    <input type="submit" value="search" />
</form>

@if (Auth::check())
    <div class="add-post-shard">
        <a href="/s/{{ $section->title }}/add"><button>add post</button></a>
    </div>
@else
    <div class="login-shard">
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
    </div>
@endif