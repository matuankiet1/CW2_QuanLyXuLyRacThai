<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>

  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('asset/css/style-dashboardcard.css') }}">
</head>

<body class="bg-gray-100 font-[Rubik]">
  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg hidden md:block">
      <div class="p-4 border-b flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
          <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo" class="h-8">
          <span class="font-bold text-xl text-gray-700">Admin</span>
        </a>
      </div>
      <nav class="px-4 py-6">
        <ul class="space-y-2">
          <li>
            <a href="{{ route('admin-dashboard') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-gauge"></i>
              <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="{{ route('user.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-user"></i>
              <span>User</span>
            </a>
          </li>
          <li>
            <a href="{{ route('product.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-user"></i>
              <span>Product</span>
            </a>
          </li>
          <li>
            <a href="{{ route('blogs.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-user"></i>
              <span>Blogs</span>
            </a>
          </li>
          <li>
            <a href="{{ route('categories.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-user"></i>
              <span>Categories</span>
            </a>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">

      <!-- Header -->
      <header class="bg-white shadow p-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
          <button class="md:hidden text-2xl" id="mobile-menu-button">
            <i class="fa-solid fa-bars"></i>
          </button>
          <form method="POST" action="{{ route('dangxuat') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 p-2 rounded hover:bg-gray-200">
              <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
          </form>
        </div>
        <div class="flex items-center gap-6">
          
          <div class="relative">
            <img src="{{ asset('assets/image/user.png') }}" class="w-10 h-10 rounded-full cursor-pointer" />
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-6 bg-gray-50">
        @yield('content')
      </main>

    </div>
  </div>

  <!-- JS (optional) -->
  <script>
    // Toggle sidebar (optional)
    document.getElementById('mobile-menu-button')?.addEventListener('click', () => {
      const sidebar = document.querySelector('aside');
      sidebar?.classList.toggle('hidden');
    });
  </script>
  @stack('scripts')
</body>

</html>