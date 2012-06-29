{% extends "layouts/default.tpl" %}

{% block content %}
<div class="auth hero-unit">
<p>
<a href="https://github.com/h13i32maru/devtool">DevTool</a>は開発がちょっと楽になるかもしれない社内向けのWebサービスです。
</p>

<p>
DevToolは<a href="https://github.com/dietcake/dietcake.github.com">DietCake</a>というフレームワークを使って開発されています。
DietCakeはシンプルで軽量高速なフレームワークです。学習コストも低くWebサービスを開発したことのない人にも最適です。
</p>

<p>
またDevToolの開発に興味がある方は<a href="https://twitter.com/h13i32maru">maruyama-r</a>までお声がけください。一緒に開発してくれる人を募集しています。
特にWebサービスを開発したことのない人、大歓迎です！僕自身Webサービスの開発はまだまだ初心者なので一緒に勉強しましょう。
</p>
<p class="auth-btn">
  <a class="auth btn btn-primary" href="{{ url('google_auth/index') }}">Sign In with Google App Acount</a>
</p>
</div>
{% endblock %}
