function getDaysWeekdays() {
    const days = [];
    const today = new Date();
    const dayNames = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
    ];

    for (let i = 6; i >= 0; i--) {
        const day = new Date(today);
        day.setDate(today.getDate() - i);
        days.push(dayNames[day.getDay()]);
    }

    return days;
}
function cekHariIndonesia(arrayHari) {
    const hariValid = [
        "Minggu",
        "Senin",
        "Selasa",
        "Rabu",
        "Kamis",
        "Jumat",
        "Sabtu",
    ];
    return arrayHari.every((hari) => hariValid.includes(hari));
}

document.addEventListener("DOMContentLoaded", function () {
    $.ajax({
        url: "/get_transaksi",
        method: "GET",
        dataType: "json",
        success: function (responseData) {
            console.log(responseData);

            const labels = getDaysWeekdays();
            const translateHari = {
                Minggu: "Sunday",
                Senin: "Monday",
                Selasa: "Tuesday",
                Rabu: "Wednesday",
                Kamis: "Thursday",
                Jumat: "Friday",
                Sabtu: "Saturday",
            };

            let hariInggris = [];
            if (cekHariIndonesia(labels))
                hariInggris = labels.map((hari) => translateHari[hari]);
            else hariInggris = labels;
            const dataValues = hariInggris.map((day) => {
                const matchingData = responseData.filter((entry) => {
                    const entryDay = new Date(
                        entry.created_at
                    ).toLocaleDateString("en-US", { weekday: "long" });
                    return entryDay === day;
                });

                const totalSubtotal = matchingData.reduce(
                    (acc, entry) =>
                        acc + (entry.satuan - entry.modal) * entry.qty,
                    0
                );

                return totalSubtotal;
            });

            console.log(dataValues);

            const ctx = document.getElementById("myPieChart");
            var data_colors =
                "rgb(" +
                (Math.floor(Math.random() * 150) + 75) +
                "," +
                (Math.floor(Math.random() * 200) + 45) +
                "," +
                (Math.floor(Math.random() * 200) + 35) +
                "," +
                (Math.floor(Math.random() * 150) + 55) +
                "," +
                (Math.floor(Math.random() * 150) + 60) +
                "," +
                (Math.floor(Math.random() * 150) + 70) +
                ")";

            if (ctx) {
                new Chart(ctx, {
                    type: "pie",
                    data: {
                        labels: hariInggris,
                        datasets: [
                            {
                                label: "Sales in the Last 7 Days",
                                data: dataValues,
                                backgroundColor: [
                                    "#7FFFD4",
                                    "#FFE4C4",
                                    "#5F9EA0",
                                    "#6495ED",
                                    "#BDB76B",
                                    "#9932CC",
                                    "#90EE90",
                                ],
                                //backgroundColor: data_colors,
                                //    hoverBackgroundColor: ['red', 'blue', 'yellow', 'purple', 'green', 'pink', 'brown'],
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    },
                });
            } else {
                console.error("Element with ID myChart not found.");
            }
        },
        error: function () {
            console.error("Failed to load data from the controller.");
        },
    });
});
