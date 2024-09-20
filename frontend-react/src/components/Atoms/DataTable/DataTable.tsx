import React, { useState, useMemo } from 'react';
import classNames from 'classnames';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import Loader from '../Loader/Loader';

interface Column {
    key: string;
    label: string;
    class?: string;
    formatter?: any
}

interface DataTableProps<T extends Record<string, any>> { // Add this constraint
    tableData: T[];
    columns: Column[];
    currentPage: number;
    totalPages: number;
    total: number;
    from: number;
    to: number;
    loading?: boolean;
    selectable?: boolean;
    onPageChange: (page: number) => void;
    onSelectionChange?: (selectedRows: T[]) => void;
    renderAction?: (row: T) => React.ReactNode;
}

const DataTable = <T,>({
    tableData = [],
    columns,
    currentPage,
    totalPages,
    total,
    from,
    to,
    loading = false,
    selectable = false,
    onPageChange,
    onSelectionChange,
    renderAction // Add this line
}: DataTableProps<T>) => {
    const [selectedRows, setSelectedRows] = useState<T[]>([]);

    const paginatedPages = useMemo(() => {
        const pages: number[] = [];
        let start = Math.max(currentPage - 2, 1);
        let end = Math.min(currentPage + 2, totalPages);

        if (totalPages <= 5) {
            start = 1;
            end = totalPages;
        } else {
            if (currentPage <= 3) {
                end = 5;
            } else if (currentPage + 2 >= totalPages) {
                start = totalPages - 4;
            }
        }

        for (let i = start; i <= end; i++) {
            pages.push(i);
        }
        return pages;
    }, [currentPage, totalPages]);

    const showFirstPage = useMemo(() => totalPages > 5 && currentPage > 4, [totalPages, currentPage]);
    const showLastPage = useMemo(() => totalPages > 5 && currentPage < totalPages - 3, [totalPages, currentPage]);
    const showEllipsisBefore = useMemo(() => showFirstPage && !paginatedPages.includes(2), [showFirstPage, paginatedPages]);
    const showEllipsisAfter = useMemo(() => showLastPage && !paginatedPages.includes(totalPages - 1), [showLastPage, paginatedPages]);

    const areAllSelected = useMemo(() => tableData.length > 0 && selectedRows.length === tableData.length, [tableData, selectedRows]);

    const handlePageChange = (page: number) => {
        onPageChange(page);
    };

    const handleNextPage = () => {
        if (currentPage < totalPages) {
            onPageChange(currentPage + 1);
        }
    };

    const handlePrevPage = () => {
        if (currentPage > 1) {
            onPageChange(currentPage - 1);
        }
    };

    const handleToggleSelectAll = () => {
        if (areAllSelected) {
            setSelectedRows([]);
            onSelectionChange?.([]);
        } else {
            setSelectedRows([...tableData]);
            onSelectionChange?.([...tableData]);
        }
    };

    const handleSelectRow = (item: T) => {
        const newSelectedRows = selectedRows.includes(item)
            ? selectedRows.filter(row => row !== item)
            : [...selectedRows, item];

        setSelectedRows(newSelectedRows);
        onSelectionChange?.(newSelectedRows);
    };

    return (
        <div className="overflow-x-auto lg:overflow-visible">
            <table className="table table-report">
                <thead>
                    <tr>
                        {selectable && (
                            <th>
                                <input
                                    type="checkbox"
                                    className="form-check-input"
                                    checked={areAllSelected}
                                    onChange={handleToggleSelectAll}
                                />
                            </th>
                        )}
                        {columns.map(column => (
                            <th key={column.key} className={`uppercase ${column.class}`}>
                                {column.label}
                            </th>
                        ))}
                        {renderAction && <th className='w-10 text-center uppercase'>Action</th>}
                    </tr>
                </thead>
                <tbody>
                    {loading ? (
                        <tr>
                            <td colSpan={columns.length + (selectable ? 1 : 0) + (renderAction ? 1 : 0)} className="transparent text-center">
                                <div className='w-full flex items-center justify-center'>
                                    <Loader stroke="rgb(0,0,0)" />
                                    <span>Loading...</span>
                                </div>
                            </td>
                        </tr>
                    ) : tableData.length === 0 ? (
                        <tr>
                            <td colSpan={columns.length + (selectable ? 1 : 0) + (renderAction ? 1 : 0)} className="text-center">
                                No data available
                            </td>
                        </tr>
                    ) : (
                        tableData.map((row, index) => (
                            <tr key={index}>
                                {selectable && (
                                    <td>
                                        <input
                                            type="checkbox"
                                            className="form-check-input"
                                            checked={selectedRows.includes(row)}
                                            onChange={() => handleSelectRow(row)}
                                        />
                                    </td>
                                )}
                                {columns.map(column => (
                                    <td key={column.key} className={`${column.class}`}>
                                        {column.formatter ? column.formatter(row[column.key as keyof T] as any) : row[column.key as keyof T] as any}
                                    </td>
                                ))}
                                {renderAction && (
                                    <td>{renderAction(row)}</td> // Render action for each row
                                )}
                            </tr>
                        ))
                    )}
                </tbody>
            </table>
            {totalPages > 1 && !loading && (
                <div className="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-between mt-3">
                    <div className="">
                        Showing {from} to {to} of {total} entries
                    </div>
                    <nav className="">
                        <ul className="pagination">
                            <li className="page-item">
                                <button
                                    className={classNames("page-link", { "disabled-link": currentPage === 1 })}
                                    onClick={handlePrevPage}
                                >
                                    <ChevronLeft size={18} />
                                </button>
                            </li>
                            {showFirstPage && (
                                <li className="page-item">
                                    <button
                                        className={classNames("page-link", { active: currentPage === 1 })}
                                        onClick={() => handlePageChange(1)}
                                    >
                                        1
                                    </button>
                                </li>
                            )}
                            {showEllipsisBefore && (
                                <li className="page-item">
                                    <button className="page-link">...</button>
                                </li>
                            )}
                            {paginatedPages.map(page => (
                                <li className={classNames("page-item", { active: page === currentPage })} key={page}>
                                    <button
                                        className="page-link"
                                        onClick={() => handlePageChange(page)}
                                    >
                                        {page}
                                    </button>
                                </li>
                            ))}
                            {showEllipsisAfter && (
                                <li className="page-item">
                                    <button className="page-link">...</button>
                                </li>
                            )}
                            {showLastPage && (
                                <li className={classNames("page-item", { active: currentPage === totalPages })}>
                                    <button
                                        className="page-link"
                                        onClick={() => handlePageChange(totalPages)}
                                    >
                                        {totalPages}
                                    </button>
                                </li>
                            )}
                            <li className="page-item">
                                <button
                                    className={classNames("page-link", { "disabled-link": currentPage === totalPages })}
                                    onClick={handleNextPage}
                                >
                                    <ChevronRight size={18} />
                                </button>
                            </li>
                        </ul>
                    </nav>
                </div>
            )}
        </div>
    );
};

export default DataTable;
