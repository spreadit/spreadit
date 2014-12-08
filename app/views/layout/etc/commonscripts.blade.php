<div id="interact-user-details" class="{{ Auth::check() ? 'logged-in ' : '' }} {{ Auth::check() && Auth::user()->anonymous ? 'anonymous ' : '' }}"></div>
<script src="{{ Bust::url("/assets/prod/build.min.js") }}"></script>
<script type="text/x-mathjax-config">MathJax.Hub.Config({ tex2jax: {inlineMath: [["\\(","\\)"]]} });</script>
<script src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
