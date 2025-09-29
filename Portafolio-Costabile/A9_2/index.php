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
                    
                    <!-- Autenticación -->
                    <div class="mb-4 border rounded p-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Sesión de usuario</h5>
                            <span id="authStatus" class="badge text-bg-secondary">No autenticado</span>
                        </div>
                        <form id="loginForm" class="row g-2">
                            <div class="col-12 col-md-5">
                                <input type="email" class="form-control" id="loginEmail" placeholder="Email" required>
                            </div>
                            <div class="col-12 col-md-5">
                                <input type="password" class="form-control" id="loginPassword" placeholder="Contraseña" required>
                            </div>
                            <div class="col-12 col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </form>
                        <div class="mt-2 d-flex gap-2">
                            <button id="logoutBtn" class="btn btn-outline-danger btn-sm" disabled>Salir</button>
                            <small class="text-muted">Demo: demo@clinic.com / Demo1234!</small>
                        </div>
                        <div id="loginAlert" class="alert alert-warning mt-2 py-2 px-3" style="display:none"></div>
                    </div>

                    <!-- Gestión de Especialidades -->
                    <div class="mb-4">
                        <label for="especialidadSelect" class="form-label fw-semibold">
                            Seleccione una especialidad:
                        </label>
                        <select class="form-select" id="especialidadSelect">
                            <option value="">-- Seleccione una especialidad --</option>
                        </select>
                    </div>

                    <div class="mb-4 border rounded p-3 bg-light">
                        <h5>Registrar nueva especialidad</h5>
                        <form id="addEspecialidadForm" class="row g-2">
                            <div class="col-12 col-md-5">
                                <input type="text" id="newNombre" class="form-control" placeholder="Nombre" maxlength="100" required>
                            </div>
                            <div class="col-12 col-md-5">
                                <input type="text" id="newDescripcion" class="form-control" placeholder="Descripción" required>
                            </div>
                            <div class="col-12 col-md-2 d-grid">
                                <button class="btn btn-success" type="submit">Agregar</button>
                            </div>
                        </form>
                        <div id="addAlert" class="alert mt-2 py-2 px-3" style="display:none"></div>
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

                    <!-- Datos de Usuario con sesión -->
                    <div class="mt-4 border rounded p-3 bg-light">
                        <h5>Datos del usuario (requiere sesión)</h5>
                        <form id="userDataForm" class="row g-2">
                            <div class="col-12 col-md-4">
                                <input type="text" id="telefono" class="form-control" placeholder="Teléfono">
                            </div>
                            <div class="col-12 col-md-4">
                                <input type="text" id="direccion" class="form-control" placeholder="Dirección">
                            </div>
                            <div class="col-12 col-md-4">
                                <input type="text" id="notas" class="form-control" placeholder="Notas">
                            </div>
                            <div class="col-12 d-grid mt-2">
                                <button class="btn btn-primary" type="submit" id="saveUserDataBtn" disabled>Guardar datos</button>
                            </div>
                        </form>
                        <div id="userDataAlert" class="alert mt-2 py-2 px-3" style="display:none"></div>
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
                // Nuevos elementos
                this.addForm = document.getElementById('addEspecialidadForm');
                this.addAlert = document.getElementById('addAlert');
                this.loginForm = document.getElementById('loginForm');
                this.loginAlert = document.getElementById('loginAlert');
                this.logoutBtn = document.getElementById('logoutBtn');
                this.authStatus = document.getElementById('authStatus');
                this.userDataForm = document.getElementById('userDataForm');
                this.saveUserDataBtn = document.getElementById('saveUserDataBtn');
                
                this.init();
            }
            
            async init() {
                await Promise.all([
                    this.cargarEspecialidades(),
                    this.checkAuth()
                ]);
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

                this.addForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    this.hideAddAlert();
                    const nombre = document.getElementById('newNombre').value.trim();
                    const descripcion = document.getElementById('newDescripcion').value.trim();
                    if (!nombre || !descripcion) return;
                    try {
                        const resp = await fetch('api/add_especialidad.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ nombre, descripcion })
                        });
                        const data = await resp.json();
                        if (!resp.ok || !data.success) throw new Error(data.message || 'Error al agregar');
                        this.showAddAlert('Especialidad agregada con éxito', 'success');
                        // Recargar lista
                        await this.cargarEspecialidades();
                        // Seleccionar la nueva
                        this.especialidadSelect.value = data.id;
                        this.cargarDescripcion(data.id);
                        this.addForm.reset();
                    } catch (err) {
                        this.showAddAlert(err.message, 'danger');
                    }
                });

                this.loginForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    this.hideLoginAlert();
                    const email = document.getElementById('loginEmail').value.trim();
                    const password = document.getElementById('loginPassword').value;
                    try {
                        const resp = await fetch('api/login.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ email, password })
                        });
                        const data = await resp.json();
                        if (!resp.ok || !data.success) throw new Error(data.message || 'Login fallido');
                        this.setAuthState(true, data.user);
                        this.loginForm.reset();
                        await this.loadUserProfile();
                    } catch (err) {
                        this.showLoginAlert(err.message);
                    }
                });

                this.logoutBtn.addEventListener('click', async () => {
                    try {
                        await fetch('api/logout.php', { method: 'POST' });
                        this.setAuthState(false, null);
                        this.userDataForm.reset();
                    } catch {}
                });

                this.userDataForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const telefono = document.getElementById('telefono').value.trim();
                    const direccion = document.getElementById('direccion').value.trim();
                    const notas = document.getElementById('notas').value.trim();
                    try {
                        const resp = await fetch('api/save_user_data.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ telefono, direccion, notas })
                        });
                        const data = await resp.json();
                        if (!resp.ok || !data.success) throw new Error(data.message || 'Error al guardar');
                        this.showUserDataAlert('Datos guardados correctamente', 'success');
                    } catch (err) {
                        this.showUserDataAlert(err.message, 'danger');
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

            // --- Auth helpers ---
            async checkAuth() {
                try {
                    const resp = await fetch('api/me.php');
                    const data = await resp.json();
                    if (data.authenticated) {
                        this.setAuthState(true, data.user);
                        if (data.profile) this.fillUserProfile(data.profile);
                    } else {
                        this.setAuthState(false, null);
                    }
                } catch {
                    this.setAuthState(false, null);
                }
            }

            setAuthState(authenticated, user) {
                if (authenticated) {
                    this.authStatus.className = 'badge text-bg-success';
                    this.authStatus.textContent = user?.nombre ? `Autenticado: ${user.nombre}` : 'Autenticado';
                    this.logoutBtn.disabled = false;
                    this.saveUserDataBtn.disabled = false;
                } else {
                    this.authStatus.className = 'badge text-bg-secondary';
                    this.authStatus.textContent = 'No autenticado';
                    this.logoutBtn.disabled = true;
                    this.saveUserDataBtn.disabled = true;
                }
            }

            async loadUserProfile() {
                const resp = await fetch('api/me.php');
                const data = await resp.json();
                if (data.profile) this.fillUserProfile(data.profile);
            }

            fillUserProfile(p) {
                document.getElementById('telefono').value = p.telefono || '';
                document.getElementById('direccion').value = p.direccion || '';
                document.getElementById('notas').value = p.notas || '';
            }

            showLoginAlert(msg) {
                this.loginAlert.textContent = msg;
                this.loginAlert.style.display = 'block';
            }
            hideLoginAlert() { this.loginAlert.style.display = 'none'; }

            showAddAlert(msg, type) {
                this.addAlert.className = `alert alert-${type} mt-2 py-2 px-3`;
                this.addAlert.textContent = msg;
                this.addAlert.style.display = 'block';
            }
            hideAddAlert() { this.addAlert.style.display = 'none'; }

            showUserDataAlert(msg, type) {
                const el = document.getElementById('userDataAlert');
                el.className = `alert alert-${type} mt-2 py-2 px-3`;
                el.textContent = msg;
                el.style.display = 'block';
                setTimeout(() => el.style.display = 'none', 3000);
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            new EspecialidadesManager();
        });
    </script>
</body>
</html>