<form id="search-box" action="/search">
    <input type="text" name="query" placeholder="search" size="31" />
    <button type="submit">search</button>
</form>

@if (Auth::check())
    <div class="add-post-shard">
        <label class="shard-action" for="collapse-add-post-shard">
            Create New
            <span class="shard-action-button"></span>
        </label>
        <input class="collapse" id="collapse-add-post-shard" type="checkbox">
        <a href="/s/{{ $section->title }}/add"><button>add post</button></a>

        <div class="shard-fold">
        </div>
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