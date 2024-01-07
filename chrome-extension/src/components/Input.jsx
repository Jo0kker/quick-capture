export default function Input({ label, name, value, onChange, type = "text", placeholder = "", tipsText = "" }) {
    return (
        <div className="w-full">
            <label htmlFor={label} className="block text-sm font-medium leading-6 text-gray-900">
                {label}
            </label>
            <div className="mt-2">
                <input
                    type={type}
                    name={name}
                    id={name}
                    value={value}
                    onChange={onChange}
                    className="block w-full rounded-md border-0 px-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    placeholder={placeholder}
                    aria-describedby={tipsText ? name + "-description" : ""}
                />
            </div>
            {tipsText && (
                <p className="mt-2 text-sm text-gray-500" id={name + "-description"}>
                    {tipsText}
                </p>
            )}
        </div>
    )
}
