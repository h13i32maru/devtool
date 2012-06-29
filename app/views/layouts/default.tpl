<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=0" />
  <meta name="format-detection" content="telephone=no" />
  <title>DevTool</title>
  <link rel="stylesheet" href="{{ url('bootstrap/css/bootstrap.min.css') }}" type="text/css"/>
  <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
  <script type="text/javascript" src="{{ url('bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ url('js/app.js') }}"></script>
  {% block js %}{% endblock %} 
  {% block css %}{% endblock %}
</head>

<body>
{% block header %}
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="brand" href="{{ url('/') }}">DevTool <span style="font-size:0.7em">alpha</span></a>

      <div class="btn-group pull-right">
        {% if user %}
          <a class="btn dropdown-toggle" href="#" data-toggle="dropdown">{{ user.name }}<span class="caret"/></a>
          <ul class="dropdown-menu">
            <li><a href="{{ url('top/signout') }}">Sign Out</a>
          </ul>
        {% else %}
          <a class="auth btn btn-primary" href="{{ url('google_auth/index') }}">Sign In with Google Acount</a>
        {% endif %}
      </div>

      <div class="nav-callapse">
        <ul class="nav">
          <li><a href="{{ url('code/index') }}">Code</a>
          <li><a href="{{ url('short_url/index') }}">Short URL</a>
          <li><a href="{{ url('dummy_image/index') }}">Dummy Image</a>
          <li><a href="{{ url('json_mock/index') }}">JSON Mock</a>
        </ul>
      </div>
    </div>
  </div>
</div>
{% endblock %}

{% block content %}
{% endblock %}

<footer>
Developer is maruyama-r. Created using <a href="https://github.com/dietcake/dietcake.github.com">DietCake</a>
</footer>
</body>

</html>
