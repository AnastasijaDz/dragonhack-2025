@extends('layouts.app')

@section('content')
    <section class="relative h-[60vh] flex items-center justify-center bg-fixed bg-center bg-cover"
        style="background-image: url('/images/farm_landscape.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 text-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">About EcoStock</h1>
            <p class="text-xl md:text-2xl">Backing Farms, Building Futures.</p>
        </div>
    </section>

    <section id="team" class="py-16 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-green-700 mb-8">Meet Our Team</h2>
            <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-4">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <img src="/images/Anja.png" alt="Anja Kuzevska"
                        class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-semibold">Anja Kuzevska</h3>
                    <p class="text-gray-600">CEO</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <img src="/images/Anastasija.png" alt="Anastasija Djajkovska"
                        class="w-32 h-32 rounded-full mx-auto mb-4 object-cover" style="object-position: center -10px;">
                    <h3 class="text-xl font-semibold">Anastasija Djajkovska</h3>
                    <p class="text-gray-600">CTO</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <img src="/images/Jordan.png" alt="Jordan Lazov"
                        class="w-32 h-32 rounded-full mx-auto mb-4 object-cover" style="object-position: center -10px;">
                    <h3 class="text-xl font-semibold">Jordan Lazov</h3>
                    <p class="text-gray-600">Senior Software Engineer</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <img src="/images/Aleksa.jpeg" alt="Aleksa Sibinović"
                        class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-semibold">Aleksa Sibinović</h3>
                    <p class="text-gray-600">Project Manager</p>
                </div>
            </div>
        </div>
    </section>

    <section id="history" class="py-16 px-4 bg-gray-100">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-green-700 mb-6">Our History</h2>
            <p class="text-lg text-gray-700 leading-relaxed">
                Founded in 2025, EcoStock has grown from a small team of passionate investors and environmentalists to a
                trusted platform that connects local farmers with investors committed to making a positive impact. Our
                journey is driven by our dedication to sustainability and transparency in every investment.
            </p>
        </div>
    </section>

    <section id="mission" class="py-16 px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-green-700 mb-6">Our Mission</h2>
            <p class="text-lg text-gray-700 leading-relaxed">
                At EcoStock, our mission is to empower communities by providing sustainable investment opportunities in
                local agriculture and eco-friendly projects. We believe in using smart investments to build a greener, more
                secure future for everyone.
            </p>
        </div>
    </section>
@endsection