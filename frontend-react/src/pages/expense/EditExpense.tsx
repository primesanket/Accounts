import React, { useEffect, useState } from 'react';
import useTitle from '@/hooks/useTitle';
import PageTitle from '@/components/Atoms/UI/PageTitle';
import FormInput from '@/components/Atoms/Form/FormInput/FormInput';
import FormTextArea from '@/components/Atoms/Form/FormInput/FormTextArea';
import FormButton from '@/components/Atoms/Form/FormButton/FormButton';
import { useFormik } from 'formik';
import { mapLaravelErrorsToFormik } from '@/utils/errorMapper';
import { formValidation } from '@/utils/validations/formvalidations';
import { createExpenseType, getExpenseAccount, getExpenseType } from '@/services/admin/options';
import { useNavigate, useParams } from 'react-router-dom';
import { showSuccessToast } from '@/services/sweetalert2Service';
import FormSelect from '@/components/Atoms/Form/FormSelect/FormSelect';
import { Calendar, IndianRupee } from 'lucide-react';
import FormDatePicker from '@/components/Atoms/Form/FormDatePicker/FormDatePicker';
import { getExpense, updateExpense } from '@/services/admin/expense';
import { format } from 'date-fns';

interface Option {
  label: string;
  value: number;
}

const EditExpense: React.FC = () => {

  useTitle("Edit Expense");
  const { id } = useParams<{ id: string }>();

  const navigate = useNavigate()
  const [expenseTypes, setExpenseTypes] = useState<Option[]>([]);
  const [expenseAccounts, setExpenseAccounts] = useState<Option[]>([]);
  const [isDataLoading, setIsDataLoading] = useState(false);
  const [loading, setLoading] = useState(false);

  const formik = useFormik({
    initialValues: {
      expense_type_id: "",
      expense_date: null,
      amount: "",
      description: "",
      expense_account_id: "",
    },
    validationSchema: formValidation.editExpense,
    validateOnChange: false,
    onSubmit: async (values) => {
      setLoading(true);
      try {
        const response = await updateExpense(Number(id), values);
        if (response.status === 201 && response.data) {
          showSuccessToast(response.data.message);
          setTimeout(() => {
            navigate('/admin/expense');
          }, 500);
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

  useEffect(() => {
    fetchBootData();
  }, []);

  const fetchBootData = async () => {
    setIsDataLoading(true);
    try {
      const response = await getExpense(Number(id));
      const expenseTypes = await getExpenseType();
      const expenseAccounts = await getExpenseAccount();

      setExpenseTypes(
        expenseTypes.data.map((item: any) => ({
          value: item.id,
          label: item.text,
        }))
      );

      setExpenseAccounts(
        expenseAccounts.data.map((item: any) => ({
          value: item.id,
          label: item.name,
        }))
      );

      if (response.status === 201 && response.data) {
        formik.setValues(response.data.data);
        formik.setFieldValue('expense_date', format(new Date(response.data.data.expense_date), 'yyyy-MM-dd'));
        formik.setFieldValue('expense_type_id', Number(response.data.data.expense_type_id.id));
        formik.setFieldValue('expense_account_id', Number(response.data.data.expense_account_id.id));
      }

    } catch (error) {
      console.error('Error fetching data:', error);
    } finally {
      setIsDataLoading(false);
    }
  };

  const handleSelectChange = async (field: string, option: any) => {

    const value = option?.value;

    if (option && option.__isNew__ === true && field === 'expense_type_id') {
      try {
        const response = await createExpenseType({ type: value });

        if (response.status === 201 && response.data) {
          const newExpenseType = {
            label: response.data.data.type,
            value: response.data.data.id
          };

          formik.setFieldValue(field, response.data.data.id);
          expenseTypes.push(newExpenseType);
        }
      } catch (error) {
        console.error('Error creating expense type:', error);
      }
    } else {
      formik.setFieldError(field, '');
      formik.setFieldValue(field, value);
    }
  };

  const handleDateChange = (field: string, date: Date | null) => {
    formik.setFieldError(field, '');
    if (date) {
      const formattedDate = date.toISOString().split('T')[0];
      formik.setFieldValue(field, formattedDate);
    } else { formik.setFieldValue(field, null); }
  };

  return (
    <>
      <PageTitle title="Edit Expense" isBackButtonAllow={true} className="mb-5" />
      <div className="intro-y col-span-12 lg:col-span-6">
        <form onSubmit={formik.handleSubmit}>
          <div className="intro-y box">
            <div id="input" className="p-5">
              <div className="grid grid-cols-12 gap-4">
                <div className="col-span-3">
                  <FormSelect label='Type' name='expense_type_id' id='expense_type_id' isCreatable={true} value={expenseTypes.find((item) => Number(item.value) === Number(formik.values.expense_type_id))} required={true} options={expenseTypes} onChange={(option) =>
                    handleSelectChange("expense_type_id", option)
                  } error={formik.errors.expense_type_id ?? ''} />
                </div>
                <div className="col-span-3">
                  <FormDatePicker label='Date' icons={<Calendar size="18" />} name='expense_date' id='expense_date' placeholder="Select a date" selectedDate={formik.values.expense_date} required={true} onDateChange={(date) =>
                    handleDateChange("expense_date", date)
                  } error={formik.errors.expense_date ?? ''} />
                </div>
                <div className="col-span-3">
                  <FormInput label='Amount' icons={<IndianRupee size="18" />} name='amount' id='amount' type='number' step="any" value={formik.values.amount} onChange={formik.handleChange} error={formik.errors.amount ?? ''} />
                </div>
                <div className="col-span-3">
                  <FormSelect label='Account By' name='expense_account_id' id='expense_account_id' value={expenseAccounts.find((item) => Number(item.value) === Number(formik.values.expense_account_id))} required={true} options={expenseAccounts} onChange={(option) =>
                    handleSelectChange("expense_account_id", option)
                  } error={formik.errors.expense_account_id ?? ''} />
                </div>
                <div className="col-span-12">
                  <FormTextArea label='Description' name='description' id='description' rows={5} value={formik.values.description} onChange={formik.handleChange} error={formik.errors.description ?? ''} />
                </div>
              </div>
            </div>
          </div>
          <FormButton label="Submit" size="sm" type="submit" disabled={isDataLoading} loading={loading} className='mt-5' />
        </form >
      </div >
    </>
  )
}

export default EditExpense
