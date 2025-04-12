@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-8 w-full items-center">
        <h1 class="text-3xl font-bold my-10">My Investments</h1>

        @if($investments->isEmpty())
            <p class="text-gray-600">You don't have any investments yet.</p>
        @else
            @foreach ($investments as $investment)
                <div class="flex flex-col shadow-lg w-[80%] rounded-xl overflow-hidden">
                    <div class="bg-green-800 font-bold p-6 text-white text-2xl flex flex-row gap-3"><img src="/svgs/white-crypto-exchange.svg">{{ $investment->project->name ?? '—' }}</div>
                    <div class="flex flex-row p-6 bg-white justify-between">
                        <div class="flex flex-col justify-between">
                            <div><span class="font-bold text-black">Investment ID: </span><span>{{ $investment->id }}</span></div>
                            <div><span class="font-bold text-black">Tokens Count:
                                </span><span>{{ $investment->tokens->count() }}</span></div>
                            <div><span class="font-bold text-black">Separate Token Price:
                                </span><span>{{ $investment->project->price }}</span></div>
                            <div><span class="font-bold text-black">Investment Date:
                                </span><span>{{ $investment->created_at->format('Y-m-d H:i') }}</span></div>
                        </div>
                        <div class="flex flex-col justify-between gap-8">
                            <button class="inline-flex p-3 hover:underline font-bold gap-2 text-black">
                                Invest More
                                <div><img src="/svgs/moneys.svg"></div>
                            </button>
                            <button class="inline-flex p-3 hover:underline font-bold gap-2 text-black">
                                List Tokens<div><img src="/svgs/paper.svg"></div>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection