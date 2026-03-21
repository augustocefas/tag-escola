import React from 'react';

interface ActivityItemProps {
  icon: React.ReactNode;
  title: string;
  time: string;
  color: 'blue' | 'green' | 'purple';
}

export const ActivityItem: React.FC<ActivityItemProps> = ({ icon, title, time, color }) => {
  const colors = {
    blue: 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
    green: 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
    purple: 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400',
  };

  return (
    <div className="flex items-start space-x-3">
      <div className={`p-2 rounded-lg ${colors[color]}`}>
        {icon}
      </div>
      <div className="flex-1">
        <p className="text-sm text-gray-800 dark:text-gray-200">{title}</p>
        <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">{time}</p>
      </div>
    </div>
  );
};
