@extends('layouts.app')

@section('content')
    <div class="p-10">
        @if($projects->isEmpty())
            <p>No projects available.</p>
        @else
            <ul class="flex flex-col gap-10">
                @foreach($projects as $project)
                    <li class="flex flex-row gap-10 shadow-lg rounded-xl bg-white p-8">
                        <div class="flex-shrink-0 flex items-center">
                            <img class="w-[200px] h-auto rounded-lg" src="https://picsum.photos/200/200" alt="Project Image"
                                class="rounded-lg">
                        </div>
                        <div class="flex flex-col gap-4 flex-grow text-black">
                            <h2 class="text-2xl font-extrabold">{{ $project->name }}</h2>
                            <div class="flex flex-row p-3 rounded-md gap-2"><span class="font-bold">Landlord
                                    name:</span><span>{{ $project->landlord->name }}</span></div>
                            <div class="flex flex-row p-3 rounded-md gap-2">
                                <span class="font-bold">Description: </span><span>{{ $project->description }}</span></div>
                            <div class="flex flex-row p-3 rounded-md gap-2"><span class="font-bold">Broj dostupnih
                                    sadnic: </span><span>{{ $project->amount }}</span></div>
                            <div class="flex flex-row p-3 rounded-md  gap-2"><span class="font-bold">Cena jedne
                                    sadnice: </span><span>{{ $project->price }}</span></div>
                        </div>
                        <div class="flex flex-col flex-shrink-0 justify-evenly gap-4 w-[200px]">
                            <button class="py-3 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold">Calculate</button>
                            <button class="py-3 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold">Invest</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection