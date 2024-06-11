<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\File;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
   
            foreach ($posts as $post){
                $isLiked =Like::where('user_id',auth()->user()->id)
                ->where('post_id', $post->id )
                ->first();
                if ($isLiked){
                    $post->isLiked = True;
                }else{
                    $post->isLiked = False;
                }
            };

            return view('posts.index', compact('posts'));
        } else {
            $posts = Post::withCount('liked')
            ->orderBy('id', 'desc')
            ->paginate(5);
            
            foreach ($posts as $post){
                $isLiked =Like::where('user_id',auth()->user()->id)
                ->where('post_id', $post->id )
                ->first();
                if ($isLiked){
                    $post->isLiked = True;
                }else{
                    $post->isLiked = False;
                }

            }
           
            return view("posts.index", [
                "posts" => $posts
            ]);
        }
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("posts.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar dades del formulari
        $validatedData = $request->validate([
            'name'      => 'required',
            'content'   => 'required',
            'upload'    => 'required|mimes:gif,jpeg,jpg,png,mp4|max:2048',
        ]);
        
        // Obtenir dades del formulari
        $name          = $request->get('name');
        $content       = $request->get('content');
        $upload        = $request->file('upload');

        // Desar fitxer al disc i inserir dades a BD
        $file = new File();
        $fileOk = $file->diskSave($upload);

        if ($fileOk) {
            // Desar dades a BD
            Log::debug("Saving post at DB...");
            $post = Post::create([
                'post_name' => $name,
                'content'   => $content,
                'file_id'   => $file->id,
                'author_id' => auth()->user()->id,
            ]);
            Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('posts.show', $post)
                ->with('success', __('Post successfully saved'));
        } else {
            // Patró PRG amb missatge d'error
            return redirect()->route("posts.create")
                ->with('error', __('ERROR Uploading file'));
        }
    }


    public function show(Post $post)
    {

        //$post->load('comments');
    
        if (Storage::disk('public')->exists($post->file->filepath)) {
            // Cargar el conteo de 'likes' para el post
            $post->loadCount('liked');
    
            return view("posts.show", [
                'post'     => $post,
                'file'     => $post->file,
                'author'   => $post->user,
                'numLikes' => $post->liked_count,
            ]);
        } else {

            return redirect()->route("posts.index")
                ->with('error', __('ERROR actualitzat el post'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view("posts.edit", [
            'post'   => $post,
            'file'   => $post->file,
            'author' => $post->user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'name'      => 'required',
            'content'   => 'required',
            'upload'    => 'required|mimes:gif,jpeg,jpg,png,mp4|max:2048',
        ]);

        // Obtenir dades del formulari
        $name          = $request->get('name');
        $content       = $request->get('content');
        $upload        = $request->file('upload');

        // Desar fitxer (opcional)
        if (is_null($upload) || $post->file->diskSave($upload)) {
            // Actualitzar dades a BD
            Log::debug("Updating DB...");
            $post->post_name      = $name;
            $post->content  = $content;
            $post->save();
            Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('posts.show', $post)
                ->with('success', __('Post successfully saved'));
        } else {
            // Patró PRG amb missatge d'error
            return redirect()->route("posts.edit")
                ->with('error', __('ERROR Uploading file'));
        }
                      
           
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Storage::delete($post->file->filepath);

         $post->delete();
        $post->file->delete();
       
        return redirect()->route("posts.index")->with('success', __('Post elimnat correctament'));
    }


    public function delete(Post $post)
    {
        
        return view("posts.delete", [
            'post' => $post
        ]);
    }

    public function like(Request $request, Post $post): RedirectResponse
    {

        $like = Like::create([
            'user_id'  => auth()->user()->id,
            'post_id' => $post->id
        ]);

        return redirect()->back()
            ->with('success', __('Like successfully saved'));
    }

    public function unlike(Post $post) 
    {

        $like = Like::where([
            ['user_id', '=', auth()->user()->id],
            ['post_id', '=', $post->id],
        ])->first();
        
        $like->delete();

        return redirect()->back()
            ->with('success', __('Like successfully deleted'));
    }
}


