@extends('layout.pages')

@section('title')
    <title>spreadit.io :: add a post to {{ $oldSection }}</title>
@stop
@section('description')
    <meta name="description" content="add a new post to spreadit with our intuitive keyboard/mouse interface">
@stop

@section('content')
    <h1>Posting to /s/{{ $oldSection }}</h1>
    @if ($errors->any())
        <div class="alert alert-warning fade in">
            <div class="close" data-dismiss="alert" aria-hidden="true">&times;</div>
            <h4 class="text-center">{{ $errors->first() }}</h4>
        </div>
    @endif
    <div class="row-fluid">
        <div class="span6">
            <p>{{ $postsRemaining }}</p>
            @if (Utility::remainingPosts() > 0)
            <form id="post-form" action="/s/{{{ $section->title }}}/add" method="post" class="flat-form flatpop-left">
                <p class="text">
                    <input name="title" type="text" value="{{ Input::old('title') }}" id="title" placeholder="title" maxlength="{{ Constant::POST_MAX_TITLE_LENGTH }}" />
                    {{ $errors->first('title') }}
                </p>
                <p class="text">
                    <div class="row-fluid">
                        <div class="span9">
                            <input name="url" type="url" value="{{ Input::old('url') }}" id="url" placeholder="http://yoururl.com" maxlength="{{ Constant::POST_MAX_URL_LENGTH }}">
                            {{ $errors->first('url') }}
                        </div>
                        <div class="span3">
                            <button type="button" id="suggest_title">Suggest Title</button>
                        </div>
                    </div>
                </p>
                <p class="text">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="spreadit-selector">
                                <select name="section" id="section">
                                    <option value="{{ $oldSection }}" selected>{{ $oldSection }}</option>
                                    @foreach ($all_sections as $sel)
                                        <option value="{{ $sel }}">{{ $sel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--<input name="section" id="section" type="text" value="{{ $oldSection }}" id="section" placeholder="spreadit section title" minlength="{{ Constant::SECTION_MIN_TITLE_LENGTH }}" maxlength="{{ Constant::SECTION_MAX_TITLE_LENGTH }}" pattern="[a-zA-Z0-0_-]+$" />-->
                            {{ $errors->first('section') }}
                        </div>
                        <div class="span6">
                        </div>
                    </div>
                </p>
                <p class="text">
                    <textarea name="data" id="data" placeholder="Body of post" maxlength="{{ Constant::POST_MAX_MARKDOWN_LENGTH }}">{{ Input::old('data') }}</textarea>
                    {{ $errors->first('data') }}
                </p>
                <p class="text">
                    nsfw: {{ Form::checkbox('nsfw-tag') }}
                    <br>
                    nsfl: {{ Form::checkbox('nsfl-tag') }}
                </p>
                <div class="submit">
                    @if (!Auth::check())
                        <p class="captcha">
                            {{  HTML::image(Captcha::img(), 'Captcha image') }}
                            <input type="text" name="captcha" placeholder="Captcha text" size="8" required>
                        </p>
                    @endif
                    <button type="submit" formmethod="post" formaction="{{ URL::to('/util/preview') }}" formtarget="preview-box" class="preview">Preview</button>
                    <button type="submit">Post</button>
                    <div class="ease"></div>
                </div>
            </form>
            <div class="preview-box"><iframe name="preview-box"></iframe></div>
            @endif
        </div>
        <div class="span6">
            <p>
                Enter a title (required) and a url or body (or both).
<br><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.---.&nbsp;.---.&nbsp;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;o&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;me&nbsp;want&nbsp;cookie!<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_..-:&nbsp;&nbsp;&nbsp;o&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:-.._&nbsp;&nbsp;&nbsp;&nbsp;/<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.-''&nbsp;&nbsp;'&nbsp;&nbsp;`---'&nbsp;`---'&nbsp;"&nbsp;&nbsp;&nbsp;``-.&nbsp;&nbsp;&nbsp;&nbsp;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.'&nbsp;&nbsp;&nbsp;"&nbsp;&nbsp;&nbsp;'&nbsp;&nbsp;"&nbsp;&nbsp;.&nbsp;&nbsp;&nbsp;&nbsp;"&nbsp;&nbsp;.&nbsp;'&nbsp;&nbsp;"&nbsp;&nbsp;`.&nbsp;&nbsp;<br>
&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'.---.,,.,...,.,.,.,..---.&nbsp;&nbsp;'&nbsp;;<br>
&nbsp;&nbsp;&nbsp;&nbsp;`.&nbsp;"&nbsp;`.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.'&nbsp;"&nbsp;.'<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`.&nbsp;&nbsp;'`.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.'&nbsp;'&nbsp;.'<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`.&nbsp;&nbsp;&nbsp;&nbsp;`-._&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_.-'&nbsp;"&nbsp;&nbsp;.'&nbsp;&nbsp;.----.<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`.&nbsp;"&nbsp;&nbsp;&nbsp;&nbsp;'"--...--"'&nbsp;&nbsp;.&nbsp;'&nbsp;.'&nbsp;&nbsp;.'&nbsp;&nbsp;o&nbsp;&nbsp;&nbsp;`.<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.'`-._'&nbsp;&nbsp;&nbsp;&nbsp;"&nbsp;.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"&nbsp;_.-'`.&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;o&nbsp;&nbsp;:<br>
&nbsp;&nbsp;jgs&nbsp;.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;```--.....--'''&nbsp;&nbsp;&nbsp;&nbsp;'&nbsp;`:_&nbsp;o&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<br>
&nbsp;&nbsp;&nbsp;&nbsp;.'&nbsp;&nbsp;&nbsp;&nbsp;"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"&nbsp;&nbsp;&nbsp;;&nbsp;`.;";";";'<br>
&nbsp;&nbsp;&nbsp;;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.&nbsp;;&nbsp;.'&nbsp;;&nbsp;;&nbsp;;<br>
&nbsp;&nbsp;;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'&nbsp;&nbsp;&nbsp;"&nbsp;&nbsp;&nbsp;&nbsp;.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.-'<br>
&nbsp;&nbsp;'&nbsp;&nbsp;"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"&nbsp;&nbsp;&nbsp;'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"&nbsp;&nbsp;&nbsp;&nbsp;_.-'<br>
<br>
            </p>
        </div>
    </div>
@stop
