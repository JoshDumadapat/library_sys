<x-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <style>
            body {
                background: url("{{ asset('storage/images/registerbg.jpg') }}") no-repeat center center fixed;
                background-size: cover;
                height: 100vh;
                margin: 0;
                display: flex;
                flex-direction: column;
            }


            .login-container {
                display: flex;
                width: 70%;
                height: 600px;
                border-radius: 20px;
                overflow: hidden;
                background: rgba(202, 202, 202, 0.2);
                backdrop-filter: blur(10px);
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                position: relative;
                z-index: 5;
            }

            .left-container,
            .right-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 50%;
                padding: 30px;
                position: relative;
                z-index: 2;
            }

            /* Left container styling */
            .left-container {
                background: linear-gradient(135deg, #1983b6, #235872);
                color: white;
                width: 400px;
                border-top-left-radius: 20px;
                border-bottom-left-radius: 20px;
                border: black;
                padding: 40px;
            }

            /* Right container styling */
            .right-container {
                background-color: rgba(255, 255, 255, 0.2);
                /* Adjust the alpha value as needed */
                border-top-right-radius: 20px;
                border-bottom-right-radius: 20px;
                width: 720px;
                text-align: center;
                /* Ensure the border is properly defined */
            }

            .astronaut1 {
                position: fixed;
                top: 13%;
                left: 26%;
                width: 270px;
                z-index: 10;
                pointer-events: none;
                animation: float1 6s ease-in-out infinite;
            }

            @keyframes float1 {
                0% {
                    transform: translateY(0) scaleX(1);
                    /* Starting position */
                }

                50% {
                    transform: translateY(20px) scaleX(1);
                    /* Float up */
                }

                100% {
                    transform: translateY(0) scaleX(1);
                    /* Return to starting position */
                }
            }
        </style>
    </head>

    <body>
        <img src="{{ asset('storage/images/astronaut3.png') }}" alt="Astronaut" class="astronaut1">

        <div class="login-container shadow-sm">
            <div class="left-container shadow-sm" style="display: flex; flex-direction: column; align-items: start; margin-bottom: 20px;">
                <h1 style="margin-bottom: 50px;"><strong>Hello new<br>Member!</strong></h1>
                <p style="margin-bottom: 50px; font-size:18px;">Welcome to <em>LunaBooks</em>, enter your personal details and start your journey with us. Unlock a world of stories and knowledge at your fingertips!</p>
                <p style="margin-bottom: 20px;">Already have an account?</p>
                <button type="submit" class="btn btn-light mb-4 w-100 fw-bold"
                    style="width: 800px; margin-bottom: 10px; color: #235872;">
                    Login
                </button>
            </div>

            <div class="right-container shadow-sm">
                <div class="d-flex justify-content-start align-items-start vh-100">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">

                                <h2 class="mb-5 mt-4 fw-bold" style="color: #235872; text-align: left;">MEMBERSHIP REGISTRATION FORM</h2>

                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form action="{{ route('register') }}" method="POST">
                                    @csrf

                                    <div class="row mb-1">
                                        <div class="col-md-4 text-start"><small>First name <span style="color: red;">*</span></small></div>
                                        <div class="col-md-4 text-start"><small>Last name <span style="color: red;">*</span></small></div>
                                        <div class="col-md-4 text-start"><small>Contact No. <span style="color: red;">*</span></small></div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4"><input type="text" id="fname" name="fname" value="{{ old('fname') }}" class="form-control" required></div>
                                        <div class="col-md-4"><input type="text" id="lname" name="lname" value="{{ old('lname') }}" class="form-control" required></div>
                                        <div class="col-md-4"><input type="text" id="contact_no" name="contact_no" value="{{ old('contact_no') }}" class="form-control" required minlength="11" maxlength="11"></div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-md-4 text-start"><small>Street <span style="color: red;">*</span></small></div>
                                        <div class="col-md-4 text-start"><small>City <span style="color: red;">*</span></small></div>
                                        <div class="col-md-4 text-start"><small>Region <span style="color: red;">*</span></small></div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4"><input type="text" id="street" name="street" value="{{ old('street') }}" class="form-control" required></div>
                                        <div class="col-md-4"><input type="text" id="city" name="city" value="{{ old('city') }}" class="form-control" required></div>
                                        <div class="col-md-4"><input type="text" id="region" name="region" value="{{ old('region') }}" class="form-control" required></div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-md-4 text-start"><small>Email <span style="color: red;">*</span></small></div>
                                        <div class="col-md-4 text-start"><small>Password <span style="color: red;">*</span></small></div>
                                        <div class="col-md-4 text-start"><small>Confirm Password <span style="color: red;">*</span></small></div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-4"><input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
                                        <div class="col-md-4"><input type="password" id="password" name="password" value="{{ old('password') }}" class="form-control" required></div>
                                        <div class="col-md-4"><input type="password" id="password_confirmation" value="{{ old('password_confirmation') }}" name="password_confirmation" class="form-control" required></div>
                                    </div>

                                    <button type="submit" class="btn w-100" style="background-color: #235872; color: white;">
                                        Register
                                    </button>

                                </form>
                                <div class="row mb-3 mt-3">
                                    <a href="{{ route('login') }}" style="color: black;">Already have an account? | Login</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!--<div class="right-container shadow-sm">
                <div class="d-flex justify-content-center align-items-center vh-100">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <h2 class="text-center mb-4">Register</h2>

                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form action="{{ route('register') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">Register</button>
                                </form>

                                <div class="text-center mt-3">
                                    <a href="{{ route('login') }}">Already have an account? Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->

        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
</x-layout>