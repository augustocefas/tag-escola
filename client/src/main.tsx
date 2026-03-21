import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { QueryClientProvider } from '@tanstack/react-query';
import { ReactQueryDevtools } from '@tanstack/react-query-devtools';
import { queryClient } from '@/lib/queryClient';
import './index.css';
import App from './App.tsx';
import { BrowserRouter } from 'react-router-dom';
import { LicenseInfo } from '@mui/x-license';

LicenseInfo.setLicenseKey(import.meta.env.VITE_MUI_LICENSE_KEY);

createRoot(document.getElementById('root')!).render(
    <StrictMode>
        <QueryClientProvider client={queryClient}>
            <BrowserRouter>
                <App />
            </BrowserRouter>

            <ReactQueryDevtools
                initialIsOpen={false}
                buttonPosition="bottom-left"
            />
        </QueryClientProvider>
    </StrictMode>,
);
