@extends('layouts.app')

@section('title', 'Завантаження дисциплін з Excel-файлу')

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col gap-4 mx-auto font-gotham-pro p-4">
        <div class="text-center text-xl sm:text-2xl lg:text-3xl p-4 w-full">
            Завантаження дисциплін з Excel-файлу
        </div>
        @if($errors->any())
            <div class="bg-red-300 rounded-lg p-4 my-2 w-10/12 sm:w-9/12 md:w-8/12 lg:w-1/2 mx-auto">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('upload.disciplines.upload') }}" enctype="multipart/form-data" method="post"
              class="text-lg flex flex-col gap-4 w-10/12 sm:w-9/12 md:w-8/12 lg:w-1/2 mx-auto">
            @csrf
            <div>
                <input type="file" required name="excel" id="excel"
                       accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
            </div>
            <div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 font-bold text-white text-lg
                    px-4 py-2">
                    Завантажити
                </button>
            </div>
        </form>
        <div class="p-4 border border-black rounded-xl font-bitter-italic hidden" id="message">

        </div>
    </main>

    @include('layouts.footer')
@endsection

@push('scripts')
    <script>
        @if(session()->has('message'))
        const messageBlock = document.querySelector('#message');
        messageBlock.classList.toggle('hidden', false);
        messageBlock.innerHTML = '{{ session('message') }}';
        @endif
    </script>
@endpush
