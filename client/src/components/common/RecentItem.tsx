import React from 'react';
import { PiFile } from 'react-icons/pi';

interface RecentItemProps {
  label: string;
}

export const RecentItem: React.FC<RecentItemProps> = ({ label }) => (
  <button className="cursor-pointer w-full flex items-center space-x-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
    <PiFile size={16} />
    <span className="truncate">{label}</span>
  </button>
);
