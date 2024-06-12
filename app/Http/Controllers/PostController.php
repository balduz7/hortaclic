<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\File;
use App\Models\Like;
use App\Models\Visibility;
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
        $visibilities = Visibility::all();
        return view("posts.create", compact('visibilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar fitxer
        
        $validatedData = $request->validate([
            'name' => 'required|max:20',
            'content' => 'required|max:150',
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024',
            'visibility_id' => 'required|exists:visibilities,id',
        ]);
        
       
        // Obtenir dades del fitxer
        $upload = $request->file('upload');
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->getSize();
        \Log::debug("Storing file '{$fileName}' ($fileSize)...");
 
 
        // Pujar fitxer al disc dur
        $uploadName = time() . '_' . $fileName;
        $filePath = $upload->storeAs(
            'uploads',      // Path
            $uploadName ,   // Filename
            'public'        // Disk
        );
       
        if (\Storage::disk('public')->exists($filePath)) {
            \Log::debug("Disk storage OK");
            $fullPath = \Storage::disk('public')->path($filePath);
            \Log::debug("File saved at {$fullPath}");
            // Desar dades a BD
            $file = File::create([
                'filepath' => $filePath,
                'filesize' => $fileSize,
            ]);
            \Log::debug('Visibility ID from form: ' . $request->visibility_id);
            // Create del registro post
            $post = Post::create([
                'post_name' => $request->name,
                'content' => $request->content,
                'author_id' => $user = auth()->user()->id,
                'file_id' => $file->id,
                'visibility_id' => $request->visibility_id,
                ]);                
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('posts.index')
                ->with('success', __('Post saved successfully'));
        } else {
            \Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("files.create")
                ->with('error', __('Error uploading post'));
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
        $visibilities = Visibility::all();
        return view('posts.edit', compact('post','visibilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validar los datos del formulario
        $request->validate([
            'upload' => 'mimes:gif,jpeg,jpg,png|max:1024',
            'name' => 'required|max:20',
            'content' => 'required|max:150',
            'visibility' => 'required|exists:visibilities,id',
        ]);
        // Comprueba si se ha enviado un nuevo archivo
        if ($request->hasFile('upload')) {
            // Elimina el archivo anterior del disco
            Storage::disk('public')->delete($post->file->filepath);
    
            // Sube el nuevo archivo al disco
            $newFile = $request->file('upload');
            $newFileName = time() . '_' . $newFile->getClientOriginalName();
            $newFilePath = $newFile->storeAs('uploads', $newFileName, 'public');
            // Actualiza la información del archivo en la base de datos
            $post->file->update([
                'original_name' => $newFile->getClientOriginalName(),
                'filesize' => $newFile->getSize(),
                'filepath' => $newFilePath,
            ]);
        }

        $post->update([
            'post_name' => $request->name,
            'content' => $request->content,
            'updated_at' => now(),
            'visibility_id' => $request->visibility,
        ]);

    
        return redirect()->route('posts.show', $post)->with('success', __('Successfully modified post'));
                      
           
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


