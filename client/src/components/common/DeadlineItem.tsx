import React from 'react';

interface DeadlineItemProps {
  title: string;
  date: string;
  priority: 'high' | 'medium' | 'low';
}

export const DeadlineItem: React.FC<DeadlineItemProps> = ({ title, date, priority }) => {
  const priorities = {
    high: 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400',
    medium: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400',
    low: 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
  };

  return (
    <div className="flex items-center justify-between">
      <div>
        <p className="text-sm text-gray-800 dark:text-gray-200">{title}</p>
        <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">Prazo: {date}</p>
      </div>
      <span className={`px-2 py-1 rounded-full text-xs font-medium ${priorities[priority]}`}>
        {priority === 'high' ? 'Urgente' : priority === 'medium' ? 'Médio' : 'Normal'}
      </span>
    </div>
  );
};
