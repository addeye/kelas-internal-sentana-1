<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Category;
use Database\Factories\BlogFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $blog = Blog::query();
        $sort = request('sort_val') ?? 'DESC';

        /* if(request('sort_val')){
            return request('sort_val');
        }else{
            return 'DESC';
        }  */

        if(request('sort_name')=='judul'){
            $sort = $sort == 'DESC' ? 'ASC' : 'DESC';
            $blog->orderBy('title', request('sort_val'));
        }

        if(request('sort_name')=='status'){
            $sort = $sort == 'DESC' ? 'ASC' : 'DESC';
            $blog->orderBy('status', request('sort_val'));
        }

        if(request('cari')){
            $blog->where('title','LIKE','%'.request('cari').'%');
        }

        $blog = $blog->orderBy('created_at', $sort)->paginate()->withQueryString();

        return view('blog.list', [
            'data' => $blog->withPath('blog'),
            'sort' => $sort
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blog.add', [
            'category' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // return $request->all();
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

        $request->validate($rules, $messages);

        $datarow = $request->all();

        //Proses upload image
        $image_path = $request->image->store('images', 'public');
        //Proses simpan lokasi file di tabel database
        $datarow['image'] = $image_path;

        // return $datarow;

        $datarow['slug'] = Str::of($request->title)->slug('-');

        $blog = Blog::create($datarow);
        $blog_id = $blog->id;

        foreach ($request->category_id as $item) {
            $datarow = [
                'blog_id' => $blog_id,
                'category_id' => $item
            ];
            BlogCategory::create($datarow);
        }

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
        // return $blog;
        // return $blog->load('blogCategory.category');
        $table = DB::table('blogs')
                        ->where('id', $blog->id)
                        ->first();

        $category = DB::table('blog_category')
                ->where('blog_id', $blog->id)
                ->join('category', 'category.id', '=', 'blog_category.category_id')
                ->select('category.name as name')
                ->get();
        $table->category = $category;

        return view('blog.detail', [
            'data' => $table
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
        return view('blog.edit', [
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

        $request->validate($rules, $messages);

        $datarow = $request->all();
        if ($request->image) {
            //Menyimpan lokasi gambar lama
            $oldimage = $blog->image;

            //Proses upload image
            $image_path = $request->image->store('images', 'public');

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
