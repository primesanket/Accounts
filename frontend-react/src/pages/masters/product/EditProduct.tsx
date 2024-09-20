import React, { useState, useEffect } from 'react';
import useTitle from '@/hooks/useTitle';
import PageTitle from '@/components/Atoms/UI/PageTitle';
import FormInput from '@/components/Atoms/Form/FormInput/FormInput';
import FormTextArea from '@/components/Atoms/Form/FormInput/FormTextArea';
import FormButton from '@/components/Atoms/Form/FormButton/FormButton';
import { useFormik } from 'formik';
import { mapLaravelErrorsToFormik } from '@/utils/errorMapper';
import { formValidation } from '@/utils/validations/formvalidations';
import { getProduct, updateProduct } from '@/services/admin/product';
import { useNavigate, useParams } from 'react-router-dom';
import { showSuccessToast } from '@/services/sweetalert2Service';

const EditProduct: React.FC = () => {
  useTitle('Edit Product');
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);

  const formik = useFormik({
    initialValues: {
      product_no: "",
      product_name: "",
      company_name: "",
      gst: "",
      hsn_sac: "",
      opening_stock: "",
      remark: "",
    },
    validationSchema: formValidation.editProduct,
    onSubmit: async (values) => {
      setLoading(true);
      try {
        const response = await updateProduct(Number(id), values);
        if (response.status === 201 && response.data) {
          showSuccessToast(response.data.message);
          setTimeout(() => {
            navigate('/admin/masters/product');
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
    const fetchPartyData = async () => {
      try {
        const response = await getProduct(Number(id));
        if (response.status === 201 && response.data) {
          formik.setValues(response.data.data);
        }
      } catch (error) {
        console.error('Error fetching product data:', error);
      }
    };
    fetchPartyData();
  }, [id]);

  return (
    <>
      <PageTitle title="Edit Product" isBackButtonAllow={true} className="mb-5" />
      <div className="intro-y col-span-12 lg:col-span-6">
        <form onSubmit={formik.handleSubmit}>
          <div className="intro-y box">
            <div id="input" className="p-5">
              <div className="grid grid-cols-12 gap-4">
                <div className="col-span-4">
                  <FormInput label='Product No' name='product_no' id='product_no' type='text' required={true} value={formik.values.product_no} onChange={formik.handleChange} error={formik.errors.product_no ?? ''} />
                </div>
                <div className="col-span-4">
                  <FormInput label='Product Name' name='product_name' id='product_name' type='text' required={true} value={formik.values.product_name} onChange={formik.handleChange} error={formik.errors.product_name ?? ''} />
                </div>
                <div className="col-span-4">
                  <FormInput label='Company Name' name='company_name' id='company_name' type='text' value={formik.values.company_name} onChange={formik.handleChange} error={formik.errors.company_name ?? ''} />
                </div>
                <div className="col-span-4">
                  <FormInput label='GST (%)' name='gst' id='gst' type='text' value={formik.values.gst} onChange={formik.handleChange} error={formik.errors.gst ?? ''} />
                </div>
                <div className="col-span-4">
                  <FormInput label='HSN/SAC' name='hsn_sac' id='hsn_sac' type='text' value={formik.values.hsn_sac} onChange={formik.handleChange} error={formik.errors.hsn_sac ?? ''} />
                </div>
                <div className="col-span-4">
                  <FormInput label='Opening Stock' name='opening_stock' id='opening_stock' type='text' value={formik.values.opening_stock} onChange={formik.handleChange} error={formik.errors.opening_stock ?? ''} />
                </div>
                <div className="col-span-12">
                  <FormTextArea label='Remark' name='remark' id='remark' value={formik.values.remark} rows={5} onChange={formik.handleChange} error={formik.errors.remark ?? ''} />
                </div>
              </div>
            </div>
          </div>
          <FormButton label="Update" size="sm" type="submit" loading={loading} className='mt-5' />
        </form>
      </div>
    </>
  );
};

export default EditProduct;
