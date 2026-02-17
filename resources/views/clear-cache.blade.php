<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clear Cache - Sistem Analisis CPL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #38A169;
            --primary-green-dark: #2F855A;
            --primary-green-light: #68D391;
        }

        body {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .cache-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .cache-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2rem;
        }

        .btn-clear {
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(56, 161, 105, 0.3);
        }

        .btn-clear:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(56, 161, 105, 0.4);
            background: linear-gradient(135deg, var(--primary-green-dark) 0%, #22543D 100%);
        }

        .result-container {
            margin-top: 2rem;
            padding: 1rem;
            border-radius: 12px;
            display: none;
        }

        .result-success {
            background: #D4EDDA;
            border: 1px solid #C3E6CB;
            color: #155724;
        }

        .result-error {
            background: #F8D7DA;
            border: 1px solid #F5C6CB;
            color: #721C24;
        }

        .loading {
            display: none;
        }

        .spinner-border {
            color: var(--primary-green);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cache-card">
            <div class="cache-icon">
                <i class="fas fa-sync-alt"></i>
            </div>
            
            <h2 class="mb-3">Clear Cache Hosting</h2>
            <p class="text-muted mb-4">
                Gunakan tombol ini untuk menghapus cache aplikasi Laravel di hosting Hostinger.
                Ini akan mengatasi masalah tampilan lama yang ter-cache.
            </p>

            <button id="clearCacheBtn" class="btn btn-clear btn-lg">
                <i class="fas fa-trash-alt me-2"></i>
                Clear All Cache
            </button>

            <div class="loading mt-3">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Clearing cache...</p>
            </div>

            <div id="resultContainer" class="result-container">
                <div id="resultContent"></div>
            </div>

            <div class="mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Jalankan ini setelah upload file baru atau mengalami masalah cache
                </small>
            </div>

            <div class="mt-3">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('clearCacheBtn').addEventListener('click', function() {
            const btn = this;
            const loading = document.querySelector('.loading');
            const resultContainer = document.getElementById('resultContainer');
            const resultContent = document.getElementById('resultContent');

            // Show loading state
            btn.disabled = true;
            loading.style.display = 'block';
            resultContainer.style.display = 'none';

            // Call clear cache endpoint
            fetch('/clear-cache-hosting')
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    resultContainer.style.display = 'block';
                    
                    if (data.success) {
                        resultContainer.className = 'result-container result-success';
                        resultContent.innerHTML = `
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Cache cleared successfully!</strong>
                            </div>
                            <small>Timestamp: ${data.timestamp}</small>
                        `;
                    } else {
                        resultContainer.className = 'result-container result-error';
                        resultContent.innerHTML = `
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Error clearing cache</strong>
                            </div>
                            <small>${data.message}</small>
                        `;
                    }
                })
                .catch(error => {
                    loading.style.display = 'none';
                    resultContainer.style.display = 'block';
                    resultContainer.className = 'result-container result-error';
                    resultContent.innerHTML = `
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Network Error</strong>
                        </div>
                        <small>${error.message}</small>
                    `;
                })
                .finally(() => {
                    btn.disabled = false;
                });
        });
    </script>
</body>
</html> 