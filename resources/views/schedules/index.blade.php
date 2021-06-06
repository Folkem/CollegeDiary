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
            <div data-menu-section="calls" class="px-12 py-6 flex w-full hidden text-center">
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
            <div data-menu-section="lessons" class="px-2 sm:px-12 py-6 w-full text-center">
                @if($groups->count() === 0)
                    <div class="font-gotham-pro-bold m-auto">
                        Жодного розкладу занять ще нема.
                    </div>
                @else
                    <div class="w-full flex flex-col gap-16 font-gotham-pro">
                        @foreach($groups as $group)
                            <div class="flex flex-col gap-2">
                                <div class="text-4xl">
                                    Група {{ $group->human_name }}
                                </div>
                                <div class="flex flex-col w-full text-center font-museo-cyrl p-4 gap-2">
                                    <div
                                        class="flex flex-row w-full font-bold border-solid border border-black rounded-t-xl py-2">
                                        <div class="w-3/12 sm:w-2/12">День</div>
                                        <div class="w-1/12">№</div>
                                        <div class="w-8/12 sm:w-9/12">Дисципліна</div>
                                    </div>
                                    @foreach($group->lessonSchedule as $weekDay => $daySchedule)
                                        <div class="flex flex-row pb-2 border-solid border-b border-black">
                                            <div class="w-3/12 sm:w-2/12 self-center">{{ __($weekDay) }}</div>
                                            <div class="w-9/12 sm:w-10/12 flex flex-col">
                                                @foreach($daySchedule as $number => $numberSchedule)
                                                    <div class="flex flex-row border-b border-solid border-black
                                                    @if(last($daySchedule) == $numberSchedule) border-none @endif">
                                                        <div class="w-1/10 self-center">
                                                            {{ $number }}
                                                        </div>
                                                        <div class="w-9/10">
                                                            @if(array_key_exists('постійно', $numberSchedule))
                                                                {{ $numberSchedule['постійно'] }}
                                                            @else
                                                                {!! $numberSchedule['чисельник'] ?? '<b>Відсутньо</b>' !!}
                                                                /
                                                                {!! $numberSchedule['знаменник'] ?? '<b>Відсутньо</b>' !!}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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
