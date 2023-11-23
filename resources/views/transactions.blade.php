<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.message')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2>Cash Deposit</h2>
                    <div class="details p-3">
                        <form action="{{ route('deposit') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="amount">Amount:</label>
                                <input type="string" class="form-control" min='0' id="amount" name="amount" placeholder="Enter amount" required>
                                @error('amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                <h2>Cash Withdrawal</h2>
                    <div class="details p-3">
                        <form action="{{ route('withdraw') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="amount">Amount:</label>
                                <input type="string" class="form-control" min='0' id="amount" name="amount" placeholder="Enter amount" required>
                                @error('amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                <h2>Cash Transfer</h2>
                    <div class="details p-3">
                        <form action="{{ route('transfer') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="amount">Email:</label>
                                <input type="email" class="form-control"name="email" placeholder="Enter email of the user to transfer" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mt-3">
                                <label for="amount">Amount:</label>
                                <input type="string" class="form-control" min='0' id="amount" name="amount" placeholder="Enter amount" required>
                                @error('amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>