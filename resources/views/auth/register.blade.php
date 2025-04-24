<x-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <style>
            body {
                background: url("{{ asset('storage/images/registerbg.jpg') }}") no-repeat center center fixed;
                background-size: cover;
                margin: 0;
                padding: 0;
                overflow: auto;
                font-family: Arial, sans-serif;
            }

            .login-container {
                display: flex;
                flex-wrap: wrap;
                width: 70%;
                min-height: 600px;
                border-radius: 20px;
                overflow: hidden;
                background: rgba(202, 202, 202, 0.2);
                backdrop-filter: blur(10px);
                margin: 5% auto;
                position: relative;
                z-index: 5;
                margin-top: 150px;
            }

            .left-container,
            .right-container {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 30px;
                position: relative;
                z-index: 2;
                min-width: 300px;
            }

            .left-container {
                flex: 1;
                max-width: 350px;
                background: linear-gradient(135deg, #1983b6, #235872);
                color: white;
                border-top-left-radius: 20px;
                border-bottom-left-radius: 20px;
                padding: 40px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: flex-start;
            }

            .right-container {
                flex: 2;
                background-color: rgba(255, 255, 255, 0.2);
                border-top-right-radius: 20px;
                border-bottom-right-radius: 20px;
                padding: 30px;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }


            .astronaut-header {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                flex-direction: row-reverse;

            }

            .astronaut-inside {
                width: 230px;
                height: auto;
                animation: float1 6s ease-in-out infinite;
                flex-shrink: 0;
                margin-bottom: 40px;
                /* or something smaller like 10px */
                margin-left: -30px;
                /* optional, fine-tune spacing between text and image */
            }


            @keyframes float1 {
                0% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(10px);
                }

                100% {
                    transform: translateY(0);
                }
            }

            @media (max-width: 768px) {
                .login-container {
                    flex-direction: column;
                    width: 90%;
                    height: auto;
                }

                .left-container,
                .right-container {
                    width: 100%;
                    max-width: 100%;
                    padding: 20px;
                }

                .left-container {
                    border-top-left-radius: 20px;
                    border-top-right-radius: 20px;
                    border-bottom-left-radius: 0;
                    align-items: center;
                    text-align: center;
                }

                .astronaut-header {
                    display: flex;
                    align-items: center;
                    justify-content: flex-start;
                    flex-direction: row-reverse;
                }

                .astronaut-inside {
                    width: 150px;
                    height: auto;
                    animation: float1 6s ease-in-out infinite;
                    flex-shrink: 0;
                    margin-bottom: 20px;
                    margin-left: 30px;
                }
            }
        </style>
    </head>

    <body>

        <div class="login-container shadow-sm">
            <div class="left-container shadow-sm" style="align-items: flex-start;">
                <div class="astronaut-header d-flex align-items-center mb-0">
                    <img src="{{ asset('storage/images/astronaut3.png') }}" alt="Astronaut" class="astronaut-inside me-0">
                    <h1 class="mb-0"><strong>Hello new Member!</strong></h1>

                </div>
                <p class="mb-5 fs-5 justify-content-start mb-5">Welcome to <em>LunaBooks</em>, enter your personal details and start your journey with us. Unlock a world of stories and knowledge at your fingertips!</p>
                <p class="mb-2 mt-3">Already have an account?</p>
                <a href="{{ route('login') }}" class="btn btn-light w-100 fw-bold mb-4" style="color: #235872;">
                    Login
                </a>
            </div>



            <div class="right-container shadow-sm">
                <div class="container py-4">
                    <h2 class="mb-5 fw-bold text-start pb-3" style="color: #235872;  ">MEMBERSHIP REGISTRATION FORM</h2>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="row mb-3 ">
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">First name <span class="text-danger">*</span></small>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Last name <span class="text-danger">*</span></small>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Contact No. <span class="text-danger">*</span></small>
                                <input type="text" name="contact_num" value="{{ old('contact_num') }}" class="form-control" required minlength="11" maxlength="11">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Street <span class="text-danger">*</span></small>
                                <input type="text" name="street" value="{{ old('street') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">City <span class="text-danger">*</span></small>
                                <input type="text" name="city" value="{{ old('city') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Region <span class="text-danger">*</span></small>
                                <input type="text" name="region" value="{{ old('region') }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Email <span class="text-danger">*</span></small>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Password <span class="text-danger">*</span></small>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-5 ">
                                <small class="text-start d-block">Confirm Password <span class="text-danger">*</span></small>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 mb-3" style="background-color: #235872; color: white;">Register</button>
                    </form>

                    <a href="{{ route('login') }}" style="color: black;">Already have an account? | Login</a>
                </div>
            </div>

        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
</x-layout>