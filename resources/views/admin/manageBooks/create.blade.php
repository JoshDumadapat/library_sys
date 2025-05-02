
<div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="add-book-form-card">
    <div class="card-body px-4 py-4">
        <h4 class="section-title mb-3" style="font-size: 1.5rem;">Book Information</h4>
        <hr class="mb-4">
        <form action="{{ route('managebooks.store') }}" method="POST">
            @csrf

            <div class="row">
                {{-- Title --}}
                <div class="col-md-6 mb-3">
                    <label for="title" style="font-size: 1.1rem;">Title <span style="color: red;">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" style="font-size: 1.05rem;" required>
                </div>

                {{-- ISBN --}}
                <div class="col-md-6 mb-3">
                    <label for="isbn" style="font-size: 1.1rem;">ISBN <span style="color: red;">*</span></label>
                    <input type="number" id="isbn" name="isbn" class="form-control" style="font-size: 1.05rem;" required>
                </div>

                {{-- Volume --}}
                <div class="col-md-6 mb-3">
                    <label for="volume" style="font-size: 1.1rem;">Volume</label>
                    <input type="number" id="volume" name="volume" class="form-control" style="font-size: 1.05rem;">
                </div>

                {{-- Total Copies --}}
                <div class="col-md-6 mb-3">
                    <label for="total_copies" style="font-size: 1.1rem;">Total Copies <span style="color: red;">*</span></label>
                    <input type="number" id="total_copies" name="total_copies" class="form-control" min="1" style="font-size: 1.05rem;" required>
                </div>

                {{-- Published Date --}}
                <div class="col-md-6 mb-3">
                    <label for="published_date" style="font-size: 1.1rem;">Published Date <span style="color: red;">*</span></label>
                    <input type="date" id="published_date" name="published_date" class="form-control" style="font-size: 1.05rem;" required>
                </div>

                {{-- Floor --}}
                <div class="col-md-6 mb-3">
                    <label for="floor_id" style="font-size: 1.1rem;">Floor <span style="color: red;">*</span></label>
                    <select id="floor_id" name="floor_id" class="form-control" style="font-size: 1.05rem;" required>
                        <option value="">-- Select Floor --</option>
                        @foreach ($floors as $floor)
                            <option value="{{ $floor->floor_id }}">{{ $floor->floor_num }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Shelf --}}
                <div class="col-md-6 mb-3">
                    <label for="shelf_id" style="font-size: 1.1rem;">Shelf <span style="color: red;">*</span></label>
                    <select id="shelf_id" name="shelf_id" class="form-control" style="font-size: 1.05rem;" required>
    <option value="">-- Select Shelf --</option>
    @foreach ($shelves as $shelf)
        <option value="{{ $shelf->shelf_id }}">{{ $shelf->shelf_code }}</option>
    @endforeach
</select>
 
                </div>

                {{-- Author(s) --}}
                <div class="col-md-6 mb-3">
                    <label for="author_ids" style="font-size: 1.1rem;">Author(s) <span style="color: red;">*</span></label>
                    <select id="author_ids" name="author_ids[]" class="form-control" multiple required style="font-size: 1.05rem;">
                        @foreach ($authors as $author)
                            <option value="{{ $author->author_id }}">{{ $author->au_fname }} {{ $author->au_lname }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Hold Ctrl (or Cmd) to select multiple authors.</small>
                </div>

                {{-- Genre(s) --}}
                <div class="col-md-6 mb-3">
                    <label for="genre_ids" style="font-size: 1.1rem;">Genre(s) <span style="color: red;">*</span></label>
                    <select id="genre_ids" name="genre_ids[]" class="form-control" multiple required style="font-size: 1.05rem;">
                        @foreach ($genres as $genre)
                        <option value="{{ $genre->genre_id }}">{{ $genre->genre }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Hold Ctrl (or Cmd) to select multiple genres.</small>
                </div>


            </div>

            {{-- Submit Button --}}
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary" style="font-size: 1.2rem;">Add Book</button>
            </div>
        </form>
    </div>
</div>

@vite('resources/js/manageBooks.js') 