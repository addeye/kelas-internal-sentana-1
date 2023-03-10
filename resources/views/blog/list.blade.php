@extends('layouts.app')

@section('content')
<div class="mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Library</li>
        </ol>
    </nav>
</div>
<div class="card">
    <div class="card-header">
        <div class="d-flex align-content-center justify-content-between">
            <h4>Data Blog</h4>
            <a href="/blog/create" class="btn btn-primary">
                <span data-feather="plus"></span> Tambah
            </a>
        </div>
    </div>
    <div class="card-body">
        <h5 class="card-title">List Data</h5>
        <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Judul</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data as $key=>$item)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item->title}}</td>
                    <td>{{$item->status}}</td>
                    <td>
                        <a href="/blog/{{$item->id}}"><span data-feather="eye"></span></a>
                        <a href="/blog/{{$item->id}}/edit"><span data-feather="edit"></span></a>

                        <a href="javascript:" onclick="deleteData({{$item->id}})"><span data-feather="trash"></span></a>
                        <form class="d-none" id="formdelete-{{$item->id}}" action="/blog/{{$item->id}}" method="POST">
                            @method('DELETE')
                            @csrf
                        </form>
                    </td>
                  </tr>
              @endforeach
            </tbody>
        </table>
    </div>
  </div>
@endsection

@section('js')
<script>
    function deleteData(id){
        if(confirm("Apakah anda yakin?")){
            document.getElementById('formdelete-'+id).submit()
        }
        return false;
    }
</script>
@endsection
