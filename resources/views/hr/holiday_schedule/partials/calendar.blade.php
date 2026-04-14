@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <style>
        .calendar-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            padding: 5px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .calendar-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 8px 35px 8px 12px;
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .calendar-select:hover {
            border-color: #2c3e50;
        }

        .calendar-select:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.1);
        }

        .fc .fc-toolbar-title {
            font-size: 1.5em !important;
            text-transform: capitalize;
        }
    </style>
@endpush


<div id='calendar-container'>
    <div class="calendar-filters">
        <select id="select-month" class="calendar-select" style="width: 150px;"></select>
        <select id="select-year" class="calendar-select" style="width: 120px;"></select>
    </div>
    <div id='calendar'></div>
</div>


@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const monthSelect = document.getElementById('select-month');
    const yearSelect = document.getElementById('select-year');

    const initFilters = () => {
        const months = Array.from({length: 12}, (_, i) => ({
            val: String(i + 1).padStart(2, '0'),
            text: `Tháng ${i + 1}`
        }));

        months.forEach(m => monthSelect.add(new Option(m.text, m.val)));
        for (let i = 1950; i <= 2050; i++) {
            yearSelect.add(new Option(`Năm ${i}`, i));
        }

        const now = new Date();
        monthSelect.value = String(now.getMonth() + 1).padStart(2, '0');
        yearSelect.value = now.getFullYear();
    };

    const initCalendar = () => {
        window.calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'vi',
            selectable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            
            events: '/holiday-schedule/list',
            eventBackgroundColor: '#e74c3c',
            eventBorderColor: '#c0392b',

            dateClick: (info) => {
                
                try {
                    const clicked = info.date;
                    const clickedDateOnly = new Date(clicked.getFullYear(), clicked.getMonth(), clicked.getDate());
                    const events = window.calendar.getEvents();
                    let matched = null;

                    for (let i = 0; i < events.length; i++) {
                        const ev = events[i];
                        if (!ev.start) continue;
                        if (ev.id && String(ev.id).startsWith('bg-')) continue;

                        const s = ev.start;
                        const e = ev.end || ev.start;

                        const startDateOnly = new Date(s.getFullYear(), s.getMonth(), s.getDate());
                        const endDateOnly = new Date(e.getFullYear(), e.getMonth(), e.getDate());

                        if (clickedDateOnly >= startDateOnly && clickedDateOnly < endDateOnly) {
                            matched = ev;
                            break;
                        }
                    }

                    if (matched) {
                        document.dispatchEvent(new CustomEvent('holiday:event-click', { detail: {
                            id: matched.id,
                            start: matched.startStr,
                            end: matched.endStr,
                            extendedProps: matched.extendedProps
                        } }));
                    } else {
                        document.dispatchEvent(new CustomEvent('holiday:date-click', { detail: { dateStr: info.dateStr } }));
                    }
                } catch (e) {
                    console.error('dateClick handling error', e);
                    document.dispatchEvent(new CustomEvent('holiday:date-click', { detail: { dateStr: info.dateStr } }));
                }
            },

            eventClick: (info) => {
                document.dispatchEvent(new CustomEvent('holiday:event-click', { detail: {
                    id: info.event.id,
                    start: info.event.startStr,
                    end: info.event.endStr,
                    extendedProps: info.event.extendedProps
                } }));
            },

            datesSet: () => {
                const date = window.calendar.getDate();
                monthSelect.value = String(date.getMonth() + 1).padStart(2, '0');
                yearSelect.value = date.getFullYear();
            }
        });

        window.calendar.render();
    };

    const handleFilterChange = () => {
        window.calendar.gotoDate(`${yearSelect.value}-${monthSelect.value}-01`);
    };

    initFilters();
    initCalendar();

    monthSelect.addEventListener('change', handleFilterChange);
    yearSelect.addEventListener('change', handleFilterChange);
});
</script>
@endpush
