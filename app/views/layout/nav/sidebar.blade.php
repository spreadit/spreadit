<div class="side">
    <div class='spacer'>
        <form action="/search" method="get" id="search" name="search">
            <input name="q" placeholder="search" tabindex="20" type="text"><input tabindex="22" type="submit" value="">
        </form>
    </div>

    <div class='spacer'>
        <div class="sponsorshipbox"></div>
    </div>

    <div class='spacer'>
        <div class="sidebox submit submit-link">
            <div class="morelink">
                <a class="login-required" href="/submit" target="_top">Submit a new post</a>

                <div class="nub"></div>
            </div>
        </div>
    </div>

    <div class='spacer'>
        <form method="post" action="{{ url('login') }}" id="login_login-main" class="login-form login-form-side">
            <input type="hidden" name="op" value="login-main" />
            <input name="username" placeholder="username" type="text" maxlength="20" tabindex="1" />
            <input name="password" placeholder="password" type="password" tabindex="1" />
            <div class="status"></div>
            <div id="remember-me">
                <input type="checkbox" name="rem" id="rem-login-main" tabindex="1" />
                <label for="rem-login-main">remember me</label><a class="recover-password" href="/password">reset password</a>
            </div>
            <div class="submit"><span class="throbber"></span>
                <button class="btn" type="submit" tabindex="1">login</button>
            </div>
            <div class="clear"></div>
        </form>
    </div>
</div>