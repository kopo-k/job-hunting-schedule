<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">カレンダー</h1>
            <a href="/events/create" class="px-3 py-1 bg-blue-600 text-white rounded">予定を追加</a>
        </div>
        <div class="flex gap-4 text-sm mb-3">
            <span><span class="inline-block w-3 h-3 rounded-full" style="background:#ef4444"></span> 重複</span>
            <span><span class="inline-block w-3 h-3 rounded-full" style="background:#eab308"></span> 間隔が短い</span>
            <span><span class="inline-block w-3 h-3 rounded-full" style="background:#3b82f6"></span> 通常</span>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div id="calendar"></div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ja',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: '/calendar/events'
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>
