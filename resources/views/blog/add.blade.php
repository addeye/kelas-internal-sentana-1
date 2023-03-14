@extends('layouts.app')

@section('css')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    div#editor.is-invalid {
        border: 1px solid red;
    }
</style>
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
        <form method="POST" class="" action="/blog" novalidate enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Judul</label>
              <input type="text" name=title value="{{old('title')}}" class="form-control @error('title') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Gambar</label>
                <input type="file" accept="image/*" name=image value="{{old('image')}}" class="form-control @error('image') is-invalid @enderror" id="image" aria-describedby="imageHelp">
                  @error('image')
                  <div class="invalid-feedback">
                      {{ $message }}
                  </div>
                  @enderror
              </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Konten</label>
              <input type="hidden" name="content" class="form-control @error('content') is-invalid @enderror"/>
              @error('content')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
              <div class="@error('content') is-invalid @enderror" id="editor">{{old('content')}}</div>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Kategori</label>
                <select name="category_id[]" multiple class="form-control" id="category_id">
                <option value="">Pilih</option>
                @foreach ($category as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
                </select>
                  @error('category_id')
                  <div class="invalid-feedback">
                      {{ $message }}
                  </div>
                  @enderror
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
        var myEditor = document.querySelector('#editor')
        var html = myEditor.children[0].innerHTML
        content.value = html;

        // console.log("Submitted", $(form).serialize(), $(form).serializeArray());

        // No back end to actually submit to!
        // alert('Open the console to see the submit data!')
        return true;
    };
</script>
@endsection
