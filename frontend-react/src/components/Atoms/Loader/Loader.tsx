import React from 'react';

interface LoaderProps {
    width?: number
    height?: number
    stroke?: string
    className?: string
}

const Loader: React.FC<LoaderProps> = ({
    width = 25,
    height = 25,
    stroke = "rgb(255, 255, 255)",
    className = "w-4 h-4 mr-2",
}) => {

    return (
        <svg width={width} height={height} viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke={stroke} className={className}>
            <g fill="none" fillRule="evenodd">
                <g transform="translate(1 1)" strokeWidth="4">
                    <circle strokeOpacity=".5" cx="18" cy="18" r="18"></circle>
                    <path d="M36 18c0-9.94-8.06-18-18-18">
                        <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform>
                    </path>
                </g>
            </g>
        </svg>
    );
};

export default Loader;