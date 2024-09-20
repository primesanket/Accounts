import React from 'react'

interface FormTextAreaProps {
    label?: string
    name: string
    id: string
    value?: string | number
    onChange?: (e: React.ChangeEvent<HTMLTextAreaElement>) => void
    onKeyDown?: (e: React.KeyboardEvent<HTMLTextAreaElement>) => void
    onBlur?: (e: React.ChangeEvent<HTMLTextAreaElement>) => void
    rows?: number
    placeholder?: string
    className?: string
    required?: boolean
    disabled?: boolean
    readOnly?: boolean
    error?: string
    icons?: React.ReactNode
}

const FormTextArea: React.FC<FormTextAreaProps> = ({ className, label, name, id, value, error, required, disabled, placeholder, readOnly, icons, rows, onChange, onKeyDown, onBlur }): JSX.Element => {
    return (
        <div className={'w-full ' + (className ? className : '')}>
            {label && <label htmlFor={id} className="form-label">
                {label} {required === true && <span className="text-danger">*</span>}
            </label>}
            <div className="relative">
                <textarea
                    readOnly={readOnly}
                    name={name}
                    id={id}
                    value={value}
                    className={"form-control w-full " + (error ? 'border-danger' : '')}
                    disabled={disabled}
                    placeholder={placeholder}
                    autoComplete='off'
                    rows={rows}
                    onChange={onChange}
                    onKeyDown={onKeyDown}
                    onBlur={onBlur}
                />
                {icons ? icons : ''}
                {error && <div className="text-danger mt-1">{error}</div>}
            </div>
        </div>
    )
}

export default FormTextArea