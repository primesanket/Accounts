import React from "react";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css"; // Ensure this CSS is imported

interface FormDatePickerProps {
    id: string
    label?: string
    name?: string
    selectedDate?: Date | null
    onDateChange: (date: Date | null) => void;
    placeholder?: string
    className?: string
    required?: boolean
    disabled?: boolean
    readOnly?: boolean
    error?: string
    icons?: React.ReactNode
    viewMode?: "date" | "month"
    isClearable?: boolean
}

const FormDatePicker: React.FC<FormDatePickerProps> = ({
    className, label, name, id, selectedDate, onDateChange, error, required,
    disabled, placeholder, readOnly, icons, viewMode = "date", isClearable = false
}): JSX.Element => {

    return (
        <div className={'w-full ' + (className ? className : '')}>
            {label && <label htmlFor={id} className="form-label">
                {label} {required && <span className="text-danger">*</span>}
            </label>}
            <div className="mx-auto">
                <div className="relative">
                    {icons && <div className={`absolute index-top-1 rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400 ${error ? 'error-icon' : ''} `}> {icons} </div>}
                    <DatePicker
                        selected={selectedDate}
                        onChange={(date: Date | null) => onDateChange(date)}
                        className={`form-control w-full ${icons ? 'pl-12' : ''} ${error ? 'border-danger' : ''}`}
                        id={id}
                        name={name}
                        placeholderText={placeholder}
                        disabled={disabled}
                        readOnly={readOnly}
                        autoComplete="off"
                        dateFormat={viewMode === "month" ? "MM-yyyy" : "yyyy-MM-dd"}
                        showMonthYearPicker={viewMode === "month"}
                        showYearDropdown={viewMode === "month"}
                        scrollableYearDropdown={viewMode === "month"}
                        isClearable={isClearable}
                    />
                </div>
                {error && <div className="text-danger mt-1">{error}</div>}
            </div>
        </div>
    );
}

export default FormDatePicker;