import React from 'react'

interface FormInputProps {
    label?: string
    name: string
    id: string
    value?: string | number
    onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void
    onKeyDown?: (e: React.KeyboardEvent<HTMLInputElement>) => void
    onBlur?: (e: React.ChangeEvent<HTMLInputElement>) => void
    type: 'text' | 'email' | 'number' | 'date' | 'time' | 'search' | 'password'
    min?: number
    max?: number
    step?: string
    placeholder?: string
    className?: string
    required?: boolean
    disabled?: boolean
    readOnly?: boolean
    error?: string
    icons?: React.ReactNode
}

const FormInput: React.FC<FormInputProps> = ({ className, label, name, id, value, onChange, onKeyDown, type, min, max, error, required, disabled, placeholder, step, readOnly, icons, onBlur }): JSX.Element => {
    const today = new Date().toISOString().split('T')[0];
    return (
        <div className={'w-full ' + (className ? className : '')}>
            {label && <label htmlFor={id} className="form-label">
                {label} {required === true && <span className="text-danger">*</span>}
            </label>}
            <div className="mx-auto">
                <div className="relative">
                    {icons && <div className={`absolute index-top-1 rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400 ${error ? 'error-icon' : ''} `}> {icons} </div>}
                    <input
                        readOnly={readOnly}
                        type={type}
                        name={name}
                        id={id}
                        value={value}
                        onChange={onChange}
                        onKeyDown={onKeyDown}
                        className={`form-control w-full ${icons ? 'pl-12' : ''} ${error ? 'border-danger' : ''} `}
                        min={min || today}
                        max={max}
                        step={step}
                        disabled={disabled}
                        placeholder={placeholder}
                        onBlur={onBlur}
                        autoComplete='off'
                    />
                </div>
                {error && <div className="text-danger mt-1">{error}</div>}
            </div>
        </div>
    )
}

export default FormInput