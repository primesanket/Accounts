import React, { useEffect, useState } from "react";
import { useFormik } from "formik";
import { useDispatch } from "react-redux";
import { useNavigate } from 'react-router-dom'
import useTitle from "@/hooks/useTitle";
import FormButton from "@/components/Atoms/Form/FormButton/FormButton";
import FormInput from "@/components/Atoms/Form/FormInput/FormInput";
import { LOGIN_SUCCESS } from "@/store/actions/authActions";
import { SignIn } from "@/services/admin/auth";
import { mapLaravelErrorsToFormik } from "@/utils/errorMapper";
import { setTokenCookie } from "@/utils/cookies";
import { formValidation } from "@/utils/validations/formvalidations";


const Login: React.FC<{ meta: { title?: string } }> = ({ meta }) => {

    useTitle(meta?.title as string);

    const navigate = useNavigate()
    const dispatch = useDispatch();

    const [loading, setLoading] = useState(false);

    useEffect(() => {
        document.body.classList.add("login");
        return () => {
            document.body.classList.remove("login"); // Cleanup
        };
    }, [])

    const formik = useFormik({
        initialValues: {
            email: "primetech@gmail.com",
            password: "Primento$99093",
        },
        validationSchema: formValidation.adminLogin,
        onSubmit: async (values) => {
            setLoading(true);
            try {
                const response = await SignIn(values);
                if (response) {
                    setTokenCookie(response?.data.token);
                    dispatch({
                        type: LOGIN_SUCCESS,
                        payload: {
                            token: response?.data.token,
                            user: response?.data.user,
                            role: 'Admin',
                            authenticated: true
                        },
                    });
                    navigate('/admin');
                }
            } catch (error: any) {
                if (error && error.data) {
                    const { errors } = error.data;
                    if (error.status === 422) {
                        const formikErrors = mapLaravelErrorsToFormik(errors);
                        formik.setErrors(formikErrors);
                    }
                }
            } finally {
                setLoading(false);
            }
        }
    });

    return (
        <div className="container sm:px-10">
            <div className="block xl:grid grid-cols-2 gap-4">
                <div className="hidden xl:flex flex-col min-h-screen">
                    <a href="" className="-intro-x flex items-center pt-5">
                        <img alt="Logo" className="w-6" src="src/assets/images/logo.svg" />
                        <span className="text-white text-lg ml-3">Prime Accounts</span>
                    </a>
                    <div className="my-auto">
                        <img alt="Illustration" className="-intro-x w-1/2 -mt-16" src="/src/assets/images/illustration.svg" />
                        <div className="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            A few more clicks to <br /> sign in to your account.
                        </div>
                        <div className="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">
                            Manage all your accounts in one place
                        </div>
                    </div>
                </div>
                <div className="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div
                        className="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto"
                    >
                        <h2 className="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">Sign In</h2>
                        <div className="intro-x mt-2 text-slate-400 xl:hidden text-center">
                            A few more clicks to sign in to your account. Manage all your accounts in one place.
                        </div>
                        <form onSubmit={formik.handleSubmit}>
                            <div className="intro-x mt-8">
                                <FormInput
                                    name='email'
                                    id='email'
                                    type='text'
                                    placeholder='Email'
                                    value={formik.values.email}
                                    onChange={formik.handleChange}
                                    error={formik.errors.email ?? ''}
                                />
                                <FormInput
                                    className="mt-4"
                                    name='password'
                                    id='password'
                                    type='password'
                                    placeholder='Password'
                                    value={formik.values.password}
                                    onChange={formik.handleChange}
                                    error={formik.errors.password ?? ''}
                                />
                            </div>
                            <div className="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                                <div className="flex items-center mr-auto">
                                    <input id="remember-me" type="checkbox" className="form-check-input border mr-2" />
                                    <label className="cursor-pointer select-none" htmlFor="remember-me">Remember me</label>
                                </div>
                                <a href="">Forgot Password?</a>
                            </div>
                            <div className="intro-x mt-5 xl:mt-5 text-center xl:text-left">
                                <FormButton label="Login" size="sm" type="submit" loading={loading} />
                            </div>
                        </form>
                        <div className="intro-x mt-10 xl:mt-24 text-slate-600 dark:text-slate-500 text-center xl:text-left">
                            By signing up, you agree to our
                            <a className="text-primary dark:text-slate-200" href="">Terms and Conditions</a>
                            &
                            <a className="text-primary dark:text-slate-200" href="">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Login;
