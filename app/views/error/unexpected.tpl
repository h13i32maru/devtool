{% extends "layouts/default.tpl" %}

{% block content %}
Error::Unexpected
<div>
{{exception|nl2br}}
</div>
{% endblock %}
