<x-sidebar>

    <div class="container-fluid" style="height: 100%;">

        <div class="row mt-2">
            <div class="col">
                <div class="card text-bg-dark" style="border-radius: 15px; height: 280px; margin-bottom: 100px;">
                    <img class="card-img" src="{{ asset('storage/images/dashboard/dashboard.jpg') }}" alt="LunaBooks Homepage" style="border-radius: 15px; height: 280px;">
                    <div class="card-img-overlay d-flex align-items-center justify-content-start">
                        <!-- Flex container for image and text -->
                        <img src="{{ asset('storage/images/dashboard/book1.png') }}" alt="Book" class="book-dashboard me-5">
                        <div class="text-container ms-3">
                            <h2 class="card-title fw-bold mb-3">Welcome back to LunaBooks!</h2>
                            <p class="card-text mb-4">Start your transactions now with LunaBooks.</p>
                            <a href="/register" class="btn-dashboard" style="text-decoration: none; padding-left: 100px; padding-right: 100px;">Add Books</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left side -->
            <div class="col-6">
                <div class="card shadow" style="width: 100%; height: 535px; overflow: hidden;">
                    <div class="card-body shdow d-flex flex-column p-3" style="height: 100%;">
                        <!-- Header + Add Book button -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0"><strong>Top choices:</strong></h5>
                            <button class="btn btn-outline-dark">Add Book</button>
                        </div>

                        <!-- Scrollable area inside the card -->
                        <div style="flex-grow: 1; overflow-y: auto;">
                            <div class="row g-2">
                                <!-- Book 1 -->
                                <div class="col-3">
                                    <div class="card h-100"
                                        style="transition: transform 0.2s, box-shadow 0.2s;"
                                        onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                                        <img src="{{ asset('storage/images/book1.png') }}" class="card-img-top" alt="Harry Potter 1">
                                        <div class="card-body p-2">
                                            <p class="card-text text-center small mb-0">Sorcerer's Stone</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Book 2 -->
                                <div class="col-3">
                                    <div class="card h-100"
                                        style="transition: transform 0.2s, box-shadow 0.2s;"
                                        onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                                        <img src="{{ asset('storage/images/book2.png') }}" class="card-img-top" alt="Harry Potter 2">
                                        <div class="card-body p-2">
                                            <p class="card-text text-center small mb-0">Chamber of Secrets</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Book 3 -->
                                <div class="col-3">
                                    <div class="card h-100"
                                        style="transition: transform 0.2s, box-shadow 0.2s;"
                                        onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                                        <img src="{{ asset('storage/images/book3.png') }}" class="card-img-top" alt="Harry Potter 3">
                                        <div class="card-body p-2">
                                            <p class="card-text text-center small mb-0">Prisoner of Azkaban</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Book 4 -->
                                <div class="col-3">
                                    <div class="card h-100"
                                        style="transition: transform 0.2s, box-shadow 0.2s;"
                                        onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                                        <img src="{{ asset('storage/images/book4.jpg') }}" class="card-img-top" alt="Harry Potter 4">
                                        <div class="card-body p-2">
                                            <p class="card-text text-center small mb-0">Goblet of Fire</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Book 5 -->
                                <div class="col-3">
                                    <div class="card h-100"
                                        style="transition: transform 0.2s, box-shadow 0.2s;"
                                        onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                                        <img src="{{ asset('storage/images/book5.jpg') }}" class="card-img-top" alt="Harry Potter 5">
                                        <div class="card-body p-2">
                                            <p class="card-text text-center small mb-0">Order of the Phoenix</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end scrollable -->
                    </div>
                </div>
            </div>





            <!-- Right side with 2x2 card layout -->
            <div class="col-6">
                <div class="row mb-3">
                    <!-- Card 1 - Overdue -->
                    <div class="col">
                        <div class="card h-100 card-hover">
                            <div class="card-body d-flex shadow justify-content-between align-items-center">
                                <div>
                                    <small class="text-secondary" style="font-size: 1.2rem;">Overdue</small><br>
                                    <span class="text-danger fw-bold" style="font-size: 2.6rem;">005</span>
                                </div>
                                <img src="{{ asset('storage/images/od.png') }}" alt="Overdue Icon" style="width: 60px; height: 60px;">
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 - Requests -->
                    <div class="col">
                        <div class="card h-100 card-hover">
                            <div class="card-body d-flex shadow justify-content-between align-items-center">
                                <div>
                                    <small class="text-secondary" style="font-size: 1.2rem;">Requests</small><br>
                                    <span class="fw-bold" style="font-size: 2.6rem; color: #DBA910;">008</span>
                                </div>
                                <img src="{{ asset('storage/images/reqs.png') }}" alt="Request Icon" style="width: 60px; height: 60px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Card 1 - Available Books -->
                    <div class="col">
                        <div class="card h-100 card-hover">
                            <div class="card-body d-flex shadow justify-content-between align-items-center">
                                <div>
                                    <small class="text-secondary" style="font-size: 1.2rem;">Available Books</small><br>
                                    <span class="fw-bold" style="font-size: 2.6rem; color: #295183;">050</span>
                                </div>
                                <img src="{{ asset('storage/images/avail.png') }}" alt="Available Books Icon" style="width: 60px; height: 60px;">
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 - Members -->
                    <div class="col">
                        <div class="card h-100 card-hover">
                            <div class="card-body d-flex shadow justify-content-between align-items-center">
                                <div>
                                    <small class="text-secondary" style="font-size: 1.2rem;">Members</small><br>
                                    <span class="text-success fw-bold" style="font-size: 2.6rem;">120</span>
                                </div>
                                <img src="{{ asset('storage/images/memb.png') }}" alt="Members Icon" style="width: 60px; height: 60px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>

</x-sidebar>