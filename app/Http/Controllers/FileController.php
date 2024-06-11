<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('files.index', [
            'files' => File::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('files.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        'uploads', // Path
        $uploadName , // Filename
        'public' // Disk
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
        \Log::debug("DB storage OK");
        // Patró PRG amb missatge d'èxit
        return redirect()->route('files.index', $file)
        ->with('success', 'File successfully saved');
        } else {
        \Log::debug("Disk storage FAILS");
        // Patró PRG amb missatge d'error
        return redirect()->route("files.create")
        ->with('error', 'ERROR uploading file');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, File $file)
    {
        $fileExists = \Storage::disk('public')->exists($file->filepath);
        if ($fileExists) {
            return view('files.show', [
                'file' => $file
            ]);
        } else {
            $file->delete();
            return redirect()->route('files.index')->with('error', 'File was not found, record removed from the database.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        $fileExists = \Storage::disk('public')->exists($file->filepath);
        if ($fileExists) {
            return view('files.edit', [
                'file' => $file
            ]);
        } else {
            $file->delete();
            return redirect()->route('files.index')->with('error', 'File was not found, record removed from the database.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        $upload = $request->file('upload');
        $filename = $upload->getClientOriginalName();
        $filesize = $upload->getSize();
        \Log::debug("storing file '{$filename}' ({$filesize} bytes) ...");

        $uploadName = time() . '_' . $filename;
        $filePath = $upload->storeAs(
            'uploads',
            $uploadName,
            'public'
        );
        if (\Storage::disk('public')->exists($filePath)) {
            \Log::debug('File uploaded successfully!');
            $fullPath = \Storage::disk('public')->path($filePath);
            $file->filepath = $filePath;
            $file->filesize = $filesize;
            $file->save();
            \Log::debug("File details saved in database!");
            return redirect()->route('files.index', $file)->with('success', 'File successfully saved');
        } else {
            \Log::debug('Failed to upload file!');
            return redirect()->route('files.edit', $file)->with('error', 'Failed to upload file');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $fileExists = \Storage::disk('public')->exists($file->filepath);
        if ($fileExists) {
            \Storage::disk('public')->delete([$file->filepath]);
        }
        $file->delete();
        return redirect()->route('files.index')->with('success', 'File was removed!');
    }

    public function delete(File $file)
    {
        return view("files.delete", [
            'file' => $file
        ]);
    }
}