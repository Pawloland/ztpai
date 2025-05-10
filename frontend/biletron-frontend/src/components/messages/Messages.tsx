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

            setTimeout(() => {
                setFadingOutIds(ids => [...ids, msg.id]);

                // Wait for fade-out before actually removing
                setTimeout(() => {
                    setMessages(prev => prev.filter(m => m.id !== msg.id));
                    setFadingOutIds(ids => ids.filter(id => id !== msg.id));
                }, 300); // match animation duration
            }, timeout);
        };

        listeners.push(listener);
        return () => {
            listeners = listeners.filter(l => l !== listener);
        };
    }, []);


    return (
        <div className={styles.messages}>
            {messages.map(msg => (
                <div
                    key={msg.id}
                    className={`${styles.message} ${fadingOutIds.includes(msg.id) ? styles['fade-out'] : ''}`}
                >
                    {msg.text}
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
