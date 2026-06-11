<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">カレンダー</h1>
                <a href="/events/create"
                   class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    予定を追加
                </a>
            </div>

            <div class="flex flex-wrap gap-4 text-sm mb-4">
                <span class="inline-flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full" style="background:#ef4444"></span> 重複</span>
                <span class="inline-flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full" style="background:#eab308"></span> 間隔が短い（60分未満）</span>
                <span class="inline-flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full" style="background:#3b82f6"></span> 通常</span>
            </div>

            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const isMobile = window.innerWidth < 640;
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: isMobile ? 'listMonth' : 'dayGridMonth',
                locale: 'ja',
                timeZone: 'Asia/Tokyo',
                height: 'auto',
                headerToolbar: isMobile
                    ? { left: 'prev,next', center: 'title', right: 'today' }
                    : { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listMonth' },
                buttonText: { today: '今日', month: '月', week: '週', list: 'リスト' },
                noEventsText: 'この期間に予定はありません',
                events: '/calendar/events'
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>
