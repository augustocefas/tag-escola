import React from 'react';
import { createPortal } from 'react-dom';
import { PiTrash, PiX, PiSpinner } from 'react-icons/pi';

interface ConfirmDeleteModalProps {
  open: boolean;
  title?: string;
  description?: React.ReactNode | string;
  loading?: boolean;
  onConfirm: () => void;
  onCancel: () => void;
}

export const ConfirmDeleteModal: React.FC<ConfirmDeleteModalProps> = ({
  open,
  title = 'Confirmar exclusão',
  description = 'Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.',
  loading = false,
  onConfirm,
  onCancel,
}) => {
  if (!open) return null;

  return createPortal(
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
      onClick={(e) => { if (e.target === e.currentTarget) onCancel(); }}
    >
      <div className="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6 animate-[slideInUp_0.2s_ease-out]">
        {/* Header */}
        <div className="flex items-start justify-between mb-4 ">
          <div className="flex items-center gap-3">
            <div className="p-2 bg-red-100 dark:bg-red-900/30 rounded-full">
              <PiTrash size={20} className="text-red-600 dark:text-red-400" />
            </div>
            <h2 className="text-base font-semibold text-gray-800 dark:text-white">{title}</h2>
          </div>
          <button
            onClick={onCancel}
            className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
          >
            <PiX size={20} />
          </button>
        </div>

        {/* Body */}
        <p className="text-sm text-gray-500 dark:text-gray-400 mb-6">{description}</p>

        {/* Actions */}
        <div className="flex justify-end gap-2">
          <button
            type="button"
            onClick={onCancel}
            disabled={loading}
            className="px-4 py-2 text-sm font-medium border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50"
          >
            Cancelar
          </button>
          <button
            type="button"
            onClick={onConfirm}
            disabled={loading}
            className="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {loading && <PiSpinner className="animate-spin" size={15} />}
            Excluir
          </button>
        </div>
      </div>
    </div>,
    document.body,
  );
};
