import React from 'react';
import { createPortal } from 'react-dom';
import {
  PiCheckCircle,
  PiWarning,
  PiXCircle,
  PiInfo,
  PiX,
} from 'react-icons/pi';
import { useToastStore, type ToastType } from '../../stores/useToastStore';

const config: Record<
  ToastType,
  { bg: string; border: string; icon: React.ReactNode; text: string }
> = {
  success: {
    bg: 'bg-green-50 dark:bg-green-900/80',
    border: 'border-green-300 dark:border-green-700',
    text: 'text-green-800 dark:text-green-200',
    icon: <PiCheckCircle size={20} className="text-green-500 flex-shrink-0" />,
  },
  error: {
    bg: 'bg-red-50 dark:bg-red-900/80',
    border: 'border-red-300 dark:border-red-700',
    text: 'text-red-800 dark:text-red-100',
    icon: <PiXCircle size={20} className="text-red-500 flex-shrink-0" />,
  },
  warning: {
    bg: 'bg-yellow-50 dark:bg-yellow-900/80',
    border: 'border-yellow-300 dark:border-yellow-700',
    text: 'text-yellow-800 dark:text-yellow-200',
    icon: <PiWarning size={20} className="text-yellow-500 flex-shrink-0" />,
  },
  info: {
    bg: 'bg-blue-50 dark:bg-blue-900/80',
    border: 'border-blue-300 dark:border-blue-700',
    text: 'text-blue-800 dark:text-blue-200',
    icon: <PiInfo size={20} className="text-blue-500 flex-shrink-0" />,
  },
};

export const ToastContainer: React.FC = () => {
  const { toasts, removeToast } = useToastStore();

  return createPortal(
    <div
      aria-live="polite"
      className="fixed top-5 left-1/2 -translate-x-1/2 z-[9999] flex flex-col gap-2 pointer-events-none "
    >
      {toasts.map((t) => {
        const c = config[t.type];
        return (
          <div
            key={t.id}
            className={`
              pointer-events-auto flex items-start gap-3 w-80 max-w-[calc(100vw-2.5rem)]
              px-4 py-3 rounded-xl border shadow-lg
              animate-[slideInUp_0.25s_ease-out]
              ${c.bg} ${c.border}
            `}
          >
            {c.icon}
            <p className={`flex-1 text-sm font-medium leading-snug ${c.text}`}>
              {t.message}
            </p>
            <button
              onClick={() => removeToast(t.id)}
              className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 flex-shrink-0"
            >
              <PiX size={16} />
            </button>
          </div>
        );
      })}
    </div>,
    document.body,
  );
};
