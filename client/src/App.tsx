import { Routes, Route } from 'react-router-dom';
import Login from './pages/Login';
import { ProtectedRoute } from './components/common/ProtectedRoute';
import { MainLayout } from './components/layout/MainLayout';

import { UsuariosConfig } from './pages/Config/UsuariosConfig';


function App() {
    return (
        <Routes>
            <Route path="/login" element={<Login />} />
            <Route element={<ProtectedRoute />}>
                <Route element={<MainLayout />}>
                    <Route path="/" element={<></>} />
                    <Route path="/config/usuarios" element={<UsuariosConfig />} />
                </Route>
            </Route>
        </Routes>
    );
}

export default App;
