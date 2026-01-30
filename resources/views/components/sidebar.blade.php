<div class="w-64 bg-gray-800 text-white min-h-screen p-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-200" />
            <span class="ml-3 text-lg font-semibold">{{ config('app.name', 'Laravel') }}</span>
        </a>
    </div>

    <nav>
        <ul>
            @if(Auth::user()->role === 'admin')
                <li class="mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-md hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : '' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('admin.departments.index') }}" class="flex items-center p-2 rounded-md hover:bg-gray-700 {{ request()->routeIs('admin.departments.*') ? 'bg-gray-900' : '' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m5-4h1m-1 4h1m-1-4h1m-1-4h1"></path></svg>
                        <span>Departments</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 rounded-md hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-900' : '' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.141-1.282-.4-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.141-1.282.4-1.857m0 0a5.002 5.002 0 019.2 0M12 14a4 4 0 110-8 4 4 0 010 8z"></path></svg>
                        <span>Users</span>
                    </a>
                </li>
            @elseif(Auth::user()->role === 'manager')
                <li class="mb-2">
                    <a href="{{ route('manager.dashboard') }}" class="flex items-center p-2 rounded-md hover:bg-gray-700 {{ request()->routeIs('manager.dashboard') ? 'bg-gray-900' : '' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('manager.tasks.index') }}" class="flex items-center p-2 rounded-md hover:bg-gray-700 {{ request()->routeIs('manager.tasks.*') ? 'bg-gray-900' : '' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span>Tasks</span>
                    </a>
                </li>
            @elseif(Auth::user()->role === 'staff')
                <li class="mb-2">
                    <a href="{{ route('staff.dashboard') }}" class="flex items-center p-2 rounded-md hover:bg-gray-700 {{ request()->routeIs('staff.dashboard') ? 'bg-gray-900' : '' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>
