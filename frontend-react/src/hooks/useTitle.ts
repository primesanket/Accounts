import { useEffect } from "react";

function useTitle(title: string): void {
    useEffect(() => {
        document.title = title || "Default Title";
    }, [title]);
}

export default useTitle;
