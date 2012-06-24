{% extends "layouts/default.tpl" %}

{% block content %}
Error::Database
<div>
{{exception|nl2br}}
</div>
{% endblock %}
