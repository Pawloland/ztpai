import styles from './InserForm.module.css';
import {FormEvent} from 'react';

export interface InputType {
    type: 'text' | 'datetime-local' | 'time' | 'textarea' | 'select' | 'file';
    required: boolean;
    options?: { key: string; value: string }[];
    default_option?: number;
}

function InsertForm({
                        submit_text,
                        labels,
                        form_labels,
                        data,
                        onSubmit,
                    }: {
    submit_text: string;
    labels: string[];
    form_labels: string[];
    data: InputType[];
    onSubmit: (event: FormEvent<HTMLFormElement>) => void;
}) {

    return (
        <div className={styles._}>
            <form onSubmit={onSubmit}>
                <table>
                    <tbody>
                    {data.map((row, rowIndex) => (
                        <tr key={rowIndex}>
                            <th>
                                <label htmlFor={form_labels[rowIndex]}>{labels[rowIndex]}</label>
                            </th>
                            <td>
                                {(() => {
                                    switch (row.type) {
                                        case 'text':
                                        case 'datetime-local':
                                            return (
                                                <input
                                                    type={row.type}
                                                    name={form_labels[rowIndex]}
                                                    required={row.required}
                                                />
                                            );
                                        case 'time':
                                            return (
                                                <input
                                                    type={row.type}
                                                    name={form_labels[rowIndex]}
                                                    required={row.required}
                                                    step={1}
                                                />
                                            );
                                        case 'file':
                                            return (
                                                <input
                                                    type={row.type}
                                                    name={form_labels[rowIndex]}
                                                    required={row.required}
                                                    accept={"img/png , img/jpeg, img/jpg , img/gif"}
                                                />
                                            );
                                        case 'textarea':
                                            return (
                                                <textarea
                                                    name={form_labels[rowIndex]}
                                                    required={row.required}
                                                />
                                            );
                                        case 'select':
                                            return (
                                                <select
                                                    name={form_labels[rowIndex]}
                                                    required={row.required}
                                                    defaultValue={row.options ? row.options[row.default_option || 0]?.value ?? '' : ''}
                                                >
                                                    {row.options?.map((option) => {
                                                        return (
                                                            <option key={option.key} value={option.value}>
                                                                {option.key}
                                                            </option>
                                                        );
                                                    })}
                                                </select>
                                            );
                                        default:
                                            return "NOT SUPPORTED INPUT TYPE " + row.type;
                                    }
                                })()}
                            </td>
                        </tr>
                    ))}
                    <tr>
                        <td colSpan={2}>
                            <input type="submit" value={submit_text}/>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </form>
        </div>

    );
}

export default InsertForm