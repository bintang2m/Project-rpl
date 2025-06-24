document.addEventListener('DOMContentLoaded', function() {
    // Load all dashboard data
    fetchDashboardData();
    
    // Initialize growth chart with two datasets
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    const growthChart = new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Usia Tanaman (hari)',
                    data: [],
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    borderColor: 'rgba(76, 175, 80, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Hari Menuju Panen',
                    data: [],
                    backgroundColor: 'rgba(255, 152, 0, 0.2)',
                    borderColor: 'rgba(255, 152, 0, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Usia (hari)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Hari Menuju Panen'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                }
            }
        }
    });

    // Update growth chart when range changes
    document.querySelector('#growthChart').closest('.card').querySelector('select').addEventListener('change', function() {
        const range = this.value;
        let param;
        if (range === '7 Hari Terakhir') param = '7days';
        else if (range === '30 Hari Terakhir') param = '30days';
        else param = '3months';

        fetch(`auth/growth_chart.php?range=${param}`)
            .then(response => response.json())
            .then(data => {
                growthChart.data.labels = data.labels;
                growthChart.data.datasets[0].data = data.ageData;
                growthChart.data.datasets[1].data = data.harvestData;
                growthChart.update();
            });
    });

    // Initialize with 7 days data
    fetch('auth/growth_chart.php?range=7days')
        .then(response => response.json())
        .then(data => {
            growthChart.data.labels = data.labels;
            growthChart.data.datasets[0].data = data.ageData;
            growthChart.data.datasets[1].data = data.harvestData;
            growthChart.update();
        });

    // Field selector functionality
    document.querySelector('#fieldSelect').addEventListener('change', function() {
        const fieldId = this.value;
        fetchDashboardData(fieldId);
        
        // Update charts with field filter
        const rangeSelect = document.querySelector('#growthChart').closest('.card').querySelector('select');
        const range = rangeSelect.value;
        let param;
        if (range === '7 Hari Terakhir') param = '7days';
        else if (range === '30 Hari Terakhir') param = '30days';
        else param = '3months';

        const url = `auth/growth_chart.php?range=${param}` + (fieldId !== 'all' ? `&lahan_id=${fieldId}` : '');
        fetch(url)
            .then(response => response.json())
            .then(data => {
                growthChart.data.labels = data.labels;
                growthChart.data.datasets[0].data = data.ageData;
                growthChart.data.datasets[1].data = data.harvestData;
                growthChart.update();
            });
    });
});

function fetchDashboardData(fieldId = 'all') {
    const url = 'auth/dashboard_data.php' + (fieldId !== 'all' ? `?lahan_id=${fieldId}` : '');
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            updateSummaryCards(data.summary);
            updateFieldStatus(data.fields);
            updateActivities(data.activities);
            updateWeatherForecast(data.forecast);
            
            // Update field selector options
            updateFieldSelector(data.fields);
        });
}

function updateFieldSelector(fields) {
    const selector = document.querySelector('#fieldSelect');
    selector.innerHTML = '<option value="all">Semua Lahan</option>';
    
    fields.forEach(field => {
        const option = document.createElement('option');
        option.value = field.id_lahan;
        option.textContent = field.nama_lahan;
        selector.appendChild(option);
    });
}

function updateSummaryCards(data) {
    document.querySelector('.sensor-value:nth-of-type(1)').textContent = `${data.temperature}°C`;
    document.querySelector('.sensor-value:nth-of-type(2)').textContent = `${data.humidity}%`;
    document.querySelector('.sensor-value:nth-of-type(3)').textContent = data.light;
    document.querySelector('.sensor-value:nth-of-type(4)').textContent = data.ph;
    
    // Update moisture card if exists
    const moistureCard = document.querySelector('.sensor-value:nth-of-type(5)');
    if (moistureCard) {
        moistureCard.textContent = `${data.moisture}%`;
    }
}

function updateFieldStatus(fields) {
    const container = document.querySelector('.list-group');
    container.innerHTML = '';

    fields.forEach(field => {
        const statusClass = getStatusClass(field.status);
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex justify-content-between align-items-center';
        item.innerHTML = `
            <div>
                <h6 class="mb-1">${field.nama_lahan}</h6>
                <small class="text-muted">${field.jenis_tanaman} - ${field.luas} Ha (${field.plant_count || 0} tanaman)</small>
            </div>
            <span class="badge ${statusClass}">${field.status}</span>
        `;
        container.appendChild(item);
    });
}

function getStatusClass(status) {
    switch(status) {
        case 'Aktif': return 'bg-success';
        case 'Maintenance': return 'bg-warning';
        case 'Nonaktif': return 'bg-danger';
        default: return 'bg-secondary';
    }
}

function updateActivities(activities) {
    const container = document.querySelector('.card-body[style*="max-height"]');
    container.innerHTML = '';

    activities.forEach(activity => {
        const iconClass = getActivityIcon(activity.type);
        const iconColor = getActivityColor(activity.type);
        const item = document.createElement('div');
        item.className = 'd-flex mb-3';
        item.innerHTML = `
            <div class="flex-shrink-0">
                <i class="${iconClass} ${iconColor} rounded-circle p-2"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-0">${activity.description}</h6>
                <small class="text-muted">${formatTime(activity.timestamp)}</small>
            </div>
        `;
        container.appendChild(item);
    });
}

function getActivityIcon(type) {
    switch(type) {
        case 'sensor': return 'fas fa-temperature-high';
        case 'planting': return 'fas fa-seedling';
        default: return 'fas fa-info-circle';
    }
}

function getActivityColor(type) {
    switch(type) {
        case 'sensor': return 'bg-primary text-white';
        case 'planting': return 'bg-success text-white';
        default: return 'bg-info text-white';
    }
}

function formatTime(timestamp) {
    const now = new Date();
    const activityTime = new Date(timestamp);
    const diff = Math.floor((now - activityTime) / 1000); // difference in seconds
    
    if (diff < 60) return 'Baru saja';
    if (diff < 3600) return `${Math.floor(diff/60)} menit yang lalu`;
    if (diff < 86400) return `${Math.floor(diff/3600)} jam yang lalu`;
    return `${Math.floor(diff/86400)} hari yang lalu`;
}

function updateWeatherForecast(forecast) {
    const container = document.querySelector('.card-body .row.text-center');
    container.innerHTML = '';

    forecast.forEach((day, index) => {
        const dayNames = ['Hari Ini', 'Besok', 'Lusa'];
        const iconClass = getWeatherIcon(day.condition);
        const col = document.createElement('div');
        col.className = 'col';
        col.innerHTML = `
            <h6>${dayNames[index] || formatDate(day.date)}</h6>
            <i class="${iconClass} fa-2x"></i>
            <div>${day.temp}°C / ${Math.round(day.temp - 5)}°C</div>
            <small class="text-muted">${day.condition}</small>
        `;
        container.appendChild(col);
    });
}

function getWeatherIcon(condition) {
    if (condition.includes('Hujan')) return 'fas fa-cloud-rain text-primary';
    if (condition.includes('Berawan')) return 'fas fa-cloud-sun text-secondary';
    return 'fas fa-sun text-warning';
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', { weekday: 'long' });
}