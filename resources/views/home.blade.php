<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 text-center">
            <a href="/" class="inline-block">
                <img src="{{ asset('images/TaskFlow.png') }}" alt="Logo" class="w-24 h-24 mx-auto" />
            </a>
            <h1 class="text-4xl font-extrabold text-gray-900">
                Welcome to TaskFlow
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                The best way to manage your tasks and collaborate with your team.
            </p>
            <div class="mt-8 space-x-4">
                <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium text-lg hover:bg-indigo-700 transition duration-150">
                    Login
                </a>
                <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 px-6 py-3 rounded-lg font-medium text-lg hover:bg-gray-100 transition duration-150 border border-indigo-600">
                    Register
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
