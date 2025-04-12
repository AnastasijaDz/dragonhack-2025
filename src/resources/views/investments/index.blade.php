@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">My Investments</h1>

    @if($investments->isEmpty())
        <p class="text-gray-600">You don't have any investments yet.</p>
    @else
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Project Name</th>
                    <th class="border px-4 py-2">Tokens Count</th>
                    <th class="border px-4 py-2">Separate Token Price</th>
                    <th class="border px-4 py-2">Investment Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($investments as $investment)
                    <tr>
                        <td class="border px-4 py-2">{{ $investment->id }}</td>
                        <td class="border px-4 py-2">
                            {{ $investment->project->name ?? '—' }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ $investment->tokens->count() }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ $investment->project->price }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ $investment->created_at->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
