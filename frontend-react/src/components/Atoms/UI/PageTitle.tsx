import { Undo2 } from "lucide-react";
import { useNavigate } from "react-router-dom";

interface PageTitleProps {
    className?: string;
    title?: string;
    isBackButtonAllow?: boolean;
}

const PageTitle: React.FC<PageTitleProps> = ({ className, isBackButtonAllow = false, title = document.title }) => {
    const navigate = useNavigate();
    const routeMetaTitle = title;

    const goBack = () => {
        navigate(-1);
    };

    return (
        <div className={`intro-y flex flex-col sm:flex-row items-center justify-between ${className}`}>
            <h2 className="mr-5 truncate text-lg font-medium">{routeMetaTitle}</h2>
            {
                isBackButtonAllow && (
                    <button className="btn btn-sm btn-primary shadow-md" onClick={goBack}>
                        <Undo2 />
                    </button>
                )
            }
        </div >
    );
};

export default PageTitle;
