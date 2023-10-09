// Function to generate colors based on track number
function getColorFromTrack(trackNumber) {
    const hue = trackNumber * 137.508; // Use golden angle to spread out the colors evenly
    return `hsl(${hue}, 50%, 75%)`; // Return as HSL format with fixed saturation and lightness for better consistency
}

function adjustAggregation(data, unit) {
    const adjustedData = {};

    for (let date in data) {
        let dateObj = new Date(date);
        let adjustedDate;

        switch (unit) {
            case 'day':
                adjustedDate = `${dateObj.getFullYear()}-${String(dateObj.getMonth() + 1).padStart(2, '0')}-${String(dateObj.getDate()).padStart(2, '0')}`;
                break;
            case 'week':
                let startOfWeek = new Date(dateObj);
                startOfWeek.setDate(dateObj.getDate() - dateObj.getDay());
                adjustedDate = `${startOfWeek.getFullYear()}-${String(startOfWeek.getMonth() + 1).padStart(2, '0')}-${String(startOfWeek.getDate()).padStart(2, '0')}`;
                break;
            case 'month':
                adjustedDate = `${dateObj.getFullYear()}-${String(dateObj.getMonth() + 1).padStart(2, '0')}-01`;
                break;
            case 'quarter':
                let quarterStartMonth = ["01", "04", "07", "10"][Math.floor(dateObj.getMonth() / 3)];
                adjustedDate = `${dateObj.getFullYear()}-${quarterStartMonth}-01`;
                break;
            case 'year':
                adjustedDate = `${dateObj.getFullYear()}-01-01`;
                break;
        }

        if (!adjustedData[adjustedDate]) {
            adjustedData[adjustedDate] = {};
        }

        for (let track in data[date]) {
            if (!adjustedData[adjustedDate][track]) {
                adjustedData[adjustedDate][track] = 0;
            }
            adjustedData[adjustedDate][track] += data[date][track];
        }
    }

    const expectedTracks = [...new Set([].concat(...Object.values(data).map(Object.keys)))];
    for (let date in adjustedData) {
        for (let track of expectedTracks) {
            if (!adjustedData[date][track]) {
                adjustedData[date][track] = 0;
            }
        }
    }

    return adjustedData;
}

$(document).ready(function() {
    let unit = $('#toggleUnit').val() || 'month';
    let data = adjustAggregation(rawData, unit); 

    const labels = Object.keys(data);
    const expectedTracks = [...new Set([].concat(...Object.values(rawData).map(Object.keys)))];

    let datasets = [];
    for (let track of expectedTracks) {
        let datasetData = labels.map(label => data[label][track] || 0);

        datasets.push({
            label: track,
            data: datasetData,
            backgroundColor: getColorFromTrack(Number(track)),
            borderColor: getColorFromTrack(Number(track)),
            borderWidth: 1
        });
    }

    let chartData = {
        labels: labels,
        datasets: datasets
    };

    let ctx = document.getElementById('myChart').getContext('2d');
    let myChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: unit
                    },
                    title: {
                        display: true,
                        text: 'Date'
                    },
                    stacked: true
                },
                y: {
                    title: {
                        display: true,
                        text: 'Amount in USD'
                    },
                    stacked: true
                }
            },
            plugins: {
                zoom: {
                    pan: {
                        enabled: false,
                        drag: false,
                        mode: 'xy'
                    },
                    zoom: {
                        enabled: true,
                        drag: true,
                        mode: 'xy'
                    }
                }
            }
        }
    });

    // Toggle unit (day, month, year)
    $('#toggleUnit').on('change', function() {
        unit = this.value;
        let aggregatedData = adjustAggregation(rawData, unit);

        let labels = Object.keys(aggregatedData);
        let datasetsData = {};

        for (let date in aggregatedData) {
            for (let track in aggregatedData[date]) {
                if (!datasetsData[track]) {
                    datasetsData[track] = [];
                }
                datasetsData[track].push(aggregatedData[date][track]);
            }
        }

        let datasets = [];
        for (let track of expectedTracks) {
            datasets.push({
                label: track,
                data: datasetsData[track],
                backgroundColor: getColorFromTrack(Number(track)),
                borderColor: getColorFromTrack(Number(track)),
                borderWidth: 1
            });
        }
        myChart.data.labels = labels;
        myChart.data.datasets = datasets;
        myChart.options.scales.x.time.unit = unit;
        myChart.update();
    });

    let divData = responseData;
    // Populate table with data
    let html = '';
    divData.sort((a, b) => new Date(b.date) - new Date(a.date)).forEach((item) => {
        html += '<tr>';
        html += '<td>' + item.date + '</td>';
        html += '<td>' + item.network + '</td>';
        html += '<td>' + item.refnum + '</td>';
        html += '<td>' + item.category + '</td>';
        html += '<td>' + item.track + '</td>';
        html += '<td> $' + parseFloat(item.amount_in_usd).toFixed(2) + '</td>';
        html += '<td>' + item.title + '</td>';
        html += '</tr>';
    });
    $('#tableBody').html(html);

    $('#dataTable').DataTable({
        dom: 'lBrtip',
        buttons: [
            'csv', 'excel'
        ],
        order: [[0, 'desc']]
    });
});
