const categoryColors = {
    "Marketing": "#F27532",
    "Software": "#FEB503",
    "Community": "#635FEC",
    "Infrastructure": "#F23252",
    "Ecosystem": "#00D577",
    "Bounties": "#CA9B6F",
    "Liquidity": "#00A9FE",
    "Others": "#FF7CDA",
    "Network Security": "#F1638D",
    "Uncategorized": "#7C95AD"
};

const expectedCategories = Object.keys(categoryColors);

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

        for (let category in data[date]) {
            if (!adjustedData[adjustedDate][category]) {
                adjustedData[adjustedDate][category] = 0;
            }
            adjustedData[adjustedDate][category] += data[date][category];
        }
    }

    for (let date in adjustedData) {
        for (let category of expectedCategories) {
            if (!adjustedData[date][category]) {
                adjustedData[date][category] = 0;
            }
        }
    }

    return adjustedData;
}


$(document).ready(function() {
let unit = $('#toggleUnit').val() || 'month';
let data = adjustAggregation(rawData, unit); 
let labels = Object.keys(data);
let datasets = [];

for (let category of expectedCategories) {
    let datasetData = labels.map(label => data[label][category] || 0);
    
    datasets.push({
        label: category,
        data: datasetData,
        backgroundColor: categoryColors[category] || "rgba(128, 128, 128, 0.2)",
        borderColor: categoryColors[category] || "rgba(128, 128, 128, 1)",
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

    let labels = [];
    let datasetsData = {};

    for (let date in aggregatedData) {
        labels.push(date);
        for (let category in aggregatedData[date]) {
            if (!datasetsData[category]) {
                datasetsData[category] = [];
            }
            datasetsData[category].push(aggregatedData[date][category]);
        }
    }

    let datasets = [];
    for (let category in datasetsData) {
        datasets.push({
            label: category,
            data: datasetsData[category],
            backgroundColor: categoryColors[category] || "rgba(128, 128, 128, 0.2)",
            borderColor: categoryColors[category] || "rgba(128, 128, 128, 1)",
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

    // Initialize DataTables on the table element
    $('#dataTable').DataTable({
        dom: 'lBrtip',
        buttons: [
            'csv', 'excel'
        ],
        order: [[0, 'desc']]
    });
});
