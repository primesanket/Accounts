import React from 'react';
import Loader from '@/components/Atoms/Loader/Loader';

interface FormButtonProps {
    label?: string;
    onClick?: () => void;
    type?: 'button' | 'submit' | 'reset';
    variant?: 'primary' | 'secondary' | 'danger';
    size?: 'sm' | 'md' | 'lg';
    loading?: boolean;
    disabled?: boolean;
    children?: React.ReactNode;
    className?: string;
}

const FormButton: React.FC<FormButtonProps> = ({
    label,
    onClick,
    type = 'button',
    variant = 'primary',
    size = 'md',
    loading = false,
    disabled = false,
    children,
    className,
}) => {
    const getButtonClasses = (): string => {
        const baseClasses = `btn btn-${size} btn-${variant} py-3 px-4 w-full xl:w-32 xl:mr-3 align-top`;
        const stateClasses = loading || disabled ? 'opacity-50 cursor-not-allowed' : '';
        return `${baseClasses} ${stateClasses} ${className}`.trim();
    };

    const handleClick = (_event: React.MouseEvent<HTMLButtonElement, MouseEvent>) => {
        if (!loading && onClick) {
            onClick();
        }
    };

    return (
        <button
            type={type}
            className={getButtonClasses()}
            onClick={handleClick}
            disabled={disabled || loading}
            aria-busy={loading}
            aria-disabled={disabled || loading}
        >
            {loading ? (
                <span>
                    <Loader />
                </span>
            ) : (
                <>
                    {children || label}
                </>
            )}
        </button>
    );
};

export default FormButton;