<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Genre;

class DropdownController extends Controller
{
    public function addAuthor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $names = explode(' ', $request->name, 2); // Split first and last name
        $author = new Author();
        $author->au_fname = $names[0];
        $author->au_lname = $names[1] ?? ''; // If last name exists
        $author->save();

        return response()->json(['success' => true, 'author_id' => $author->author_id]);
    }

    public function addGenre(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $genre = new Genre();
        $genre->genre = $request->name;
        $genre->save();

        return response()->json(['success' => true, 'genre_id' => $genre->genre_id]);
    }
}
