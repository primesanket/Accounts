import React, { useState, useEffect, useCallback } from 'react';
import debounce from 'lodash/debounce';
import useTitle from '@/hooks/useTitle';
import { useNavigate } from 'react-router-dom';
import { Trash2, FilePenLine } from 'lucide-react';
import DataTable from '@/components/Atoms/DataTable/DataTable';
import { getPartyList, deleteParty } from '@/services/admin/party';
import { showConfirm, showSuccessToast, showErrorToast } from '@/services/sweetalert2Service';

interface Party {
    id: number;
    party_name: string;
    owner_name: string;
    reference_name: string;
    email: string;
    mobile_no: string;
    office_no: string;
    pan_no: string;
    gst_tin: string;
}

const columns = [
    { label: 'Party Name', key: 'party_name' },
    { label: 'Owner Name', key: 'owner_name' },
    { label: 'Reference Name', key: 'reference_name' },
    { label: 'Email', key: 'email' },
    { label: 'Mobile No', key: 'mobile_no' },
    { label: 'Office No', key: 'office_no' },
    { label: 'Pan No', key: 'pan_no' },
    { label: 'GSTTIN', key: 'gst_tin' },
];

const PartyList: React.FC = () => {
    const [data, setData] = useState<{ data: Party[]; current_page: number; last_page: number; total: number; from: number; to: number } | null>(null);
    const [search, setSearch] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState<string | null>(null); // Error state
    const navigate = useNavigate();

    useTitle("Party List");

    const fetchData = useCallback(async (page = 1, search = '') => {
        try {
            setIsLoading(true);
            setError(null);
            const response = await getPartyList(page, search);
            setData(response);
        } catch (error) {
            console.error('Fetch party data error:', error);
            setError('Failed to fetch data. Please try again later.');
        } finally {
            setIsLoading(false);
        }
    }, []);

    const debouncedSearch = useCallback(
        debounce((search) => {
            fetchData(1, search);
        }, 300),
        [fetchData]
    );

    useEffect(() => {
        fetchData();
    }, [fetchData]);

    const handleSearch = (event: React.ChangeEvent<HTMLInputElement>) => {
        const searchValue = event.target.value;
        setSearch(searchValue);
        debouncedSearch(searchValue);
    };

    const handlePageChange = (page: number) => {
        fetchData(page, search);
    };

    const editAction = (id: number) => {
        navigate(`/admin/masters/edit-party/${id}`);
    };

    const deleteAction = async (id: number, partyName: string) => {

        const confirmed = await showConfirm(
            "Are you sure?",
            `Are you sure you want to delete ${partyName}?`
        );
        if (confirmed) {
            try {
                await deleteParty(id);
                showSuccessToast("Deleted successfully!");
                setTimeout(() => {
                    fetchData(1, search);
                }, 300);
            } catch (error) {
                console.error("Delete party error:", error);
            }
        } else {
            showErrorToast("Deletion has been canceled.");
        }
    };

    if (error) {
        return <div className="error">{error}</div>;
    }

    // if (!data) return <div>Loading...</div>;

    return (
        <div>
            <div className="intro-y flex flex-col sm:flex-row items-center">
                <div className="search hidden sm:block mr-auto">
                    <input
                        type="text"
                        className="search__input form-control border-transparent"
                        placeholder="Search Party..."
                        value={search}
                        onChange={handleSearch}
                    />
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth="2"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        className="lucide lucide-search search__icon dark:text-slate-500"
                    >
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <div className="w-full sm:w-auto flex mt-4 sm:mt-0">
                    <button
                        className="btn btn-primary shadow-md mr-2"
                        onClick={() => navigate('/admin/masters/add-party')}
                    >
                        Add Party
                    </button>
                </div>
            </div>

            <div className="divider mt-5 mx-1"></div>
            <DataTable
                selectable={false}
                tableData={data?.data || []}
                currentPage={data?.current_page || 1}
                totalPages={data?.last_page || 1}
                columns={columns}
                total={data?.total || 0}
                from={data?.from || 0}
                to={data?.to || 0}
                loading={isLoading}
                onPageChange={handlePageChange}
                renderAction={(row: Party) => (
                    <span className="flex">
                        <button
                            onClick={() => editAction(row.id)}
                            className="btn btn-sm outline-none text-primary mr-2"
                        >
                            <FilePenLine size={16} className="mr-1" /> Edit
                        </button>
                        <button
                            onClick={() => deleteAction(row.id, row.party_name)}
                            className="btn btn-sm outline-none text-danger"
                        >
                            <Trash2 size={16} className="mr-1" /> Delete
                        </button>
                    </span>
                )}
            />
        </div>
    );
};

export default PartyList;