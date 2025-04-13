@extends('layouts.app')

@section('content')
    <style>
        /* Base styles for transitions */
        .hidden-section {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .visible-section {
            opacity: 1;
            transform: translateY(0px);
        }
        /* Existing animations */
        @keyframes whoopIn {
            0% { transform: scale(0); opacity: 0; }
            25% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes whoopOut {
            0% { transform: scale(1); opacity: 1; }
            25% { transform: scale(0.9); opacity: 0.7; }
            100% { transform: scale(0); opacity: 0; }
        }
        .animate-whoopIn { animation: whoopIn 0.5s ease-out forwards; }
        .animate-whoopOut { animation: whoopOut 0.3s ease-in forwards; }
        .success-message {
            margin-top: 1rem;
            color: green;
            font-size: 1.1rem;
            font-weight: bold;
        }
    </style>

    <div class="min-h-screen flex flex-col">
        <div class="flex flex-col gap-16 relative">
            <!-- Hero Section -->
            <section class="w-full relative hidden-section">
                <img src="/images/main-page-image.jpg" alt="Main Page Image">
                <div class="absolute top-[40%] left-[10%] text-white flex flex-col">
                    <h1 class="text-8xl font-bold">EcoStock</h1>
                    <p class="text-6xl">Backing Farms, Building Futures.</p>
                </div>
            </section>

            <!-- Investment Info Section -->
            <section class="w-full relative flex flex-row justify-center gap-10 hidden-section">
                <div class="w-[20%]">
                    <img src="/images/farmer-cartoon.png" alt="Farmer Cartoon">
                </div>
                <div class="flex flex-col w-[60%] gap-10 items-start justify-center">
                    <h2 class="text-5xl text-green-700 font-extrabold text-left">Eco-friendly investment</h2>
                    <p class="text-black font-bold text-xl pl-2">
                        Looking for the best place to put your money to work? We got you covered! By partnering with local
                        farmers, you have an opportunity to support eco-friendly and sustainable projects.
                    </p>
                </div>
            </section>

            <!-- Paperwork Section -->
            <section class="w-full relative flex flex-row justify-center gap-10 hidden-section">
                <div class="w-[95%] relative flex flex-row justify-center gap-10 bg-green-800 p-12 rounded-2xl">
                    <div class="flex flex-col w-[60%] gap-10 items-end justify-center text-white">
                        <h2 class="text-5xl font-extrabold text-right">Focus on investing, not paperwork.</h2>
                        <p class="font-bold text-xl text-right pr-2">
                            As an investor, your focus is on making money by smart investing. We take care of the rest.
                            On our “investments” page you’ll find available options, detailed project information, and
                            direct contacts with project owners. Create your profile and start investing by buying shares.
                        </p>
                    </div>
                    <div class="w-[20%]">
                        <img class="rounded-lg" src="/images/investor-apple.png" alt="Investor Image">
                    </div>
                </div>
            </section>

            <!-- Security Section -->
            <section class="w-full relative flex flex-row justify-center gap-10 hidden-section">
                <div class="w-[20%]">
                    <img src="/images/apple-with-a-lock.png" alt="Secure Investment">
                </div>
                <div class="flex flex-col w-[60%] gap-10 items-start justify-center">
                    <h2 class="text-5xl text-green-700 font-extrabold text-left">Your investment is safe with us.</h2>
                    <div class="flex flex-col gap-4 pl-2">
                        <p class="text-black font-bold text-xl">
                            Every investment is securely tracked through blockchain technology. Your ownership in a project is
                            represented by digital tokens, ensuring full transparency and security.
                        </p>
                        <p class="text-black font-bold text-xl">
                            Tokens can be seamlessly transferred to other investors at any time – no complicated paperwork.
                            Enjoy flexible, secure, and efficient investment control.
                        </p>
                    </div>
                </div>
            </section>

            <div class="menu-open-overlay hidden opacity-0 transition-all duration-300 ease-in-out absolute w-full h-full top-0 bg-black bg-opacity-50"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Intersection Observer to reveal sections on scroll
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible-section');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.2
            });

            document.querySelectorAll('.hidden-section').forEach(section => {
                observer.observe(section);
            });
        });
    </script>
@endsection
