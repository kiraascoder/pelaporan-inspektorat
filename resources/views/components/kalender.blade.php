<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Kalender</h3>

    <!-- Header Kalender -->
    <div class="flex justify-between items-center mb-4">
        <button onclick="changeMonth(-1)" class="text-gray-600 hover:text-gray-900">&lt;</button>
        <div id="calendar-header" class="text-sm font-medium text-gray-800"></div>
        <button onclick="changeMonth(1)" class="text-gray-600 hover:text-gray-900">&gt;</button>
    </div>

    <!-- Hari -->
    <div class="grid grid-cols-7 text-xs text-center text-gray-500 font-semibold mb-2">
        <div>Min</div>
        <div>Sen</div>
        <div>Sel</div>
        <div>Rab</div>
        <div>Kam</div>
        <div>Jum</div>
        <div>Sab</div>
    </div>

    <!-- Tanggal -->
    <div id="calendar-body" class="grid grid-cols-7 text-sm text-center gap-y-2 text-gray-800">
        <!-- Diisi oleh JS -->
    </div>
</div>

<script>
    let currentDate = new Date();

    function renderCalendar(date) {
        const monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDate = new Date(year, month + 1, 0).getDate();
        const startDay = firstDay.getDay(); // 0 = Minggu, 1 = Senin, dst

        document.getElementById("calendar-header").textContent = `${monthNames[month]} ${year}`;

        const calendarBody = document.getElementById("calendar-body");
        calendarBody.innerHTML = "";

        // Kosong sebelum tanggal 1
        for (let i = 0; i < startDay; i++) {
            const emptyCell = document.createElement("div");
            calendarBody.appendChild(emptyCell);
        }

        // Isi tanggal
        for (let i = 1; i <= lastDate; i++) {
            const dateCell = document.createElement("div");
            dateCell.textContent = i;

            // Highlight hari ini
            if (
                i === new Date().getDate() &&
                month === new Date().getMonth() &&
                year === new Date().getFullYear()
            ) {
                dateCell.classList.add("bg-blue-100", "text-blue-700", "rounded-full", "font-semibold");
            }

            calendarBody.appendChild(dateCell);
        }
    }

    function changeMonth(step) {
        currentDate.setMonth(currentDate.getMonth() + step);
        renderCalendar(currentDate);
    }

    // Render awal
    document.addEventListener("DOMContentLoaded", () => {
        renderCalendar(currentDate);
    });
</script>
