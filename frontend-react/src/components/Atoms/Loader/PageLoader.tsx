import React from 'react'

const PageLoader: React.FC = (): JSX.Element => {
    return (
        <div className='h-screen flex justify-center items-center'>
            <svg viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" className="w-16 h-16 box px-4">
                <defs>
                    <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
                        <stop stopColor="rgb(30, 41, 59)" stopOpacity="0" offset="0%"></stop>
                        <stop stopColor="rgb(30, 41, 59)" stopOpacity=".631" offset="63.146%"></stop>
                        <stop stopColor="rgb(30, 41, 59)" offset="100%"></stop>
                    </linearGradient>
                </defs>
                <g fill="none" fillRule="evenodd">
                    <g transform="translate(1 1)">
                        <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" strokeWidth="3">
                            <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                        </path>
                        <circle fill="rgb(30, 41, 59)" cx="36" cy="18" r="1">
                            <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                        </circle>
                    </g>
                </g>
            </svg>
        </div>
    )
}

export default PageLoader