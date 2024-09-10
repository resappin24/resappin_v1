function getDaysWeekdays() {
    const weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const DaysWeekdays = [];

    for (let i = 6; i >= 0; i--) {
        const currentDate = new Date();
        currentDate.setDate(currentDate.getDate() - i);
        const dayIndex = currentDate.getDay();
        const formattedDate = currentDate.toLocaleDateString('id-ID', { weekday: 'long' });
        DaysWeekdays.push(formattedDate);
    }

    return DaysWeekdays;
}

document.addEventListener('DOMContentLoaded', function () {
    $.ajax({
        url: '/get_transaksi',
        method: 'GET',
        dataType: 'json',
        success: function (responseData) {
            console.log(responseData);

            const labels = getDaysWeekdays();
            const dataValues = getDaysWeekdays().map(day => {
                const matchingData = responseData.filter(entry => {
                    const entryDay = new Date(entry.created_at).toLocaleDateString('id-ID', { weekday: 'long' });
                    return entryDay === day;
                });

                const totalSubtotal = matchingData.reduce((acc, entry) => acc + (entry.satuan - entry.modal) * entry.qty, 0);

                return totalSubtotal;
            });

            console.log(dataValues)

            const ctx = document.getElementById('myLineChart');
            var data_colors = 'rgb('+(Math.floor(Math.random() *150)+75)+','+(Math.floor(Math.random() *200)+45)+','+(Math.floor(Math.random() *200)+35)+','+(Math.floor(Math.random() *150)+55)+','+(Math.floor(Math.random() *150)+60)+','+(Math.floor(Math.random() *150)+70)+')';

            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Penjualan 7 hari terakhir',
                            data: dataValues,
                             backgroundColor: ['#7FFFD4', '#FFE4C4', '#5F9EA0', '#6495ED', '#BDB76B', '#9932CC', '#90EE90'],
                            //backgroundColor: data_colors,
                        //    hoverBackgroundColor: ['red', 'blue', 'yellow', 'purple', 'green', 'pink', 'brown'],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            } else {
                console.error('Elemen dengan ID myChart tidak ditemukan.');
            }
        },
        error: function () {
            console.error('Gagal memuat data dari controller.');
        }
    });
});
