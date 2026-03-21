import React from 'react';
import { Drawer, IconButton, Typography, Box, Divider } from '@mui/material';
import { PiX } from 'react-icons/pi';

interface CustomDrawerProps {
  open: boolean;
  onClose: () => void;
  title: string;
  children: React.ReactNode;
  width?: number | string;
  footer?: React.ReactNode;
}

export const CustomDrawer: React.FC<CustomDrawerProps> = ({
  open,
  onClose,
  title,
  children,
  width = { xs: '90%', sm: 600, md: 800 },
  footer
}) => {
  return (
    <Drawer
      anchor="right"
      open={open}
      onClose={onClose}
      PaperProps={{
        sx: { 
          width, 
          maxWidth: '100%',
          bgcolor: 'transparent',
          backgroundImage: 'none'
        },
        className: 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100'
      }}
    >
      {/* Header */}
      <Box className="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/90 shrink-0">
        <Typography variant="h6" className="font-semibold text-gray-800 dark:text-gray-100" sx={{ fontSize: '1.125rem' }}>
          {title}
        </Typography>
        <IconButton 
          onClick={onClose} 
          size="small" 
          className="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 transition-colors"
        >
          <PiX size={24} className="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 transition-colors"/>
        </IconButton>
      </Box>

      {/* Body */}
      <Box className="flex-1 overflow-y-auto bg-white dark:bg-gray-800">
        {children}
      </Box>

      {/* Footer */}
      {footer && (
        <React.Fragment>
          <Divider className="border-gray-200 dark:border-gray-700" />
          <Box className="p-4 flex justify-end gap-3 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-200 dark:border-gray-700 shrink-0">
            {footer}
          </Box>
        </React.Fragment>
      )}
    </Drawer>
  );
};
