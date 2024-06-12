<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Place;
use App\Models\Favorite;
use App\Models\Visibility;
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
        $visibilities = Visibility::all();
        return view("places.create", compact('visibilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'address' => 'required|max:255',
            'description' => 'required|max:255',
            'latitude' => 'required',
            'longitude' => 'required',
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:1024',
            'visibility' => 'required|exists:visibilities,id',
        ]);

        $upload = $request->file('upload');
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->getSize();

        $uploadName = time() . '_' . $fileName;
        $filePath = $upload->storeAs('uploads', $uploadName, 'public');

        if (Storage::disk('public')->exists($filePath)) {
            $fullPath = Storage::disk('public')->path($filePath);

            $file = File::create([
                'filepath' => $filePath,
                'filesize' => $fileSize,
            ]);

            $place = Place::create([
                'name' => $request->name,
                'description' => $request->description,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'author_id' => auth()->user()->id,
                'file_id' => $file->id,
                'visibility_id' => $request->visibility,
            ]);

            return redirect()->route('places.index')
                ->with('success', __('Location successfully saved'));
        } else {
            return redirect()->route('files.create')
                ->with('error', __('Error uploading place'));
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
        $visibilities = Visibility::all();
        return view('places.edit', compact('place','visibilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place)
    {
        $request->validate([
            'upload' => 'mimes:gif,jpeg,jpg,png|max:1024',
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'latitude' => 'required',
            'longitude' => 'required',
            'visibility' => 'required|exists:visibilities,id',
        ]);

        if ($request->hasFile('upload')) {
            Storage::disk('public')->delete($place->file->filepath);

            $newFile = $request->file('upload');
            $newFileName = time() . '_' . $newFile->getClientOriginalName();
            $newFilePath = $newFile->storeAs('uploads', $newFileName, 'public');

            $place->file->update([
                'original_name' => $newFile->getClientOriginalName(),
                'filesize' => $newFile->getSize(),
                'filepath' => $newFilePath,
            ]);
        }

        $place->update([
            'name' => $request->name,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'updated_at' => now(),
            'visibility_id' => $request->visibility,
        ]);

        return redirect()->route('places.show', $place)->with('success', __('Successfully modified location'));
                      
           
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
