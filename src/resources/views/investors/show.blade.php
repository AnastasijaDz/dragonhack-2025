@extends('layouts.app')

@section('content')
    <div class="flex flex-col w-full items-center relative">
        <div class="w-full h-[250px] overflow-x-hidden [&::-webkit-scrollbar]:hidden pointer-events-none">
            <img src="/images/almonds.png" class="w-full">
        </div>
        <div class="w-[160px] h-[160px] mb-8 mt-[-80px]">
            <img src="/images/professional-investor-profile-picture.png" class="h-full w-full rounded-full">
        </div>
        <h1 class="text-2xl font-bold">{{ $investor->name }}'s Profile</h1>
        <h2 class="text-lg font-semibold text-green-800 mb-16">High Ranking Investor</h2>
        <div class="flex flex-col gap-2 shadow-lg p-8 rounded-2xl border-2 w-[30%]">
            @if($investor->user)
                <div class="flex justify-between">
                    <span class="font-bold">User Name: </span><span>{{ $investor->user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold">Email: </span><span>{{ $investor->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold">Phone: </span><span>{{ $investor->phone }}</span>
                </div>
            @else
                <p>No associated user found.</p>
            @endif
        </div>
    </div>
@endsection