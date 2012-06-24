{% extends "code/default.tpl" %}

{% block js %}
{{ parent() }}
<script>$(Code.addCodeForm.bind(Code));</script>
{% endblock %}

{% block content %}
{{ parent() }}

<form class="code-form" method="post" action="{{ url('code/exec_create') }}">
<input type="submit" value="Create">

<br/>
<input type="text" name="title" placeholder="title">
<input type="text" name="description" placeholder="description">

<br/>

</form>

<a href="javascript:Code.addCodeForm()">Add</a>

{% endblock %}





{#
<select name="class[]">
    <option value="auto">Auto
    <option value="actionscript">ActionScript
    <option value="bash">Bash
    <option value="cpp">C++
    <option value="cs">C#
    <option value="css">CSS
    <option value="diff">Diff
    <option value="html">HTML
    <option value="java">Java
    <option value="javascript">JavaScript
    <option value="json">JSON
    <option value="lua">Lua
    <option value="markdown">Markdown
    <option value="objectivec">Objective C
    <option value="perl">Perl
    <option value="php">PHP
    <option value="no-highlight">Plain Text
    <option value="python">Python
    <option value="ruby">Ruby
    <option value="sql">SQL
    <option value="xml">XML
</select>
#}
