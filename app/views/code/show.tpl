{% extends "code/default.tpl" %}

{% block code_content %}

{% if code_pack.writable %}
<header class="code">
  <a class="btn btn-primary" href="{{ url('code/edit', {p:code_pack.path}) }}">Edit</a>
  <a class="btn btn-danger" onclick="return Code.deleteConfirm()" href="{{ url('code/exec_delete', {p:code_pack.path}) }}">Delete</a>
</header>
{% else %}
<header class="code">
  <a class="btn btn-primary" href="{{ url('code/exec_copy', {p:code_pack.path}) }}">Copy</a>
</header>
{% endif %}

<pre>
Title {{ code_pack.title }}
Description {{ code_pack.description }}
Update <time datetime="{{ code_pack.updated }} UTC+0000"></time>
Create <time datetime="{{ code_pack.created }} UTC+0000"></time>
</pre>

{% for code in codes %}
<div class="code-block">
<p class="appendix"><a target="_blank" href="{{ url('code/plain', { p: code_pack.path, cid: code.id }) }}"><i class="icon-share"></i> Plain text</a></p>
<pre><code class={{ code.class }}>{{ code.code }}</code></pre>
</div>
{% endfor %}

{% endblock %}
