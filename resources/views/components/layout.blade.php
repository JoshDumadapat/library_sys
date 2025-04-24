<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <link rel="icon" type="image/png" href="{{ asset('storage/images/favicon.png') }}">
    <title>LunaBooks</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('storage/images/logo.png') }}" alt="LunaBooks Logo" class="logo">
            </a>

            <!-- Toggler button for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
                aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible menu -->
            <div class="collapse navbar-collapse" id="navbarMenu">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="/">HOME</a>
                    <a class="nav-link scroll-link" href="#about">ABOUT</a>
                    <a class="nav-link" href="/register">REGISTER</a>
                    <a class="nav-link btn-signin" href="/login">LOG IN</a>
                </div>
            </div>
        </div>
    </nav>

    {{ $slot }}

    <!-- Smooth scroll functionality for "ABOUT" link -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.scroll-link').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);

                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 50,
                            behavior: "smooth"
                        });
                    }
                });
            });
        });
    </script>

    <!-- Bootstrap JS bundle (required for the collapse toggling to work) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>