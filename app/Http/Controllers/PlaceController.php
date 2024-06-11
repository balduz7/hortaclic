<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Place;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Auth;


class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
  
            $places = Place::withCount('favorited')
            ->where('name', 'like', "%$searchTerm%")
            ->paginate(5);
           
            return view('places.index', compact('places'));
        } else {
            $places = Place::withCount('favorited')
            ->orderBy('id', 'desc')
            ->paginate(5);            
            return view("places.index", [
                "places" => $places]);
        }
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("places.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar dades del formulari
        $validatedData = $request->validate([
            'name'        => 'required',
            'description' => 'required',
            'address'     => 'required',
            'upload'      => 'required|mimes:gif,jpeg,jpg,png,mp4|max:2048',
            'latitude'    => 'required',
            'longitude'   => 'required',
        ]);
        
        // Obtenir dades del formulari
        $name        = $request->get('name');
        $description = $request->get('description');
        $address = $request->get('address');
        $upload      = $request->file('upload');
        $latitude    = $request->get('latitude');
        $longitude   = $request->get('longitude');

        // Desar fitxer al disc i inserir dades a BD
        $file = new File();
        $fileOk = $file->diskSave($upload);

        if ($fileOk) {
            // Desar dades a BD
            Log::debug("Saving place at DB...");
            $place = Place::create([
                'name'        => $name,
                'description' => $description,
                'address'     => $address,
                'file_id'     => $file->id,
                'latitude'    => $latitude,
                'longitude'   => $longitude,
                'author_id'   => auth()->user()->id,
            ]);
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('places.show', $place)
                ->with('success', __('Place successfully saved'));
        } else {
            \Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("places.create")
                ->with('error', __('ERROR Uploading file'));
        }
    }

    /**
     * Display the specified resource.
     */

    public function show(Place $place)
    {
        //
        

        if (Storage::disk('public')->exists($place->file->filepath)) {
            $place->loadCount('favorited');
            return view("places.show", [
                'place'   => $place,
                'file'    => $place->file,
                'author'  => $place->user,
                'numFavs' => $place->favorited_count,
            ]);
        } else {
            return redirect()->route("places.index")
                ->with('error', __('Erro: falta l\'arxiu'));
        }; 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place)
    {
        //
        return view("places.edit", [
            'place'  => $place,
            'file'   => $place->file,
            'author' => $place->user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place)
    {
       // Validar fitxer
       $validatedData = $request->validate([
        'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024'
    ]);

    $upload = $request->file('upload');
    $fileName = $upload->getClientOriginalName();
    $fileSize = $upload->getSize();
    Log::debug("Storing file '{$fileName}' ($fileSize)...");

    $uploadName = time() . '_' . $fileName;
    $filePath = $upload->storeAs(
        'uploads',      // Path
        $uploadName ,   // Filename
        'public'        // Disk
    );
    if (Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete($place->file->filepath);
        Log::debug("Local storage OK");
        $fullPath = Storage::disk('public')->path($filePath);
        Log::debug("File saved at {$fullPath}");

        $place->file->filepath=$filePath;
        $place->file->filesize=$fileSize;
        $place->file->save();
        Log::debug("DB storage OK");

        $place->name = $request->name;
        $place->description = $request->description;
        $place->longitude = $request->longitude;
        $place->latitude = $request->latitude;

        $place->save();

    
        return redirect()->route('places.show', $place)
            ->with('success', __('Place actualitzat correctament'));
    } else {
        Log::debug("Local storage FAILS");

        return redirect()->route("places.edit")
            ->with('error', __('Place Error actualització'));
    }
                      
           
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        Storage::delete($place->file->filepath);
        $place->delete();
        $place->file->delete();
        return redirect()->route("places.index")->with('success', __('Place eliminat correctament'));
    }

    public function delete(Place $place)
    {
        
        return view("places.delete", [
            'place' => $place
        ]);
    }

    public function favorite(Place $place) 
    {
        $fav = Favorite::create([
            'user_id'  => auth()->user()->id,
            'place_id' => $place->id
        ]);

        return redirect()->back()
            ->with('success', __('Afegit a favorits'));
    }

    public function unfavorite(Place $place) 
    {
        $fav = Favorite::where([
            ['user_id',  '=', auth()->user()->id],
            ['place_id', '=', $place->id],
        ])->first();
        
        $fav->delete();
        
        return redirect()->back()
            ->with('success', __('Eliminat de favorits'));
    }
}
