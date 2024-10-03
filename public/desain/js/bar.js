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

            console.log("labels : ", hariInggris);
            const dataValues = hariInggris.map((day) => {
                const matchingData = responseData.filter((entry) => {
                    const entryDay = new Date(
                        entry.created_at
                    ).toLocaleDateString("en-US", { weekday: "long" });

                    return entryDay === day;
                });
                const totalQty = matchingData.reduce(
                    (acc, entry) => acc + entry.qty,
                    0
                );
                return totalQty;
            });

            const ctx = document.getElementById("myChart");

            if (ctx) {
                new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: hariInggris,
                        datasets: [
                            {
                                label: "Last 7 Days Sales",
                                data: dataValues,
                                backgroundColor: [
                                    "#083f6d",
                                    "#215284",
                                    "#34669b",
                                    "#477ab3",
                                    "#598fcc",
                                    "#6ca5e5",
                                    "#7ebbff",
                                ],
                                borderWidth: 1,
                            },
                        ],
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                            },
                        },
                    },
                });
            } else {
                console.error("Element with ID myChart not found.");
            }
        },
        error: function () {
            console.error("Failed to load data from controller.");
        },
    });
});
