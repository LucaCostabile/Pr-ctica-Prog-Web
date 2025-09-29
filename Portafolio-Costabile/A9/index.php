<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Especialidades Médicas - Clínica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            margin-top: 50px;
            padding: 40px;
        }
        
        .title-header {
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .form-select {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .description-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: none;
            border-radius: 12px;
            padding: 25px;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            display: none;
            transition: all 0.3s ease;
        }
        
        .description-card.show {
            display: block;
            animation: fadeInUp 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .specialty-title {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.25rem;
        }
        
        .specialty-description {
            color: #4a5568;
            line-height: 1.6;
            text-align: justify;
        }
        
        .loading-spinner {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
        
        .medical-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="main-container">
                    <div class="medical-icon">
                        🏥
                    </div>
                    
                    <h1 class="title-header">
                        Especialidades Médicas
                    </h1>
                    
                    <div class="mb-4">
                        <label for="especialidadSelect" class="form-label fw-semibold">
                            Seleccione una especialidad:
                        </label>
                        <select class="form-select" id="especialidadSelect">
                            <option value="">-- Seleccione una especialidad --</option>
                        </select>
                    </div>
                    
                    <div class="loading-spinner" id="loadingSpinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando descripción...</p>
                    </div>
                    
                    <div class="alert alert-danger" id="errorAlert" style="display: none;">
                        <strong>Error:</strong> <span id="errorMessage"></span>
                    </div>
                    
                    <div class="description-card" id="descriptionCard">
                        <h3 class="specialty-title" id="specialtyTitle"></h3>
                        <p class="specialty-description" id="specialtyDescription"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        class EspecialidadesManager {
            constructor() {
                this.especialidadSelect = document.getElementById('especialidadSelect');
                this.descriptionCard = document.getElementById('descriptionCard');
                this.specialtyTitle = document.getElementById('specialtyTitle');
                this.specialtyDescription = document.getElementById('specialtyDescription');
                this.loadingSpinner = document.getElementById('loadingSpinner');
                this.errorAlert = document.getElementById('errorAlert');
                this.errorMessage = document.getElementById('errorMessage');
                
                this.init();
            }
            
            async init() {
                await this.cargarEspecialidades();
                this.setupEventListeners();
            }
            
            setupEventListeners() {
                this.especialidadSelect.addEventListener('change', (e) => {
                    if (e.target.value) {
                        this.cargarDescripcion(e.target.value);
                    } else {
                        this.hideDescription();
                    }
                });
            }
            
            async cargarEspecialidades() {
                try {
                    const response = await fetch('api/get_especialidades.php');
                    if (!response.ok) {
                        throw new Error('Error al cargar las especialidades');
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.poblarCombobox(data.especialidades);
                    } else {
                        this.showError(data.message || 'Error al cargar las especialidades');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showError('Error de conexión al servidor');
                }
            }
            
            poblarCombobox(especialidades) {
                // Limpiar opciones existentes (excepto la primera)
                while (this.especialidadSelect.children.length > 1) {
                    this.especialidadSelect.removeChild(this.especialidadSelect.lastChild);
                }
                
                // Agregar las especialidades
                especialidades.forEach(especialidad => {
                    const option = document.createElement('option');
                    option.value = especialidad.id;
                    option.textContent = especialidad.nombre;
                    this.especialidadSelect.appendChild(option);
                });
            }
            
            async cargarDescripcion(especialidadId) {
                this.showLoading();
                this.hideError();
                this.hideDescription();
                
                try {
                    const response = await fetch(`api/get_descripcion.php?id=${especialidadId}`);
                    if (!response.ok) {
                        throw new Error('Error al cargar la descripción');
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.showDescription(data.especialidad);
                    } else {
                        this.showError(data.message || 'Error al cargar la descripción');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showError('Error de conexión al servidor');
                } finally {
                    this.hideLoading();
                }
            }
            
            showDescription(especialidad) {
                this.specialtyTitle.textContent = especialidad.nombre;
                this.specialtyDescription.textContent = especialidad.descripcion;
                this.descriptionCard.classList.add('show');
            }
            
            hideDescription() {
                this.descriptionCard.classList.remove('show');
            }
            
            showLoading() {
                this.loadingSpinner.style.display = 'block';
            }
            
            hideLoading() {
                this.loadingSpinner.style.display = 'none';
            }
            
            showError(message) {
                this.errorMessage.textContent = message;
                this.errorAlert.style.display = 'block';
            }
            
            hideError() {
                this.errorAlert.style.display = 'none';
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            new EspecialidadesManager();
        });
    </script>
</body>
</html>