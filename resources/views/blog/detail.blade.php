@extends('layouts.app')

@section('css')

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
            <h4>Detail Blog</h4>
            <a href="/blog" class="btn btn-warning">
                <span data-feather="arrow-left"></span> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-center">
            <h5 class="card-title mb-0">{{$data->title}}</h5>
            <span class="badge bg-secondary" style="padding-top: 6px;">{{$data->status}}</span>
        </div>
        @foreach ($data->category as $item)
        <span class="badge text-bg-primary">{{$item->name}}</span>
        @endforeach
        <hr>
        <img width="200" src="{{asset($data->image)}}" alt="">
        <hr>
        {!! $data->content !!}
    </div>
  </div>
@endsection

@section('js')
@endsection
