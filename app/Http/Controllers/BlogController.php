<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('blog.list', [
            'data' => Blog::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blog.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // return $request->all();
        $rules =[
            'title' => 'required|unique:blogs|max:255',
            'content' => 'required',
            'image' => 'image'
        ];
        $messages = [
            'title.required' => 'Judul wajib diisi',
            'title.unique' => 'Judul sudah digunakan silahkan ketik yang lain',
            'title.max' => 'Judul terlalu panjang max 255 karakter',
            'content.required' => 'Konten wajib diisi',
            'image.image' => 'Format nya bukan gambar'
        ];

        $validated = $request->validate($rules,$messages);

        $datarow = $request->all();

        //Proses upload image
        $image_path = $request->image->store('images','public');
        //Proses simpan lokasi file di tabel database
        $datarow['image'] = $image_path;

        // return $datarow;

        $datarow['slug'] = Str::of($request->title)->slug('-');

        Blog::create($datarow);

       /*  $blog = new Blog();
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->save(); */

        return redirect('blog');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return view('blog.detail',[
            'data' => $blog
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('blog.edit',[
            'data' => $blog
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        // return $request->all();
        $rules =[
            'title' => 'required|unique:blogs,title,'.$blog->id.'|max:255',
            // 'title' => [
            //     'required',
            //     Rule::unique('blogs')->ignore($blog->id),
            //     'Max:255'
            // ],
            'content' => 'required',
        ];
        $messages = [
            'title.required' => 'Judul wajib diisi',
            'title.unique' => 'Judul sudah digunakan silahkan ketik yang lain',
            'title.max' => 'Judul terlalu panjang max 255 karakter',
            'content.required' => 'Konten wajib diisi',
        ];

       $request->validate($rules,$messages);

        $datarow = $request->all();
        if($request->image){

            //Menyimpan lokasi gambar lama
            $oldimage = $blog->image;

            //Proses upload image
            $image_path = $request->image->store('images','public');

            //Proses simpan lokasi file di tabel database
            $datarow['image'] = $image_path;

            //Proses hapus gambar lama
            Storage::delete($oldimage);
        }

        $datarow['slug'] = Str::of($request->title)->slug('-');


        $blog->update($datarow);

        return redirect('blog');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        // return "Ini method hapus";
        $blog->delete();
        return redirect('blog');
    }
}
