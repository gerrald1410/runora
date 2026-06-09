<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Runora')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <div class="w-20 bg-[#C81010] flex flex-col items-center py-6 h-screen justify-between flex-shrink-0 z-10 shadow-xl">
        
        <div class="flex flex-col items-center w-full gap-6">
            <a href="#" class="bg-white w-12 h-12 rounded-full flex items-center justify-center mb-4 shadow-md hover:scale-105 transition-all">
                <i class="fas fa-user text-black text-xl"></i>
            </a>
            
            <div class="flex flex-col gap-4 items-center w-full">
                <div class="relative group flex items-center justify-center w-full">
                    <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center hover:bg-gray-100 transition-all duration-200 active:scale-90">
                        <i class="fas fa-th-large text-gray-700 text-lg"></i>
                    </a>
                    <span class="absolute left-16 ml-2 px-3 py-1 bg-black text-white text-xs rounded-md invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl z-50">Dashboard</span>
                </div>

                <div class="relative group flex items-center justify-center w-full">
                    <a href="{{ route('admin.produk.index') }}" class="w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center hover:bg-gray-100 transition-all duration-200 active:scale-90">
                        <i class="fas fa-shopping-bag text-gray-700 text-lg"></i>
                    </a>
                    <span class="absolute left-16 ml-2 px-3 py-1 bg-black text-white text-xs rounded-md invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl z-50">Data Produk</span>
                </div>

                <div class="relative group flex items-center justify-center w-full">
                    <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center hover:bg-gray-100 transition-all duration-200 active:scale-90">
                        <i class="fas fa-shipping-fast text-gray-700 text-lg"></i>
                    </a>
                    <span class="absolute left-16 ml-2 px-3 py-1 bg-black text-white text-xs rounded-md invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl z-50">Distribusi</span>
                </div>

                <div class="relative group flex items-center justify-center w-full">
                    <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center hover:bg-gray-100 transition-all duration-200 active:scale-90">
                        <i class="fas fa-cog text-gray-700 text-lg"></i>
                    </a>
                    <span class="absolute left-16 ml-2 px-3 py-1 bg-black text-white text-xs rounded-md invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl z-50">Pengaturan</span>
                </div>
            </div>
        </div>

        <div class="w-full flex justify-center pb-2">
            <div class="relative group flex items-center justify-center w-full">
                {{-- Mencegah link lompat, lalu menembak id form di atas --}}
                <a href="#" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center hover:bg-red-50 transition-all duration-200 active:scale-90">
                    <i class="fas fa-sign-out-alt text-red-600 text-lg"></i>
                </a>
                <span class="absolute left-16 ml-2 px-3 py-1 bg-black text-white text-xs rounded-md invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl z-50">Keluar</span>
            </div>
        </div>
    </div>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <main class="flex-grow p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>