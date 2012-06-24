{% extends "code/default.tpl" %}

{% block code_content %}

<form class='code-form' method="post" action="{{ url('code/exec_edit', {p:code_pack.path}) }}">
<input class="btn btn-primary" type="submit" value="Update">
<input type="text" name="title" placeholder="title" value="{{ code_pack.title }}">
<input type="text" name="description" placeholder="description" value="{{ code_pack.description }}">

{% for code in codes %}
<select name="class[]">
  <option value="auto" {{ code.class == 'auto' ? 'selected' : '' }}>Auto
  <option value="no-highlight" {{ code.class == 'no-highlight' ? 'selected' : '' }}>Plain Text
</select>

</select>
<textarea name="code[]">{{ code.code }}</textarea>
<input type="hidden" name="id[]" value="{{ code.id }}">
{% endfor %}
</form>

<a class="btn btn-info" href="javascript:Code.addCodeForm()">Add</a>

{% endblock %}
