@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col">
        <div class="flex flex-col gap-16 relative">
            <div class="w-full relative">
                <img src="/images/main-page-image.jpg" alt="Main Page Image">
                <div class="top-[40%] left-[10%] flex flex-col absolute text-white">
                    <h1 class="text-8xl font-bold">EcoStock</h1>
                    <p class="text-6xl">Backing Farms, Building Futures.</p>
                </div>
            </div>

            <div class="w-full relative flex flex-row justify-center gap-10">
                <div class="w-[20%]">
                    <img src="/images/farmer-cartoon.png" alt="Farmer Cartoon">
                </div>
                <div class="flex flex-col w-[60%] gap-10 items-start justify-center">
                    <h2 class="text-5xl text-green-700 font-extrabold text-left">Eco-friendly investment</h2>
                    <p class="text-black font-bold text-xl pl-2">Looking for a best place to put your money to work? We got
                        you
                        covered! By partnering up with
                        local farmers who are experienced in their field, you are presented with an opportunity to
                        support Eco-friendly and sustainable projects.</p>
                </div>
            </div>

            <div class="flex items-center justify-center">
                <div class="w-[95%] relative flex flex-row justify-center gap-10 bg-green-800 p-12 rounded-2xl">
                    <div class="flex flex-col w-[60%] gap-10 items-end justify-center text-white">
                        <h2 class="text-5xl font-extrabold text-right">Focus on investing, not paperwork.</h2>
                        <p class="font-bold text-xl text-right pr-2">As an investor, your focus is on making money by smart investing.
                            We take care of the rest. On our “investments” page you can find available options with
                            detailed information, including the contacts of the project owners. When you are ready, you
                            can create a profile and start investing by buying shares of the project. </p>
                    </div>
                    <div class="w-[20%]">
                        <img class="rounded-lg" src="/images/investor-apple.png" alt="Farmer Cartoon">
                    </div>
                </div>
            </div>

            <div class="w-full relative flex flex-row justify-center gap-10">
                <div class="w-[20%]">
                    <img src="/images/apple-with-a-lock.png" alt="Farmer Cartoon">
                </div>
                <div class="flex flex-col w-[60%] gap-10 items-start justify-center">
                    <h2 class="text-5xl text-green-700 font-extrabold text-left">Your investment is safe with us.</h2>
                    <div class="flex flex-col gap-4 pl-2">
                        <p class="text-black font-bold text-xl">Every investment is securely tracked through blockchain
                            technology.
                            Your ownership in a project is represented by digital tokens,
                            ensuring full transparency and security.
                        </p>
                        <p class="text-black font-bold text-xl">
                            These tokens can be seamlessly transferred to other investors at any time, no need for complicated paperwork or new contract
                            signings. It’s a flexible, secure, and efficient system designed to give you full control over your investment.
                    </p>    
                    </div>                                                      
                </div>
            </div>
            <div class="menu-open-overlay hidden opacity-0 transition-all duration-300 ease-in-out absolute w-full h-full top-0 bg-black bg-opacity-50"></div>
        </div>
    </div>
@endsection
