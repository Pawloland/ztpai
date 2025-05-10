import styles from "./List.module.css";
import {AllowedIconClass} from "../icon/Icon.tsx";
import BaseIcon from "../baseIcon/BaseIcon.tsx";


type ToStringable = {
    toString(): string;
};

function List({
                  title,
                  header,
                  data,
                  onColumnValueClick,
              }: {
    title: string;
    header: string[];
    data: ToStringable[][];
    onColumnValueClick?: (valueFromColumnKey: ToStringable[]) => void;
}) {
    if (data.some((row) => row.length !== header.length)) {
        throw new Error("Each row in data must have the same number of elements as the header.");
    }

    return (
        <div className={styles._}>
            <p>{title}</p>
            <form>
                <table>
                    <thead>
                    <tr>
                        {header.map((col, i) => (
                            <th key={i}>{col}</th>
                        ))}
                        {onColumnValueClick && (
                            <th></th>
                        )}
                    </tr>
                    </thead>
                    <tbody>
                    {data.map((row, rowIndex) => (
                        <tr key={rowIndex}>
                            {row.map((value, colIndex) => (
                                <td key={colIndex}>{value.toString()}</td>
                            ))}
                            {
                                onColumnValueClick && (
                                    <td>
                                        <BaseIcon
                                            iconClass={AllowedIconClass.Bin}
                                            onClick={() => {
                                                onColumnValueClick(row);
                                            }}
                                        />
                                    </td>
                                )}
                        </tr>
                    ))}
                    </tbody>
                </table>
            </form>
        </div>
    );
}


export default List;