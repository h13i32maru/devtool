{% extends "layouts/default.tpl" %}

{% block js %}
<script src="{{ url('js/code.js') }}"></script>
<link rel="stylesheet" href="{{ url('highlight/styles/github.css') }}">
<script src="{{ url('highlight/highlight.pack.js') }}"></script>
<script>hljs.initHighlightingOnLoad();</script>
{% endblock %}


{% block content %}
<a class="help label label-success" data-toggle="modal" href="#help">?</a>
<div class="container-fluid">
<div class="row-fluid">

<div class="span3">
  <div class="well sidebar-nav">
    <ul class="nav nav-list">
      {% for code_pack in code_packs %}
      <li><a href="{{ url('code/show', {p:code_pack.path}) }}">{{ code_pack.title ? code_pack.title : '-' }}</a>
      {% endfor %}
    </ul>
  </div>
</div>

<div class="span9">
{% block code_content %}
{% endblock %}
</div>

</div>
</div>

<div class="modal hide" id="help">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Help</h3>
  </div>
  <div class="modal-body">
    <p>Codeはコードペーストを少し改良したようなものです。</p>
    <ul>
      <li>自分が作成したコードを一覧で見ることができる
      <li>過去のコードを編集することができる
      <li>URLを知っていれば他人のコードを見ることができる
      <li>複数のコードを1つにまとめることができる
    </ul>
    <p>今後の機能追加はこんな感じ。</p>
    <ul>
      <li>個々のコードにファイル名をつける
      <li>Raw表示
      <li>コードの検索機能(Title, Descriptionを対象)
      <li>他人のコードの閲覧履歴機能
      <li>コードをブックマークする機能
      <li>コードにいいねをできる機能
    </ul>
  </div>
    <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
  </div>
</div>
{% endblock %}
