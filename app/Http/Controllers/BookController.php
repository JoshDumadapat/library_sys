<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Floor;
use App\Models\Shelf;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function create()
{
    $authors = Author::all();
    $genres = Genre::all();
    $floors = Floor::all();
    $shelves = Shelf::all();

    
    return view('admin.manageBooks.create', [
        'authors' => $authors,
        'genres' => $genres,
        'floors' => $floors,
        'shelves' => $shelves,
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'isbn' => 'required|numeric',
        'volume' => 'nullable|numeric',
        'total_copies' => 'required|numeric|min:1',
        'published_date' => 'required|date',
        'floor_id' => 'required|exists:floors,floor_id',
        'shelf_id' => 'required|exists:shelves,shelf_id',
        'author_ids' => 'required|array',
        'genre_ids' => 'required|array',
    ]);

    // Create the book
    $book = Book::create([
        'title' => $request->title,
        'isbn' => $request->isbn,
        'volume' => $request->volume,
        'total_copies' => $request->total_copies,
        'published_date' => $request->published_date,
        'floor_id' => $request->floor_id,
        'shelf_id' => $request->shelf_id,
    ]);

    // Attach authors and genres (if using many-to-many relationships)
    $book->authors()->attach($request->author_ids);
    $book->genres()->attach($request->genre_ids);

    return redirect()->route('managebooks.index')->with('success', 'Book added successfully!');
}


    // Display the list of books
    public function index()
    {
        // Fetch all books and related data
        $books = Book::with(['authors', 'genres', 'floor', 'shelf'])->get();
        $authors = Author::all();
        $genres = Genre::all();
        $floors = Floor::all();
        $shelves = Shelf::all();
    
        // Return the index view with the data
        return view('admin.manageBooks.index', compact('books', 'authors', 'genres', 'floors', 'shelves'));
    }
    
    public function edit($book_id)
{
    $book = Book::with(['authors', 'genres', 'floor', 'shelf'])->find($book_id);
    
    if ($book) {
        $authors = Author::all(); // All authors for the <select>
        $genres = Genre::all();   // All genres for the <select>

        return response()->json([
            'book' => $book->load('authors', 'genres'),
            'authors' => Author::all(),
            'genres' => Genre::all(),
            'floors' => Floor::all(),
            'shelves' => Shelf::all(),
        ]);
        
    } else {
        return response()->json([
            'error' => 'Book not found',
        ], 404);
    }
}


public function update(Request $request, $book_id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'isbn' => 'required|numeric',
        'volume' => 'nullable|numeric',
        'total_copies' => 'required|numeric|min:1',
        'published_date' => 'required|date',
        'floor_id' => 'required|exists:floors,floor_id',
        'shelf_id' => 'required|exists:shelves,shelf_id',
        'author_ids' => 'required|array|min:1',
        'author_ids.*' => 'exists:authors,author_id',
        'genre_ids' => 'required|array|min:1',
        'genre_ids.*' => 'exists:genres,genre_id',
    ]);

    $book = Book::findOrFail($book_id);
    $book->update([
        'title' => $request->title,
        'isbn' => $request->isbn,
        'volume' => $request->volume,
        'total_copies' => $request->total_copies,
        'published_date' => $request->published_date,
        'floor_id' => $request->floor_id,
        'shelf_id' => $request->shelf_id,
    ]);

    $book->authors()->sync($request->author_ids);
    $book->genres()->sync($request->genre_ids);

    // ✅ Return JSON instead of redirect
    return response()->json(['message' => 'Book updated successfully!']);
}

    // Delete a book
    public function destroy($book_id)
    {
        $book = Book::findOrFail($book_id);
        $book->authors()->detach();
        $book->genres()->detach();
        $book->delete();
    
        return response()->json(['message' => 'Book deleted successfully!']);
    }
    


    public function show($id)
{
    $book = Book::with(['authors', 'genres', 'floor', 'shelf'])->findOrFail($id);

    // Fetch all options (if needed for select dropdowns)
    $allGenres = Genre::all();
    $allFloors = Floor::all();
    $allShelves = Shelf::all();

    return response()->json([
        'book' => $book,
        'authors' => $book->authors,
        'genres' => $allGenres,
        'floors' => $allFloors,
        'shelves' => $allShelves,
    ]);
}



public function showBook($book_id)
{
    $book = Book::with(['authors', 'floor', 'shelf'])->where('book_id', $book_id)->first();

    if (!$book) {
        return response()->json(['message' => 'Book not found'], 404);
    }

    // Prepare the data to match Select2 or your frontend needs
    return response()->json([
        'id' => $book->book_id,
        'text' => $book->book_id . ' - ' . $book->title,
        'book_id' => $book->book_id,
        'title' => $book->title,
        'author' => $book->authors->pluck('au_fname')->implode(', ') . ' ' . $book->authors->pluck('au_lname')->implode(', '),
        'isbn' => $book->isbn,
        'floor' => $book->floor->floor_num, // Assuming floor has a `floor_num` attribute
        'shelf_code' => $book->shelf ? $book->shelf->shelf_code : null, // Access shelf_code properly
    ]);
}


public function searchBooks(Request $request)
{
    $books = Book::select('book_id', 'title')
        ->where('title', 'like', '%' . $request->q . '%')
        ->orWhere('book_id', 'like', '%' . $request->q . '%')
        ->get()
        ->map(function ($book) {
            return [
                'id' => $book->book_id, // returned as id
                'text' => $book->book_id . ' - ' . $book->title
            ];
        });

    return response()->json($books);
}




}
