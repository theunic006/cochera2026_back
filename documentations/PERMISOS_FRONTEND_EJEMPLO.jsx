// 🔐 Ejemplo de Implementación de Permisos en React/Frontend

// ============================================
// 1. Context de Permisos (usePermissions.js)
// ============================================

import { createContext, useContext, useState, useEffect } from 'react';

const PermissionsContext = createContext();

export const PermissionsProvider = ({ children }) => {
  const [permissions, setPermissions] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Cargar permisos del usuario desde localStorage o API
    const loadPermissions = async () => {
      try {
        const user = JSON.parse(localStorage.getItem('user'));
        if (user && user.permissions) {
          const permissionSlugs = user.permissions.map(p => p.slug);
          setPermissions(permissionSlugs);
        }
      } catch (error) {
        console.error('Error loading permissions:', error);
      } finally {
        setLoading(false);
      }
    };

    loadPermissions();
  }, []);

  const hasPermission = (permission) => {
    return permissions.includes(permission);
  };

  const hasAnyPermission = (permissionsArray) => {
    return permissionsArray.some(p => permissions.includes(p));
  };

  const hasAllPermissions = (permissionsArray) => {
    return permissionsArray.every(p => permissions.includes(p));
  };

  return (
    <PermissionsContext.Provider value={{
      permissions,
      hasPermission,
      hasAnyPermission,
      hasAllPermissions,
      loading
    }}>
      {children}
    </PermissionsContext.Provider>
  );
};

export const usePermissions = () => {
  const context = useContext(PermissionsContext);
  if (!context) {
    throw new Error('usePermissions must be used within PermissionsProvider');
  }
  return context;
};

// ============================================
// 2. Componente de Protección (ProtectedRoute.jsx)
// ============================================

import { Navigate } from 'react-router-dom';
import { usePermissions } from './usePermissions';

const ProtectedRoute = ({ children, permission, fallback = '/no-permission' }) => {
  const { hasPermission, loading } = usePermissions();

  if (loading) {
    return <div>Cargando...</div>;
  }

  if (!hasPermission(permission)) {
    return <Navigate to={fallback} replace />;
  }

  return children;
};

export default ProtectedRoute;

// ============================================
// 3. Componente Condicional (Can.jsx)
// ============================================

import { usePermissions } from './usePermissions';

const Can = ({ perform, children, fallback = null }) => {
  const { hasPermission } = usePermissions();

  if (!hasPermission(perform)) {
    return fallback;
  }

  return children;
};

export default Can;

// ============================================
// 4. Hook Personalizado (useAuth.js actualizado)
// ============================================

import { useState, useEffect } from 'react';
import axios from 'axios';

export const useAuth = () => {
  const [user, setUser] = useState(null);
  const [permissions, setPermissions] = useState([]);
  const [loading, setLoading] = useState(true);

  const login = async (email, password) => {
    try {
      const response = await axios.post('http://127.0.0.1:8000/api/auth/login', {
        email,
        password
      });

      const { user, token, permissions } = response.data.data;

      // Guardar en localStorage
      localStorage.setItem('token', token);
      localStorage.setItem('user', JSON.stringify(user));

      // Actualizar estado
      setUser(user);
      setPermissions(permissions || []);

      // Configurar axios para futuras peticiones
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

      return { success: true };
    } catch (error) {
      return { success: false, error: error.response?.data?.message || 'Error al iniciar sesión' };
    }
  };

  const logout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    setUser(null);
    setPermissions([]);
    delete axios.defaults.headers.common['Authorization'];
  };

  useEffect(() => {
    // Cargar usuario y token al iniciar
    const token = localStorage.getItem('token');
    const savedUser = localStorage.getItem('user');

    if (token && savedUser) {
      setUser(JSON.parse(savedUser));
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

      // Opcional: Recargar permisos desde el servidor
      fetchUserPermissions();
    }

    setLoading(false);
  }, []);

  const fetchUserPermissions = async () => {
    try {
      const savedUser = JSON.parse(localStorage.getItem('user'));
      if (!savedUser) return;

      const response = await axios.get(`http://127.0.0.1:8000/api/permissions/users/${savedUser.id}`);
      const userPermissions = response.data.data.permissions.map(p => p.slug);
      setPermissions(userPermissions);
    } catch (error) {
      console.error('Error fetching permissions:', error);
    }
  };

  return {
    user,
    permissions,
    loading,
    login,
    logout,
    isAuthenticated: !!user
  };
};

// ============================================
// 5. Configuración de Rutas (App.jsx)
// ============================================

import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { PermissionsProvider } from './hooks/usePermissions';
import ProtectedRoute from './components/ProtectedRoute';

// Importar páginas
import Dashboard from './pages/Dashboard';
import Users from './pages/Users';
import Ingresos from './pages/Ingresos';
import NoPermission from './pages/NoPermission';

function App() {
  return (
    <BrowserRouter>
      <PermissionsProvider>
        <Routes>
          {/* Ruta pública */}
          <Route path="/login" element={<Login />} />

          {/* Rutas protegidas por permisos */}
          <Route path="/dashboard" element={
            <ProtectedRoute permission="dashboard.view">
              <Dashboard />
            </ProtectedRoute>
          } />

          <Route path="/usuarios" element={
            <ProtectedRoute permission="users.view">
              <Users />
            </ProtectedRoute>
          } />

          <Route path="/ingresos" element={
            <ProtectedRoute permission="ingresos.view">
              <Ingresos />
            </ProtectedRoute>
          } />

          {/* Ruta de sin permiso */}
          <Route path="/no-permission" element={<NoPermission />} />
        </Routes>
      </PermissionsProvider>
    </BrowserRouter>
  );
}

export default App;

// ============================================
// 6. Ejemplo de Navegación con Permisos (Sidebar.jsx)
// ============================================

import { Link } from 'react-router-dom';
import { usePermissions } from '../hooks/usePermissions';
import {
  HomeIcon,
  UsersIcon,
  ClipboardIcon,
  CarIcon,
  ChartBarIcon
} from '@heroicons/react/outline';

const Sidebar = () => {
  const { hasPermission } = usePermissions();

  const menuItems = [
    {
      name: 'Dashboard',
      path: '/dashboard',
      icon: HomeIcon,
      permission: 'dashboard.view'
    },
    {
      name: 'Usuarios',
      path: '/usuarios',
      icon: UsersIcon,
      permission: 'users.view'
    },
    {
      name: 'Ingresos',
      path: '/ingresos',
      icon: ClipboardIcon,
      permission: 'ingresos.view'
    },
    {
      name: 'Vehículos',
      path: '/vehiculos',
      icon: CarIcon,
      permission: 'vehiculos.view'
    },
    {
      name: 'Reportes',
      path: '/reportes',
      icon: ChartBarIcon,
      permission: 'reportes.view'
    }
  ];

  return (
    <aside className="w-64 bg-gray-800 text-white">
      <nav className="mt-5">
        {menuItems.map((item) => {
          // Solo mostrar si tiene permiso
          if (!hasPermission(item.permission)) {
            return null;
          }

          const Icon = item.icon;

          return (
            <Link
              key={item.path}
              to={item.path}
              className="flex items-center px-6 py-3 hover:bg-gray-700"
            >
              <Icon className="h-5 w-5 mr-3" />
              {item.name}
            </Link>
          );
        })}
      </nav>
    </aside>
  );
};

export default Sidebar;

// ============================================
// 7. Ejemplo de Tabla con Acciones (UsersTable.jsx)
// ============================================

import { Can } from '../components/Can';
import { usePermissions } from '../hooks/usePermissions';

const UsersTable = ({ users }) => {
  const { hasPermission } = usePermissions();

  const handleEdit = (userId) => {
    // Lógica de edición
  };

  const handleDelete = (userId) => {
    // Lógica de eliminación
  };

  return (
    <div>
      <div className="flex justify-between mb-4">
        <h2>Usuarios</h2>

        {/* Botón visible solo si tiene permiso de crear */}
        <Can perform="users.create">
          <button className="bg-blue-500 text-white px-4 py-2 rounded">
            Crear Usuario
          </button>
        </Can>
      </div>

      <table className="min-w-full">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          {users.map(user => (
            <tr key={user.id}>
              <td>{user.id}</td>
              <td>{user.name}</td>
              <td>{user.email}</td>
              <td>
                {/* Botón de editar solo si tiene permiso */}
                <Can perform="users.edit">
                  <button
                    onClick={() => handleEdit(user.id)}
                    className="text-blue-600 hover:text-blue-800 mr-2"
                  >
                    Editar
                  </button>
                </Can>

                {/* Botón de eliminar solo si tiene permiso */}
                <Can perform="users.delete">
                  <button
                    onClick={() => handleDelete(user.id)}
                    className="text-red-600 hover:text-red-800"
                  >
                    Eliminar
                  </button>
                </Can>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default UsersTable;

// ============================================
// 8. Servicio de API de Permisos (permissionsService.js)
// ============================================

import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000/api';

export const permissionsService = {
  // Listar todos los permisos
  getAllPermissions: async (params = {}) => {
    const response = await axios.get(`${API_URL}/permissions`, { params });
    return response.data;
  },

  // Obtener módulos
  getModules: async () => {
    const response = await axios.get(`${API_URL}/permissions/modules`);
    return response.data;
  },

  // Obtener permisos de un usuario
  getUserPermissions: async (userId) => {
    const response = await axios.get(`${API_URL}/permissions/users/${userId}`);
    return response.data;
  },

  // Asignar permisos a un usuario
  assignPermissions: async (userId, permissionIds) => {
    const response = await axios.post(
      `${API_URL}/permissions/users/${userId}/assign`,
      { permission_ids: permissionIds }
    );
    return response.data;
  },

  // Dar un permiso
  givePermission: async (userId, permissionId) => {
    const response = await axios.post(
      `${API_URL}/permissions/users/${userId}/give`,
      { permission_id: permissionId }
    );
    return response.data;
  },

  // Revocar un permiso
  revokePermission: async (userId, permissionId) => {
    const response = await axios.post(
      `${API_URL}/permissions/users/${userId}/revoke`,
      { permission_id: permissionId }
    );
    return response.data;
  },

  // Verificar permiso
  checkPermission: async (userId, permissionSlug) => {
    const response = await axios.get(
      `${API_URL}/permissions/users/${userId}/check/${permissionSlug}`
    );
    return response.data;
  }
};

// ============================================
// 9. Componente de Gestión de Permisos (PermissionsManager.jsx)
// ============================================

import { useState, useEffect } from 'react';
import { permissionsService } from '../services/permissionsService';

const PermissionsManager = ({ userId }) => {
  const [allPermissions, setAllPermissions] = useState([]);
  const [userPermissions, setUserPermissions] = useState([]);
  const [selectedPermissions, setSelectedPermissions] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadData();
  }, [userId]);

  const loadData = async () => {
    try {
      setLoading(true);

      // Cargar todos los permisos
      const permsResponse = await permissionsService.getAllPermissions({ per_page: 100 });
      setAllPermissions(permsResponse.data);

      // Cargar permisos del usuario
      const userPermsResponse = await permissionsService.getUserPermissions(userId);
      const userPermIds = userPermsResponse.data.permissions.map(p => p.id);
      setUserPermissions(userPermIds);
      setSelectedPermissions(userPermIds);
    } catch (error) {
      console.error('Error loading permissions:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleTogglePermission = (permissionId) => {
    setSelectedPermissions(prev => {
      if (prev.includes(permissionId)) {
        return prev.filter(id => id !== permissionId);
      } else {
        return [...prev, permissionId];
      }
    });
  };

  const handleSave = async () => {
    try {
      await permissionsService.assignPermissions(userId, selectedPermissions);
      alert('Permisos actualizados exitosamente');
      loadData();
    } catch (error) {
      console.error('Error saving permissions:', error);
      alert('Error al guardar permisos');
    }
  };

  if (loading) {
    return <div>Cargando permisos...</div>;
  }

  // Agrupar permisos por módulo
  const groupedPermissions = allPermissions.reduce((acc, perm) => {
    if (!acc[perm.module]) {
      acc[perm.module] = [];
    }
    acc[perm.module].push(perm);
    return acc;
  }, {});

  return (
    <div className="permissions-manager">
      <h3>Gestionar Permisos</h3>

      {Object.entries(groupedPermissions).map(([module, permissions]) => (
        <div key={module} className="module-group mb-4">
          <h4 className="font-bold text-lg mb-2">{module}</h4>
          <div className="grid grid-cols-2 gap-2">
            {permissions.map(perm => (
              <label key={perm.id} className="flex items-center">
                <input
                  type="checkbox"
                  checked={selectedPermissions.includes(perm.id)}
                  onChange={() => handleTogglePermission(perm.id)}
                  className="mr-2"
                />
                <span>{perm.name}</span>
              </label>
            ))}
          </div>
        </div>
      ))}

      <button
        onClick={handleSave}
        className="bg-blue-500 text-white px-6 py-2 rounded mt-4"
      >
        Guardar Cambios
      </button>
    </div>
  );
};

export default PermissionsManager;

// ============================================
// 10. Uso en Main.jsx o index.jsx
// ============================================

import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import { PermissionsProvider } from './hooks/usePermissions';
import './index.css';

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <PermissionsProvider>
      <App />
    </PermissionsProvider>
  </React.StrictMode>
);
