<x-navigation />
<nav class="bg-white shadow-lg border-b-4 border-blue-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <h1 class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-graduation-cap mr-2"></i>StudentsApp
                    </h1>
                </div>
                <div class="hidden md:block ml-10">
                    <div class="flex items-baseline space-x-4">
                        <a href="#" class="nav-link text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-home mr-2"></i>Inicio
                        </a>
                        <a href="#" class="nav-link text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-users mr-2"></i>Estudiantes
                        </a>
                        <a href="#" class="nav-link text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-circle mr-2"></i>Mi Perfil
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img class="h-10 w-10 rounded-full border-2 border-blue-400" 
                         src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Crect width='40' height='40' fill='%233b82f6'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='0.3em' fill='white' font-size='16' font-weight='bold'%3EAD%3C/text%3E%3C/svg%3E" 
                         alt="Admin">
                    <span class="status-online"></span>
                </div>
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>Salir
                </button>
            </div>
        </div>
    </div>
</nav>

