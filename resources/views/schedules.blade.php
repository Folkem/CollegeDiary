@extends('layouts.app')

@section('title', 'Розклади')

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col md:flex-row mx-auto">
        <div class="font-museo-cyrl md:w-5/12 bg-blue-500 flex flex-col text-base sm:text-lg
            md:text-xl lg:text-3xl">
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white">
                Розклади
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white bg-white text-blue-900 cursor-pointer"
                 data-menu-button="calls">
                Розклад дзвінків
            </div>
            <hr>
            <div class="text-white px-8 py-4 md:border-solid border-b-2 border-white cursor-pointer"
                 data-menu-button="lessons">
                Розклади занять
            </div>
        </div>
        <div class="bg-blue-100 md:w-7/12">
            <div data-menu-section="calls" class="px-12 py-6 flex w-full text-center">
                @if($callScheduleItems->count() === 0)
                    <div class="font-gotham-pro-bold m-auto">
                        Розкладу дзвінків ще нема.
                    </div>
                @else
                    <div class="font-gotham-pro flex flex-col gap-2 mx-auto text-lg md:text-xl lg:text-2xl xl:text-4xl">
                        <div class="flex flex-row font-bold gap-3 xl:gap-6">
                            <div class="w-2/12 min-w-min">
                                №
                            </div>
                            <div class="w-5/12 min-w-min">
                                Початок
                            </div>
                            <div class="w-5/12 min-w-min">
                                Кінець
                            </div>
                        </div>
                        @foreach($callScheduleItems as $csi)
                            <div class="flex flex-row gap-3 xl:gap-6">
                                <div class="w-2/12">{{ $csi->id }}</div>
                                <div class="w-5/12">{{ $csi->starting_at->format('H:i') }}</div>
                                <div class="w-5/12">{{ $csi->ending_at->format('H:i') }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div data-menu-section="lessons" class="px-12 py-6 hidden w-full">
                lessons
            </div>
        </div>
    </main>

    @include('layouts.footer')
@endsection

@push('scripts')
    <script>
        const buttons = document.querySelectorAll('[data-menu-button]');
        const sections = document.querySelectorAll('[data-menu-section]');
        buttons.forEach((button, key) => {
            button.addEventListener('click', () => {
                buttons.forEach((button, key) => {
                    button.classList.toggle('bg-white', false);
                    button.classList.toggle('text-blue-900', false);
                    sections.item(key).classList.toggle('hidden', true);
                });
                button.classList.toggle('bg-white', true);
                button.classList.toggle('text-blue-900', true);
                sections.item(key).classList.toggle('hidden', false);
            });
        });
    </script>
@endpush
