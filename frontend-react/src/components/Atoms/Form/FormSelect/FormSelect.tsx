import React from 'react'
import Select from "react-select";
import CreatableSelect from 'react-select/creatable';

interface FormSelectProps {
    label?: string
    name: string
    id: string
    options: any[]
    value?: any
    onChange: (newValue: any) => void;
    isMulti?: boolean
    placeholder?: string
    className?: string
    required?: boolean
    isDisabled?: boolean
    loading?: boolean
    error?: string
    getOptionLabel?: any
    getOptionValue?: any
    isCreatable?: boolean
}

const FormSelect: React.FC<FormSelectProps> = ({ className, label, name, id, value, onChange, options, isMulti, loading, getOptionLabel, getOptionValue, error, required, isDisabled, placeholder, isCreatable }): JSX.Element => {

    const SelectComponent = isCreatable ? CreatableSelect : Select;

    return (
        <div className={'w-full ' + (className ? className : '')}>
            {label && <label htmlFor={id} className="form-label">
                {label} {required === true && <span className="text-danger">*</span>}
            </label>}
            <div className="relative">
                <SelectComponent
                    name={name}
                    inputId={id}
                    aria-labelledby={id}
                    options={options}
                    isMulti={isMulti}
                    value={value}
                    placeholder={placeholder}
                    className='form-control'
                    isDisabled={isDisabled}
                    onChange={(val) => onChange(val)}
                    getOptionLabel={getOptionLabel}
                    getOptionValue={getOptionValue}
                    isLoading={loading}
                    isClearable
                    styles={{
                        menu: (styles) => ({
                            ...styles,
                            zIndex: 999
                        }),
                        control: (styles) => ({
                            ...styles,
                            borderColor: error ? '#dc3545' : '#dee2e6'
                        }),
                        input: (styles) => ({
                            ...styles,
                            border: 'none',
                            outline: 'none',
                            boxShadow: 'none',
                            "input[type='text']": {
                                border: 'none',
                                outline: 'none',
                                boxShadow: 'none',
                            }
                        }),
                        multiValue: (styles) => {
                            return {
                                ...styles,
                                backgroundColor: '#e2e8f0',
                                borderRadius: 5
                            };
                        },
                        multiValueRemove: (styles) => {
                            return {
                                ...styles,
                                borderLeft: '1px solid #cbd5e1',
                            };
                        },
                        option: (styles, { isFocused, isSelected }) => ({
                            ...styles,
                            backgroundColor: isSelected ? 'rgb(var(--color-primary) / var(--tw-border-opacity))' : isFocused ? 'rgb(var(--color-primary) / 0.2)' : undefined, // Using undefined instead of null
                            color: isSelected ? '#fff' : '#000',
                        }),
                    }}
                />

                {error && <div className="text-danger mt-1">{error}</div>}
            </div>
        </div>
    )
}

export default FormSelect