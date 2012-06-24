{% extends "code/default.tpl" %}

{% block js %}
{{ parent() }}
<script>$(Code.addCodeForm.bind(Code));</script>
{% endblock %}

{% block code_content %}
<form class="code-form" method="post" action="{{ url('code/exec_create') }}">
  <input class="btn btn-primary" type="submit" value="Create">
  <input type="text" name="title" placeholder="title">
  <input type="text" name="description" placeholder="description">
</form>

<a class="btn btn-info" href="javascript:Code.addCodeForm()">Add Form</a>
{% endblock %}
