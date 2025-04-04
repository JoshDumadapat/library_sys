<x-layout>
    <section class="homepage shadow">
        <div class="overlay">
            <img src="{{ asset('storage/images/hero.jpg') }}" alt="LunaBooks Homepage" class="homepage-image">
            <h1 class="animated-text">L U N A B O O K S</h1>
            <a href="/signup" class="btn-signup" style="text-decoration: none; ">Click here to get started</a>

        </div>
    </section>

    <section>
        <div class="container my-4">
            <div class="row align-items-center">
                <!-- Column 1: Text -->
                <div class="col-md-6 ">
                    <h5 class="mb-4">Welcome to <span><b>LunaBooks!</b></span></h5>
                    <h1 class="main-text mb-4">Manage <br> your <br> books with ease.</h1>
                    <p class="sub-text">With Lunari’s Library Information System</p>
                </div>

                <!-- Column 2: Image and Button -->
                <div class="col-md-6 text-center">
                    <div class="img-overlay-container">
                        <img src="{{ asset('storage/images/homeImg.jpg') }}" alt="Descriptive Alt Text" class="img-fluid">
                        <a href="/signup" class="btn overlay-button " style="text-decoration: none; color:white">Click here to get started</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Books -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

    <section>
        <div class="container mt-4">
            <h6 class=" text-center browse-text">BROWSE BOOKS YOU WANT</h6>
            <div class="swiper book-carousel">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="book-card">
                            <img src="{{ asset('storage/images/book1.png') }}" alt="Book Cover">
                            <p class="mt-3">Harry Potter: The Sorcerer's Stone</p>
                            <button class="borrow-btn">Borrow Now</button>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="book-card">
                            <img src="{{ asset('storage/images/book2.png') }}" alt="Book Cover">
                            <p class="mt-3">Harry Potter: The Chamber of Secrets</p>
                            <button class="borrow-btn">Borrow Now</button>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="book-card">
                            <img src="{{ asset('storage/images/book3.png') }}" alt="Book Cover">
                            <p class="mt-3">Harry Potter: The Prisoner of Azkaban</p>
                            <button class="borrow-btn">Borrow Now</button>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="book-card">
                            <img src="{{ asset('storage/images/book4.jpg') }}" alt="Book Cover">
                            <p class="mt-3">Harry Potter: The Goblet of Fire</p>
                            <button class="borrow-btn">Borrow Now</button>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="book-card">
                            <img src="{{ asset('storage/images/book5.jpg') }}" alt="Book Cover">
                            <p class="mt-3">Harry Potter: The Order of Phoenix </p>
                            <button class="borrow-btn">Borrow Now</button>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="book-card">
                            <img src="{{ asset('storage/images/book6.jpg') }}" alt="Book Cover">
                            <p class="mt-3">Harry Potter: The blood Prince</p>
                            <button class="borrow-btn">Borrow Now</button>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="book-card">
                            <img src="{{ asset('storage/images/book7.png') }}" alt="Book Cover">
                            <p class="mt-3">Harry Potter: The Deathly Hollows</p>
                            <button class="borrow-btn">Borrow Now</button>
                        </div>
                    </div>

                </div>
                <div class="swiper-button-prev btn-outline"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-pagination mt-5"></div>
            </div>
        </div>
    </section>


    <section>
        <div class="container my-5">
            <div class="row align-items-center">
                <!-- Column 2: Image and Button -->
                <div class="col-md-6 text-center">
                    <div class="img-overlay-container">
                        <img src="{{ asset('storage/images/aboutus.png') }}" alt="about us" class="img-fluid">

                    </div>
                </div>
                <!-- Column 1: Text -->
                <div class="col-md-6">
                    <h1 class="about-text">About LunaBooks</h1>
                    <p class="sub-text">LunaBooks is a Library Information System by Lunari,
                        designed to make book management effortless. From cataloging to tracking,
                        borrowing, and returns—everything is streamlined for efficiency.
                        LunaBooks keeps your records accurate and accessible. Manage your books with ease—simpler, faster, smarter!</p>
                    <p>by: Ivan Josh Dumadapat and Rica Item</p>
                </div>

            </div>
        </div>
    </section>

    <section style="background-color: #246484; color: white;">
        <div class="container">
            <div class="row">
                <!-- Logo Section -->
                <div class="col-md-3 mt-3 mb-2">
                    <img src="{{ asset('storage/images/dlogo.png') }}" alt="LunaBooks Logo" class="logo">
                </div>
            </div>

            <div class="row mt-4">
                <!-- Column 1: 5 rows of text -->
                <div class="col-md-3">
                    <ul style="list-style-type: none; padding-left: 0; margin-bottom: 15px;">
                        <!-- Follow Us Section -->
                        <li class="mb-3"><strong>Follow Us</strong></li>
                        <!-- Social Media Links with Icons -->
                        <li class="mb-3">
                            <a href="https://www.instagram.com"
                                style="color: white; margin-right: 20px; transition: color 0.3s;"
                                onmouseover="this.style.color='lightgray'"
                                onmouseout="this.style.color='white'">
                                <i class="bi bi-instagram" style="font-size: 1.5rem;"></i>
                            </a>
                            <a href="https://www.facebook.com"
                                style="color: white; margin-right: 20px; transition: color 0.3s;"
                                onmouseover="this.style.color='lightgray'"
                                onmouseout="this.style.color='white'">
                                <i class="bi bi-facebook" style="font-size: 1.5rem;"></i>
                            </a>
                            <a href="https://www.twitter.com"
                                style="color: white; transition: color 0.3s;"
                                onmouseover="this.style.color='lightgray'"
                                onmouseout="this.style.color='white'">
                                <i class="bi bi-twitter" style="font-size: 1.5rem;"></i>
                            </a>
                        </li>

                        <!-- Address (Clickable Link to Google Maps) -->
                        <li class="mb-3">
                            <a href="https://www.google.com/maps?q=Poblacion+District,+Davao+City,+Davao+del+Sur+Store+Name"
                                style="color: white; text-decoration: none; transition: color 0.3s, text-decoration 0.3s;"
                                target="_blank"
                                onmouseover="this.style.color='lightgray'; this.style.textDecoration='underline'"
                                onmouseout="this.style.color='white'; this.style.textDecoration='none'">
                                Poblacion District, Davao City, Davao del Sur - LunaBooks
                            </a>
                        </li>

                        <!-- Phone (No Link) -->
                        <li class="mb-3">Phone: +63 9236540157</li>

                        <!-- FAQ Link -->
                        <li>
                            <a href="#"
                                style="color: white; text-decoration: none; transition: color 0.3s, text-decoration 0.3s;"
                                onmouseover="this.style.color='lightgray'; this.style.textDecoration='underline'"
                                onmouseout="this.style.color='white'; this.style.textDecoration='none'">
                                FAQ
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 2: Products -->
                <div class="col-md-3">
                    <h5 class="mb-3" style="color: #6ce5e8; font-weight: bold;">Products</h5>
                    <p>Web-based Library Information System</p>
                    <p>Manage your books with ease.</p>
                    <h5 style="color: #6ce5e8; font-weight: bold;">Developers</h5>
                    <p class="pb-0 m-0">Item, Rica S.</p>
                    <p class="p-0 m-0">Dumadapat, Ivan Josh</p>
                </div>

                <!-- Column 3: Solutions -->
                <div class="col-md-3">
                    <h5 class="mb-3" style="color: #6ce5e8; font-weight: bold;">Solutions</h5>
                    <p>Streamlined Solutions for your Library.</p>
                    <p>Effortlessly manage librarian and staff data with our centralized system, automate administrative tasks for smooth operations, and track book checkouts, returns, and inventory in real time to optimize library resources.</p>
                </div>

                <!-- Column 4: About Us -->
                <div class="col-md-3">
                    <h5 class="mb-3" class="mb-2" style="color: #6ce5e8; font-weight: bold;">About Us</h5>
                    <p>LunaBooks is a Library Information System by Lunari, designed to make book management effortless. From cataloging to tracking, borrowing, and returns—everything is streamlined for efficiency. LunaBooks keeps your records accurate and accessible.</p>
                </div>
            </div>

            <!-- Horizontal line -->
            <hr style="border: 1px solid white;">

            <!-- Text Section -->
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="m-0">&copy; 2025 Lunari. All rights reserved.</p>
                    <p>
                        <a class="p-0 m-0" href="#" style="color: white; text-decoration: none; transition: color 0.3s, text-decoration 0.3s;"
                            target="_blank"
                            onmouseover="this.style.color='lightgray'; this.style.textDecoration='underline'"
                            onmouseout="this.style.color='white'; this.style.textDecoration='none'">Terms and Conditions</a>
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".book-carousel", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: 3, // Keep 3 visible to maintain the layout
            loop: true,
            loopAdditionalSlides: 2, // Ensures smooth transition when looping
            coverflowEffect: {
                rotate: 0,
                stretch: 50,
                depth: 200,
                modifier: 1,
                slideShadows: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            mousewheel: true,
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },
        });
    </script>
</x-layout>