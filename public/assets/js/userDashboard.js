document.addEventListener('DOMContentLoaded', function() {
    if ($("#performanceLineParc").length) {
        const ctx = document.getElementById('performanceLineParc');
        var graphGradient = document.getElementById("performanceLineParc").getContext('2d');
        var graphGradient2 = document.getElementById("performanceLineParc").getContext('2d');
        
        var saleGradientBg = graphGradient.createLinearGradient(5, 0, 5, 100);
        saleGradientBg.addColorStop(0, 'rgba(26, 115, 232, 0.18)');
        saleGradientBg.addColorStop(1, 'rgba(26, 115, 232, 0.02)');
        
        var saleGradientBg2 = graphGradient2.createLinearGradient(100, 0, 50, 150);
        saleGradientBg2.addColorStop(0, 'rgba(0, 208, 255, 0.19)');
        saleGradientBg2.addColorStop(1, 'rgba(0, 208, 255, 0.03)');
  
        // These variables will be set from the template
        const formattedDates = parcChart.formattedDates;
        const dailyParcCounts = parcChart.parcCounts;
        const cumulativeParcCounts = parcChart.cumulativeData;
  
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedDates,
                datasets: [{
                    label: 'Parc totaux',
                    data: cumulativeParcCounts,
                    backgroundColor: saleGradientBg,
                    borderColor: [
                        '#1F3BB3',
                    ],
                    borderWidth: 1.5,
                    fill: true,
                    pointBorderWidth: 1,
                    pointRadius: 4,
                    pointHoverRadius: 2,
                    pointBackgroundColor: '#1F3BB3',
                    pointBorderColor: '#fff',
                },{
                    label: 'Nouveaux parc par jour',
                    data: dailyParcCounts,
                    backgroundColor: saleGradientBg2,
                    borderColor: [
                        '#52CDFF',
                    ],
                    borderWidth: 1.5,
                    fill: true,
                    pointBorderWidth: 1,
                    pointRadius: 4,
                    pointHoverRadius: 2,
                    pointBackgroundColor: '#52CDFF',
                    pointBorderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    line: {
                        tension: 0.4,
                    }
                },
                scales: {
                    y: {
                        border: {
                            display: false
                        },
                        grid: {
                            display: true,
                            color: "#F0F0F0",
                            drawBorder: false,
                        },
                        ticks: {
                            beginAtZero: true,
                            autoSkip: true,
                            maxTicksLimit: 4,
                            color: "#6B778C",
                            font: {
                                size: 10,
                            }
                        }
                    },
                    x: {
                        border: {
                            display: false
                        },
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 10,
                            color: "#6B778C",
                            font: {
                                size: 10,
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y + ' utilisateurs';
                                return label;
                            }
                        }
                    }
                }
            },
            plugins: [{
                afterDatasetUpdate: function(chart, args, options) {
                    const chartId = chart.canvas.id;
                    const legendId = `${chartId}-legend`;
                    const legendElement = document.getElementById(legendId);
                    legendElement.innerHTML = '';
                    
                    const ul = document.createElement('ul');
                    for(let i = 0; i < chart.data.datasets.length; i++) {
                        ul.innerHTML += `
                        <li>
                            <span style="background-color: ${chart.data.datasets[i].borderColor}"></span>
                            ${chart.data.datasets[i].label}
                        </li>
                        `;
                    }
                    legendElement.appendChild(ul);
                }
            }]
        });
    }
  });