import styles from './Messages.module.css';
import {useEffect, useState} from 'react';

interface Message {
    id: number;
    text: string;
}

let listeners: ((msg: Message, timeout: number) => void)[] = [];
let nextId = 0;

function Messages() {
    const [messages, setMessages] = useState<Message[]>([]);
    const [fadingOutIds, setFadingOutIds] = useState<number[]>([]);

    useEffect(() => {
        const listener = (msg: Message, timeout: number) => {
            setMessages(prev => [...prev, msg]);

            // Auto timeout
            const autoDismiss = () => dismissMessage(msg.id);
            setTimeout(autoDismiss, timeout);
        };

        listeners.push(listener);
        return () => {
            listeners = listeners.filter(l => l !== listener);
        };
    }, []);

    const dismissMessage = (id: number) => {
        // Prevent duplicate fade-outs
        setFadingOutIds(ids => {
            if (ids.includes(id)) return ids;
            return [...ids, id];
        });

        // After fade-out, remove the message
        setTimeout(() => {
            setMessages(prev => prev.filter(m => m.id !== id));
            setFadingOutIds(ids => ids.filter(fid => fid !== id));
        }, 300); // Match fade-out animation duration
    };

    return (
        <div className={styles.messages}>
            {messages.map(msg => (
                <div
                    key={msg.id}
                    className={`${styles.message} ${fadingOutIds.includes(msg.id) ? styles['fade-out'] : ''}`}
                >
                    <span>{msg.text}</span>
                    <button
                        onClick={() => dismissMessage(msg.id)}
                    >
                        ×
                    </button>
                </div>
            ))}
        </div>
    );
}

Messages.showMessage = (text: string, timeout = 3000) => {
    console.log(text)
    const id = nextId++;
    const message: Message = {id, text};
    listeners.forEach(l => l(message, timeout));
};

export default Messages;
