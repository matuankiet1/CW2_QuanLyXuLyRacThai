@extends('layouts.app')

@section('content')

<div class="relative w-screen h-screen flex justify-center">
    <img src="/images/404-error.png" class="w-[70vw] h-[70vh] object-contain" alt="" />
    <button onclick="window.history.back()"
        class="cursor-pointer absolute bottom-35 left-1/2 -translate-x-1/2 flex items-center gap-2 px-4 bg-green-600 text-white py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 19-7-7 7-7" />
            <path d="M19 12H5" />
        </svg>
        Quay về
    </button>
</div>

@endsection