// Supervision page JavaScript
(function() {
  document.addEventListener("DOMContentLoaded", function() {
    // Check if user is manager
    const auth = SeneBI.requireRole(["manager"], "Accès réservé aux managers");
    if (!auth) return;

    // Initialize supervision data
    initializeSupervision();
  });

  function initializeSupervision() {
    // Render the topbar navigation
    SeneBI.renderTopbar();
    
    // Simulate real-time data updates
    updateKPIs();
    updateFarmersDirectory();
    updateSystemStatus();
    
    // Set up periodic updates
    setInterval(updateKPIs, 5000); // Update every 5 seconds
    setInterval(updateFarmersDirectory, 15000); // Update every 15 seconds
    setInterval(updateSystemStatus, 30000); // Update every 30 seconds
  }

  function updateKPIs() {
    // Simulate active users
    const activeUsers = Math.floor(Math.random() * 20) + 5;
    document.getElementById('activeUsers').textContent = activeUsers;

    // Simulate daily activities
    const dailyActivities = Math.floor(Math.random() * 150) + 50;
    document.getElementById('dailyActivities').textContent = dailyActivities;

    // Simulate system alerts
    const systemAlerts = Math.floor(Math.random() * 3);
    document.getElementById('systemAlerts').textContent = systemAlerts;

    // Simulate performance score
    const performanceScore = Math.floor(Math.random() * 10) + 90;
    document.getElementById('performanceScore').textContent = performanceScore;
  }

  function updateFarmersDirectory() {
    // Utiliser les clients réels depuis la base de données
    const realClients = window.SeneBI?.activeClients || [];

    const farmers = realClients.map(client => ({
      name: client.name,
      location: client.location,
      stockStatus: "ok",
      stockLevel: "Actif",
      riskLevel: "Faible",
      riskClass: "risk-low",
      lastActivity: "Actif"
    }));

    const container = document.getElementById('farmersTableBody');
    if (container) {
      if (farmers.length === 0) {
        container.innerHTML = `
          <tr>
            <td colspan="6" style="text-align: center; padding: 48px 24px; color: #6b7280;">
              <p style="margin: 0; font-size: 15px; font-weight: 500;">Aucun agriculteur actif</p>
              <p style="margin: 8px 0 0 0; font-size: 13px; color: #9ca3af;">Les agriculteurs approuvés apparaîtront ici</p>
            </td>
          </tr>
        `;
      } else {
        container.innerHTML = farmers.map(farmer => `
          <tr>
            <td>
              <div class="farmer-name">${farmer.name}</div>
            </td>
            <td>
              <div class="farmer-location">${farmer.location}</div>
            </td>
            <td>
              <span class="stock-badge ${farmer.stockStatus}">${farmer.stockLevel}</span>
            </td>
            <td>
              <span class="risk-badge ${farmer.riskClass}">${farmer.riskLevel}</span>
            </td>
            <td>
              <div class="last-activity">${farmer.lastActivity}</div>
            </td>
            <td>
              <button class="details-btn" onclick="showFarmerDetails('${farmer.name}')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                Détails
              </button>
            </td>
          </tr>
        `).join('');
      }
    }
  }

  // Function to handle farmer details click
  window.showFarmerDetails = function(farmerName) {
    const farmerData = getFarmerData(farmerName);
    if (farmerData) {
      openFarmerModal(farmerData);
    }
  }

  // Function to get farmer data
  function getFarmerData(farmerName) {
    // Utiliser les clients réels depuis la base de données
    const realClients = window.SeneBI?.activeClients || [];
    const client = realClients.find(c => c.name === farmerName);

    if (!client) {
      return null;
    }

    // Générer des données simulées pour le client réel
    return {
      name: client.name,
      location: client.location,
      stockStatus: "ok",
      stockLevel: "Actif",
      stockPercentage: 75,
      lastActivity: "Actif",
      cultures: {
        riz: { real: 4500, forecast: 4700 },
        mais: { real: 4100, forecast: 4300 },
        coton: { real: 2500, forecast: 2700 }
      },
      stocks: {
        uree: 48,
        npk: 72,
        semenceRiz: 80,
        semenceMais: 78,
        semenceCoton: 82
      }
    };
  }

  // Function to open farmer modal
  function openFarmerModal(farmerData) {
    const modal = document.getElementById('farmerModal');
    const nameEl = document.getElementById('modalFarmerName');
    const locationEl = document.getElementById('modalFarmerLocation');
    const stockDateEl = document.getElementById('stockDate');
    const stockListEl = document.getElementById('stockList');

    // Update modal content
    nameEl.textContent = farmerData.name;
    locationEl.textContent = farmerData.location;
    
    // Generate a recent date for the analysis
    const today = new Date();
    const lastWeek = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
    const dateStr = lastWeek.toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
    stockDateEl.textContent = dateStr;

    // Update stock details list
    updateStockDetailsList(farmerData.stocks);

    // Show modal
    modal.removeAttribute('hidden');

    // Create charts after modal is visible
    setTimeout(() => {
      createStockChart(farmerData.stockPercentage);
      createPerformanceChart(farmerData.cultures);
    }, 100);
  }

  // Function to update stock details list
  function updateStockDetailsList(stocks) {
    const stockListEl = document.getElementById('stockList');
    if (!stockListEl) return;

    const stockItems = [
      { name: 'Urée', value: stocks.uree },
      { name: 'NPK', value: stocks.npk },
      { name: 'Semence Riz', value: stocks.semenceRiz },
      { name: 'Semence Maïs', value: stocks.semenceMais },
      { name: 'Semence Coton', value: stocks.semenceCoton }
    ];

    stockListEl.innerHTML = stockItems.map(item => {
      let statusClass = 'ok';
      if (item.value < 30) statusClass = 'critical';
      else if (item.value < 60) statusClass = 'warning';

      return `
        <div class="stock-item">
          <span class="stock-dot ${statusClass}"></span>
          <span class="stock-name">${item.name}</span>
          <span class="stock-percentage">${item.value}%</span>
        </div>
      `;
    }).join('');
  }

  // Function to close farmer modal
  window.closeFarmerModal = function() {
    const modal = document.getElementById('farmerModal');
    modal.setAttribute('hidden', '');
  }

  // Function to create stock gauge chart (doughnut half-circle)
  function createStockChart(stockPercentage) {
    const canvas = document.getElementById('stockChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    // Destroy existing chart if it exists
    if (window.stockChartInstance) {
      window.stockChartInstance.destroy();
    }

    // Determine colors based on stock percentage
    const mainColor = stockPercentage > 30 ? '#16a34a' : '#dc2626'; // Vert Émeraude or Rouge Corail
    const bgColor = stockPercentage > 30 ? 'rgba(22, 163, 74, 0.1)' : 'rgba(220, 38, 38, 0.1)';

    // Create doughnut chart (half-circle)
    window.stockChartInstance = new Chart(ctx, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [stockPercentage, 100 - stockPercentage],
          backgroundColor: [mainColor, '#e2e8f0'],
          borderWidth: 0,
          cutout: '70%'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        rotation: -90,
        circumference: 180,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            enabled: true,
            callbacks: {
              label: function(context) {
                return `Stock: ${context.parsed}%`;
              }
            }
          }
        }
      },
      plugins: [{
        beforeDraw: function(chart) {
          const width = chart.width;
          const height = chart.height;
          const ctx = chart.ctx;
          
          ctx.restore();
          ctx.font = 'bold 24px Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif';
          ctx.textBaseline = 'middle';
          ctx.textAlign = 'center';
          ctx.fillStyle = mainColor;
          ctx.fillText(`${stockPercentage}%`, width / 2, height * 0.7);
          ctx.save();
        }
      }]
    });
  }

  // Function to create performance bar chart for cultures
  function createPerformanceChart(cultures) {
    const canvas = document.getElementById('performanceChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    // Destroy existing chart if it exists
    if (window.performanceChartInstance) {
      window.performanceChartInstance.destroy();
    }

    // Prepare data for Riz, Maïs, Coton
    const labels = ['Riz', 'Maïs', 'Coton'];
    const realData = [cultures.riz.real, cultures.mais.real, cultures.coton.real];
    const forecastData = [cultures.riz.forecast, cultures.mais.forecast, cultures.coton.forecast];

    // Create bar chart
    window.performanceChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Récolte Réelle',
            data: realData,
            backgroundColor: '#059669',
            borderColor: '#059669',
            borderWidth: 1,
            borderRadius: 4
          },
          {
            label: 'Prévisions',
            data: forecastData,
            backgroundColor: 'rgba(5, 150, 105, 0.3)',
            borderColor: 'rgba(5, 150, 105, 0.5)',
            borderWidth: 1,
            borderRadius: 4
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              font: {
                family: 'Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif',
                size: 11
              },
              usePointStyle: true,
              padding: 15
            }
          },
          tooltip: {
            enabled: true,
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleFont: {
              family: 'Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif'
            },
            bodyFont: {
              family: 'Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif'
            },
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                label += new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' kg';
                return label;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Rendement (kg)',
              font: {
                family: 'Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif',
                size: 10
              },
              color: '#64748b'
            },
            grid: {
              color: 'rgba(226, 232, 240, 0.5)',
              drawBorder: false
            },
            ticks: {
              font: {
                family: 'Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif',
                size: 10
              },
              color: '#64748b',
              callback: function(value) {
                return new Intl.NumberFormat('fr-FR').format(value);
              }
            }
          },
          x: {
            grid: {
              display: false,
              drawBorder: false
            },
            ticks: {
              font: {
                family: 'Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif',
                size: 11,
                weight: 600
              },
              color: '#0f172a'
            }
          }
        }
      }
    });
  }

  // Close modal when clicking on overlay
  document.addEventListener('click', function(e) {
    const modal = document.getElementById('farmerModal');
    if (e.target === modal) {
      closeFarmerModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeFarmerModal();
    }
  });

  function updateSystemStatus() {
    const services = [
      { name: "Base de données", status: "online", uptime: "99.9%" },
      { name: "API Services", status: "online", uptime: "99.7%" },
      { name: "File System", status: "online", uptime: "100%" },
      { name: "Cache Redis", status: "online", uptime: "99.8%" },
      { name: "Backup Service", status: "warning", uptime: "95.2%" }
    ];

    const container = document.getElementById('systemStatus');
    if (container) {
      container.innerHTML = services.map(service => `
        <div class="status-item">
          <div class="status-indicator status-${service.status}"></div>
          <div class="status-info">
            <div class="status-name">${service.name}</div>
            <div class="status-uptime">Uptime: ${service.uptime}</div>
          </div>
        </div>
      `).join('');
    }
  }
})();
