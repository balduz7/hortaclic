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
    
    
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
   
            // Realizar la búsqueda en la base de datos
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
         // Validar fitxer
         $validatedData = $request->validate([
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024',
            'body'          => 'required',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            

        ]);
       
        // Obtenir dades del fitxer
        $upload = $request->file('upload');
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->getSize();
        Log::debug("Storing file '{$fileName}' ($fileSize)...");
 
 
        // Pujar fitxer al disc dur
        $uploadName = time() . '_' . $fileName;
        $filePath = $upload->storeAs(
            'uploads',      // Path
            $uploadName ,   // Filename
            'public'        // Disk
        );
       
        if (Storage::disk('public')->exists($filePath)) {
            Log::debug("Disk storage OK");
            $fullPath = Storage::disk('public')->path($filePath);
            Log::debug("File saved at {$fullPath}");
            // Desar dades a BD
            $file = File::create([
                'filepath' => $filePath,
                'filesize' => $fileSize,
                
            ]);
            Log::debug("DB storage OK");
            $post = Post::create([
                'body' =>$request->body,
                'file_id' =>$file->id,
                'latitude' =>$request->latitude,
                'longitude' =>$request->longitude,
                'visibility_id'=>$request->visibility_id,
                'author_id' =>auth()->user()->id

            ]);
            Log::debug("DB Storage OK");
            return redirect()->route('posts.show', $post)
                ->with('success',__('Post pujat correctament'));
            
        } else {
            Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("posts.create")
                ->with('error',__( 'ERROR pujant l\'arxiu'));
        }


    }


    public function show(Post $post)
    {
        // Cargar los comentarios relacionados con el post
        $post->load('comments');
    
        // Primero, verificar si el archivo asociado al post existe
        if (Storage::disk('public')->exists($post->file->filepath)) {
            // Cargar el conteo de 'likes' para el post
            $post->loadCount('liked');
    
            return view("posts.show", [
                "post" => $post
            ]);
        } else {
            // Redirigir si el archivo no existe
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
            "post" => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
       // Validar fitxer
       $validatedData = $request->validate([
        'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024'
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
    if (Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete($post->file->filepath);
        Log::debug("Local storage OK");
        $fullPath = Storage::disk('public')->path($filePath);
        Log::debug("File saved at {$fullPath}");
        // Desar dades a BD
        $post->file->filepath=$filePath;
        $post->file->filesize=$fileSize;
        $post->file->save();
        Log::debug("DB storage OK");
        // Patró PRG amb missatge d'èxit

        $post->body = $request->body;
        $post->longitude = $request->longitude;
        $post->latitude = $request->latitude;
        $post->save();

    
        return redirect()->route('posts.show', $post)
            ->with('success', __('Post actualitzat correctament'));
    } else {
        Log::debug("Local storage FAILS");
        // Patró PRG amb missatge d'error
        return redirect()->route("posts.edit")
            ->with('error', __('ERROR editant el post'));
    }
                      
           
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        
        //borramos archivo local
        Storage::delete($post->file->filepath);
        
        //borramos el post
         $post->delete();

        //borramos en base de datos
        $post->file->delete();

       
        return redirect()->route("posts.index")->with('success', __('Post elimnat correctament'));
    }

    public function like(Request $request, Post $post): RedirectResponse
    {
        $this->authorize('like', $post);
        $like =Like::where('user_id',auth()->user()->id)
                    ->where('post_id', $post->id )
                    ->first();
        if($like){
            $like->delete();
            Log::debug("Like eliminat correctament");
            return redirect()->route('posts.index');
           

        }else{
            $like = Like::create([
                'user_id' => auth()->user()->id,
                'post_id' => $post->id,
            ]);
            $isLiked=True;
            Log::debug("Like creat correctament");
            return redirect()->route('posts.index');
            
        }
    }
}


