import * as Yup from "yup";

export const formValidation = {
    adminLogin: Yup.object({
        email: Yup.string()
            .email("Invalid email address")
            .required("Email is required!")
            .matches(/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i, "Invalid email address"),
        password: Yup.string()
            .min(5, "Minimum 5 characters")
            .max(16, "Maximum 16 characters")
            .required("Password is required!"),
    }),
    addParty: Yup.object({
        party_name: Yup.string()
            .required("Party name is required!"),
        address: Yup.string()
            .required("Address is required!"),
        email: Yup.string()
            .email("Invalid email address")
            .matches(/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i, "Invalid email address"),
    }),
    editParty: Yup.object({
        party_name: Yup.string()
            .required("Party name is required!"),
        address: Yup.string()
            .required("Address is required!"),
        email: Yup.string()
            .email("Invalid email address")
            .matches(/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i, "Invalid email address"),
    }),
    addProduct: Yup.object({
        product_no: Yup.string()
            .required("Party number is required!"),
        product_name: Yup.string()
            .required("Party name is required!"),
    }),
    editProduct: Yup.object({
        product_no: Yup.string()
            .required("Party number is required!"),
        product_name: Yup.string()
            .required("Party name is required!"),
    }),
    addExpense: Yup.object({
        expense_type_id: Yup.string()
            .required("Expense type is required!"),
        expense_date: Yup.string()
            .required("Expense date is required!")
            .matches(/^\d{4}-\d{2}-\d{2}$/, "Date must be in the format 'YYYY-MM-DD'"),
        expense_account_id: Yup.string()
            .required("Expense account is required!"),
    }),
    editExpense: Yup.object({
        expense_type_id: Yup.string()
            .required("Expense type is required!"),
        expense_date: Yup.string()
            .required("Expense date is required!")
            .matches(/^\d{4}-\d{2}-\d{2}$/, "Date must be in the format 'YYYY-MM-DD'"),
        expense_account_id: Yup.string()
            .required("Expense account is required!"),
    }),
}