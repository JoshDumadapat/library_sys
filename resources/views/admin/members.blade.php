<x-sidebar>
    <div class="content">
        <!-- Header -->
        <h5 id="return-books-header" class="ms-3 mt-2"><strong>Members</strong></h5>

        <!-- Card with Table -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="book-card">
            <div class="card-body">
                <!-- Search Bar and Buttons -->
                <div class="d-flex justify-content-between mb-0 p-0">
                    <div class="input-group w-50">
                        <h5 class="fw-bold">Members List</h5>
                    </div>
                </div>

                <hr class="mb-3 mt-0">

                <div class="d-flex justify-content-between mb-3">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" placeholder="Search Member" aria-label="Search Member">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                </div>


                <!-- Table -->
                <div id="book-table">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="book-table-body"> 
                        @foreach($members as $member)
                                <tr>
                                    <td>{{ $member->id }}</td>
                                    <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->contact_num }}</td>
                                    <td>
                                        {{ $member->address->street ?? '' }},
                                        {{ $member->address->city ?? '' }},
                                        {{ $member->address->region ?? '' }}
                                    </td>

                                    <td>
                                        @if($member->status === 'Active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning text-black">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.members.deactivate', $member->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-delete" style="border-radius: 8px;">
                                                <i class="bi bi-person-x me-1"></i>&nbsp;Deactivate
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.members.activate', $member->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-add" style="border-radius: 8px;">
                                                <i class="bi bi-person-check me-1"></i>Activate
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3" id="pagination">
                </div>
            </div>
        </div>
    </div>
</x-sidebar>
@vite('resources/js/pagination.js')  