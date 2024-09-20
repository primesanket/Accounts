// utils/errorMapper.ts

interface LaravelErrors {
    [key: string]: string[];
}

interface FormikErrors {
    [key: string]: string;
}

export const mapLaravelErrorsToFormik = (errors: LaravelErrors): FormikErrors => {
    const formikErrors: FormikErrors = {};
    for (const [key, value] of Object.entries(errors)) {
        formikErrors[key] = value[0]; // Assuming errors are in array format
    }
    return formikErrors;
};
