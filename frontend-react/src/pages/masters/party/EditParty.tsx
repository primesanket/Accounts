import React, { useState, useEffect } from 'react';
import useTitle from '@/hooks/useTitle';
import PageTitle from '@/components/Atoms/UI/PageTitle';
import FormInput from '@/components/Atoms/Form/FormInput/FormInput';
import FormTextArea from '@/components/Atoms/Form/FormInput/FormTextArea';
import FormButton from '@/components/Atoms/Form/FormButton/FormButton';
import { useFormik } from 'formik';
import { mapLaravelErrorsToFormik } from '@/utils/errorMapper';
import { formValidation } from '@/utils/validations/formvalidations';
import { getParty, updateParty } from '@/services/admin/party';
import { useNavigate, useParams } from 'react-router-dom';
import { showSuccessToast } from '@/services/sweetalert2Service';

const EditParty: React.FC = () => {
  useTitle('Edit Party');
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);

  const formik = useFormik({
    initialValues: {
      party_name: "",
      owner_name: "",
      reference_name: "",
      email: "",
      mobile_no: "",
      office_no: "",
      address: "",
      state: "",
      state_code: "",
      pan_no: "",
      gst_tin: ""
    },
    validationSchema: formValidation.editParty,
    onSubmit: async (values) => {
      setLoading(true);
      try {
        const response = await updateParty(Number(id), values);
        if (response.status === 201 && response.data) {
          showSuccessToast(response.data.message);
          setTimeout(() => {
            navigate('/admin/masters/party');
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
        const response = await getParty(Number(id));
        if (response.status === 201 && response.data) {
          formik.setValues(response.data.data);
        }
      } catch (error) {
        console.error('Error fetching party data:', error);
      }
    };
    fetchPartyData();
  }, [id]);

  return (
    <>
      <PageTitle title="Edit Party" isBackButtonAllow={true} className="mb-5" />
      <div className="intro-y col-span-12 lg:col-span-6">
        <form onSubmit={formik.handleSubmit}>
          <div className="intro-y box">
            <div id="input" className="p-5">
              <div className="grid grid-cols-12 gap-4">
                <div className="col-span-4">
                  <FormInput label='Party Name' name='party_name' id='party_name' type='text' required={true} value={formik.values.party_name} onChange={formik.handleChange} error={formik.errors.party_name ?? ''} />
                </div>
                <div className="col-span-4">
                  <FormInput label='Owner Name' name='owner_name' id='owner_name' type='text' value={formik.values.owner_name} onChange={formik.handleChange} error={formik.errors.owner_name ?? ''} />
                </div>
                <div className="col-span-4">
                  <FormInput label='Reference Name' name='reference_name' id='reference_name' type='text' value={formik.values.reference_name} onChange={formik.handleChange} error={formik.errors.reference_name ?? ''} />
                </div>
                <div className="col-span-4">
                  <FormInput label='Email' name='email' id='email' type='text' value={formik.values.email} onChange={formik.handleChange} error={formik.errors.email ?? ''} />
                </div>
                <div className="col-span-4">
                  <FormInput label='Mobile No' name='mobile_no' id='mobile_no' type='text' value={formik.values.mobile_no} onChange={formik.handleChange} error={formik.errors.mobile_no ?? ''} />
                </div>
                <div className="col-span-4">
                  <FormInput label='Office No' name='office_no' id='office_no' type='text' value={formik.values.office_no} onChange={formik.handleChange} error={formik.errors.office_no ?? ''} />
                </div>
                <div className="col-span-12">
                  <FormTextArea label='Address' name='address' id='address' value={formik.values.address} required={true} rows={5} onChange={formik.handleChange} error={formik.errors.address ?? ''} />
                </div>
                <div className="lg:col-span-3">
                  <FormInput label='State' name='state' id='state' type='text' value={formik.values.state} onChange={formik.handleChange} error={formik.errors.state ?? ''} />
                </div>
                <div className="lg:col-span-3">
                  <FormInput label='State Code' name='state_code' id='state_code' type='text' value={formik.values.state_code} onChange={formik.handleChange} error={formik.errors.state_code ?? ''} />
                </div>
                <div className="col-span-3">
                  <FormInput label='Pan No' name='pan_no' id='pan_no' type='text' value={formik.values.pan_no} onChange={formik.handleChange} error={formik.errors.pan_no ?? ''} />
                </div>
                <div className="lg:col-span-3">
                  <FormInput label='GSTTIN' name='gst_tin' id='gst_tin' type='text' value={formik.values.gst_tin} onChange={formik.handleChange} error={formik.errors.gst_tin ?? ''} />
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

export default EditParty;
