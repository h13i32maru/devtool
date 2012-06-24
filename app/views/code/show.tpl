{% extends "code/default.tpl" %}

{% block code_content %}

{% if code_pack.writable %}
<header class="code">
  <a class="btn btn-primary" href="{{ url('code/edit', {p:code_pack.path}) }}">Edit</a>
  <a class="btn btn-danger" onclick="return Code.deleteConfirm()" href="{{ url('code/delete', {p:code_pack.path}) }}">Delete</a>
</header>
{% endif %}

<pre>
Title {{ code_pack.title }}
Description {{ code_pack.description }}
Update <time datetime="{{ code_pack.updated }} UTC+0000"></time>
Create <time datetime="{{ code_pack.created }} UTC+0000"></time>
</pre>

{% for code in codes %}
<pre><code class={{ code.class }}>{{ code.code }}</code></pre>
{% endfor %}

{% endblock %}
