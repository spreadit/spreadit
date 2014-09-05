@extends('layout.help')

@section('title')
    <title>spreadit.io :: help/formatting</title>
@stop
@section('description')
    <meta name="description" content="get help regarding formatting of posts and comments">
@stop

@section('content')
<h2>Formatting text using markdown and LaTeX</h2>
<p>
writing can be done using just plaintext, the text is run through a markdown parser and also a latex parser<br>
markdown for those of you who don't know is a simple way to add elements to a post, such as italicizing text or adding links<br>
</p>

<br><br>

here are a few common operations:

<table>
    <thead>
        <tr><td>type</td><td>code</td><td>result</td></tr>
    </thead>
    <tbody>
        <tr><td>link</td><td><pre>[link title](http://example.com)</pre></td><td><a href="http://example.com">link title</a></td></tr>
        <tr><td>image</td><td><pre>![image title](https://spreadit.io/assets/images/logo_small.png)</pre></td><td><img alt="image title" class="lazy-loaded" data-original="https://spreadit.io/assets/images/logo_small.png"></td></tr>
        <tr><td>italics</td><td><pre>just *italic* text</pre></td><td>just <em>italic</em> text</td></tr>
        <tr><td>bold</td><td><pre>just **bold** text</pre></td><td>just <strong>bold</strong> text</td></tr>
        <tr><td>code block</td><td><pre>~~~<br>function(i) { return i+1; }<br>~~~</pre></td><td><pre><code>function(i) { return i+1; }</code></pre></td></tr>
        <tr><td>inline equation</td><td><pre>some text and \(a \ne 0\) this</pre></td><td>some text and \(a \ne 0\) this</td></tr>
        <tr><td>newline equation</td><td><pre>some text and $$a \ne 0$$ this</pre></td><td>some text and $$a \ne 0$$ this</td></tr>
    </tbody>
</table>
<br><br>
you can test rendering right here, or by clicking preview when creating or editing a post or comment
<br>

<form id="comment-form" method="post" class="flat-form flatpop-left">
    <p class="text">
        <textarea name="data" id="data" placeholder="TRY ME"></textarea>
    </p>
    <div class="submit">
        <button type="submit" formmethod="post" formaction="{{ URL::to('/util/preview') }}" formtarget="preview-box" class="preview">Preview</button>
    </div>
</form>
<div class="preview-box"><iframe name="preview-box"></iframe></div>

you can read more about markdown <a href="https://michelf.ca/projects/php-markdown/extra/">here</a> and you can learn about LaTeX <a href="http://en.wikibooks.org/wiki/LaTeX">here</a>.



</p>
@stop
