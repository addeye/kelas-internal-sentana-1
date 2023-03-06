@extends('layouts.app')

@section('css')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Library</li>
    </ol>
</nav>
<div class="card">
    <div class="card-header">
        <div class="d-flex align-content-center justify-content-between">
            <h4>Tambah Blog</h4>
            <a href="/blog" class="btn btn-warning">
                <span data-feather="arrow-left"></span> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <h5 class="card-title">Masukkan data</h5>
        <form method="POST" action="/blog">
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Judul</label>
              <input type="text" name=title class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Konten</label>
              <input type="hidden" name="content" class="form-control"/>
              <div id="editor">
                <p>Hello World!</p>
                <p>Some initial <strong>bold</strong> text</p>
                <p><br></p>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </form>
    </div>
  </div>
@endsection

@section('js')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor', {
        modules: {
            toolbar: [
            ['bold', 'italic'],
            ['link', 'blockquote', 'code-block', 'image'],
            [{ list: 'ordered' }, { list: 'bullet' }]
            ]
        },
        placeholder: 'Compose an epic...',
        theme: 'snow'
        });

    //Ini fungsi sinkronisasi inputan konten
    var form = document.querySelector('form');
    form.onsubmit = function() {
    // Populate hidden form on submit
    var content = document.querySelector('input[name=content]');
    content.value = JSON.stringify(quill.getContents());

    // console.log("Submitted", $(form).serialize(), $(form).serializeArray());

    // No back end to actually submit to!
    // alert('Open the console to see the submit data!')
    return false;
    };
</script>
@endsection