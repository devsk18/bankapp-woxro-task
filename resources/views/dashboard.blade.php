<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2>Account Informations</h2>
                    <div class="details p-3">
                        <table>
                            <tr>
                                <td>Name</td>
                                <td width="10%">:</td>
                                <td>{{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td width="10%">:</td>
                                <td>{{ Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <td>Balance</td>
                                <td width="10%">:</td>
                                <td>{{ number_format(Auth::user()->account_balance, 2) }} Rs</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2>Account Statements</h2>
                    <div class="details p-3">
                        <table class="table">
                            <thead>
                                <th>Transation ID</th>
                                <th>Transation From</th>
                                <th>Transation To</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Time</th>
                            </thead>
                            <tbody>
                                @if($transactions)
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->from }}</td>
                                        <td>{{ $transaction->to }}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>{{ $transaction->type }}</td>
                                        <td>{{ $transaction->status }}</td>
                                        <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
