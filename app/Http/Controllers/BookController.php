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
    // Show the form to create a new book
    public function create()
    {
        // Fetch related data for authors, genres, floors, and shelves
        $authors = Author::all();
        $genres = Genre::all();
        $floors = Floor::all();
        $shelves = Shelf::all();

        return view('books.create', compact('authors', 'genres', 'floors', 'shelves'));
    }

    // Store a new book in the database
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'total_copies' => 'required|integer|min:1',
            'isbn' => 'required|string|max:255',
            'available_copies' => 'required|integer|min:0',
            'book_volume' => 'required|string|max:255',
            'published_date' => 'required|date',
            'floor_id' => 'required|exists:floors,floor_id',
            'shelf_id' => 'required|exists:shelves,shelf_id',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,author_id',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,genre_id',
        ]);

        // Create a new book
        $book = Book::create([
            'title' => $request->title,
            'total_copies' => $request->total_copies,
            'isbn' => $request->isbn,
            'available_copies' => $request->available_copies,
            'book_volume' => $request->book_volume,
            'published_date' => $request->published_date,
            'floor_id' => $request->floor_id,
            'shelf_id' => $request->shelf_id,
        ]);

        // Attach authors and genres to the book
        $book->authors()->attach($request->authors);
        $book->genres()->attach($request->genres);

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    // Display the list of books
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    // Show the form to edit an existing book
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $authors = Author::all();
        $genres = Genre::all();
        $floors = Floor::all();
        $shelves = Shelf::all();

        return view('books.edit', compact('book', 'authors', 'genres', 'floors', 'shelves'));
    }

    // Update an existing book
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'total_copies' => 'required|integer|min:1',
            'isbn' => 'required|string|max:255',
            'available_copies' => 'required|integer|min:0',
            'book_volume' => 'required|string|max:255',
            'published_date' => 'required|date',
            'floor_id' => 'required|exists:floors,floor_id',
            'shelf_id' => 'required|exists:shelves,shelf_id',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,author_id',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,genre_id',
        ]);

        // Find the book and update its details
        $book = Book::findOrFail($id);
        $book->update([
            'title' => $request->title,
            'total_copies' => $request->total_copies,
            'isbn' => $request->isbn,
            'available_copies' => $request->available_copies,
            'book_volume' => $request->book_volume,
            'published_date' => $request->published_date,
            'floor_id' => $request->floor_id,
            'shelf_id' => $request->shelf_id,
        ]);

        // Update authors and genres
        $book->authors()->sync($request->authors);
        $book->genres()->sync($request->genres);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    // Delete a book
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->authors()->detach();
        $book->genres()->detach();
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }
}
