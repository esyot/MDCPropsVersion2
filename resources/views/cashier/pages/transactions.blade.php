@extends('cashier.layouts.header')
@section('content')
<nav class="flex items-center justify-between p-2 bg-gray-300">
    <h1 class="text-2xl font-medium">
        {{ $page_title }}
    </h1>
    <div class="relative inline-block text-left">
        <button id="optionsIcon" class="px-2 hover:opacity-50" title="Options">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <div id="optionsIconDropdown"
            class="absolute right-0 z-10 mt-2 w-48 bg-white border rounded-md shadow-lg hidden">
            <ul class="" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t">
                    <a href="#">Export as PDF</a>
                </li>
                <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <a href="#">Export as XLS</a>
                </li>
                <li class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b">
                    <a href="#">Export as Text</a>
                </li>
            </ul>

        </div>

        <script>

            const button = document.getElementById('optionsIcon');
            const dropdown = document.getElementById('optionsIconDropdown');

            button.addEventListener('click', () => {
                dropdown.classList.toggle('hidden');
            });

            window.addEventListener('click', (event) => {
                if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });

        </script>

</nav>

<section class="m-2 bg-gray-100 rounded">
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 text-left">Item Name</th>
                <th class="p-2 text-left">Rent Date</th>
                <th class="p-2 text-left">Return Date</th>
                <th class="p-2 text-left">Rentee</th>
                <th class="p-2 text-left">Transaction Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr class="hover:bg-gray-200 border-b">
                    <td class="p-2">
                        {{$transaction->item->name}}
                    </td>
                    <td class="p-2">
                        {{ (new DateTime($transaction->rent_date))->format('F j, Y') }} at
                        {{ (new DateTime($transaction->rent_time))->format('g:i A') }}
                    </td>
                    <td class="p-2">
                        {{ (new DateTime($transaction->rent_return))->format('F j, Y') }} at
                        {{ (new DateTime($transaction->rent_return_time))->format('g:i A') }}
                    </td>
                    <td class="p-2">
                        {{$transaction->transaction->rentee->first_name}}
                        {{$transaction->transaction->rentee->middle_name[0]}}.
                        {{$transaction->transaction->rentee->last_name}}
                    </td>
                    <td class="p-2">
                        {{ (new DateTime($transaction->created_at))->format('F j, Y \a\t g:i A') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection