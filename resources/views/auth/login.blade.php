<x-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <style>
            body {
                background: url("{{ asset('storage/images/loginbg.png') }}") no-repeat center center fixed;
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
                background: rgba(180, 177, 177, 0.2);
                backdrop-filter: blur(10px);
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                position: relative;
                z-index: 5;
            }

            /* Left and Right container adjustments */
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
                background: rgba(180, 177, 177, 0.2);
                border-top-left-radius: 20px;
                border-bottom-left-radius: 20px;
                backdrop-filter: blur(10px);
                text-align: center;
                border: black;
            }

            /* Right container styling */
            .right-container {
                background: linear-gradient(135deg, #1983b6, #235872);
                color: white;
                border-top-right-radius: 20px;
                border-bottom-right-radius: 20px;
                border: black;
                padding: 50px;
            }

            /* ✅ Astronaut positioned OUTSIDE login-container */
            .astronaut1 {
                position: fixed;
                top: 17%;
                left: 33%;
                width: 310px;
                z-index: 10;
                pointer-events: none;
                animation: float1 6s ease-in-out infinite;
            }

            .astronaut2 {
                position: fixed;
                top: 50%;
                left: 10%;
                width: 360px;
                z-index: 10;
                /* Ensures it's above everything */
                pointer-events: none;

                animation: float 6s ease-in-out infinite;
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


            @keyframes float {
                0% {
                    transform: translateY(0) scaleX(-1);
                    /* Starting position */
                }

                50% {
                    transform: translateY(-20px) scaleX(-1);
                    /* Float up */
                }

                100% {
                    transform: translateY(0) scaleX(-1);
                    /* Return to starting position */
                }
            }

            /* Modernized input fields */
            .right-container .form-control {
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
            }

            .right-container .form-control::placeholder {
                color: rgba(255, 255, 255, 0.7);
            }

            .right-container .form-control:focus {
                background: rgba(255, 255, 255, 0.3);
                color: white;
            }

            /* Modern button */
            .right-container .btn {
                background-color: #ffffff;
                color: #1e3c72;
                font-weight: bold;
                border-radius: 8px;
            }

            .right-container .btn:hover {
                background-color: #f0f0f0;
            }
        </style>
    </head>

    <body>
        <!-- 🚀 Astronaut is now OUTSIDE the login-container -->
        <img src="{{ asset('storage/images/astronaut1.png') }}" alt="Astronaut" class="astronaut1">
        <img src="{{ asset('storage/images/astronaut2.png') }}" alt="Astronaut" class="astronaut2">

        <div class="login-container shadow-sm">
            <!-- Left Side -->
            <div class="left-container" style="background-color: rgba(255, 255, 255, 0.9); border-top-left-radius: 20px; border-bottom-left-radius: 20px; text-align: center; padding: 30px;">
                <!-- Row 1 -->
                <div class="row">
                    <!-- Column 1 (Header) -->
                    <div class="col-12">
                        <h1 style="color: #235872; font-size: 74px; text-align: start; margin-left: -180px; margin-bottom:40px; margin-top:170px;">
                            Welcome <br> back <br> friend!
                        </h1>
                    </div>
                </div>


                <!-- Row 2 -->
                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6 text-start" style="margin-left: 220px;">
                        <p style="font-size: 24px; color: #555; margin-bottom: 20px; text-align: start;">
                            This is LunaBooks, to keep connected with us enter your account details.
                        </p>
                        <p style="font-size: 15px; color: #555; margin-bottom: 200px; text-align: start;">
                            <span><i>New here? Register now and be a member.</i></span>
                        </p>
                    </div>
                </div>
            </div>


            <!-- Right Side -->
            <div class="right-container shadow-sm">
                <div class="row w-100">
                    <h1 class="text-start mb-5">Sign In to LunaBooks</h1>
                </div>

                <div class="row w-100">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <div class="row w-100">
                    <form action="{{ route('login') }}" method="POST" class="w-100">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <div class="row mb-4" style="margin-left: 292px ">
                            <p><a href="{{ route('register') }}" class="text-white">Forgot password?</a></p>
                        </div>

                        <button type="submit" class="btn btn-light mb-4 w-100" style="width: 800px;">Login</button>
                    </form>
                </div>



                <div class="row">
                    <div class="text-center mt-2">
                        <p>Don't have an account? | <a href="{{ route('register') }}" class="text-white">Register here</a></p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
</x-layout>