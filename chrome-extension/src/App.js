/* global chrome */
import React, {useEffect, useState} from 'react';

function App() {
    const [token, setToken] = useState('');
    const [userName, setUserName] = useState('');
    const [inputToken, setInputToken] = useState('')

    const [showFormCreate, setShowFormCreate] = useState(false);
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [fonction, setFonction] = useState('');
    const [lieu, setLieu] = useState('');
    const [entreprise, setEntreprise] = useState('')
    const [resultStore, setResultStore] = useState('')

    useEffect(() => {
        chrome.storage.local.get(['token', 'username'], function(result) {
            setToken(result.token);
            setUserName(result.username);
        });
    }, []);

    const handleTokenSubmit = async (e) => {
        e.preventDefault();
        console.log('handleTokenSubmit')
        const response = await fetch('http://localhost/api/user', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                Authorization: `Bearer ${inputToken}`,
            },
        });
        const data = await response.json();
        setUserName(data.name);
        chrome.storage.local.set({ token: inputToken, username: data.name }, () => {
            setInputToken('')
        });
    };

    const resetStorage = () => {
        chrome.storage.local.clear(() => {
            const error = chrome.runtime.lastError;
            if (error) {
                console.error(error);
            } else {
                setUserName('')
                setToken('')
                setInputToken('')
            }
        });
    }

    const sendRequestToExportData = () => {
        chrome.tabs.query({active: true, currentWindow: true}, function(tabs) {
            chrome.tabs.sendMessage(tabs[0].id, {action: "fetchLinkedInData"}, function(response) {
                if (response?.content) {
                    sendDataToAPI(response.content)
                }
                setShowFormCreate(true)
            });
        });
    }

    const sendDataToAPI = async (content) => {
        const response = await fetch('http://localhost/api/openapi/linkedin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({content}),
        });
        const res = await response.json();
        setName(res.data.lastname + ' ' + res.data.firstname)
        setEmail(res.data.email)
        setPhone(res.data.phone)
        setFonction(res.data.poste)
        setLieu(res.data.lieu)
        setEntreprise(res.data.entreprise)
    }

    const handleStoreData = async (e) => {
        e.preventDefault();
        const response = await fetch('http://localhost/api/captureLinkedin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({
                name: name,
                email: email,
                phone: phone,
                function: fonction,
                lieu: lieu,
                entreprise: entreprise
            }),
        });
        setResultStore('Enregistrement effectué')
        setName('')
        setEmail('')
        setPhone('')
        setFonction('')
        setLieu('')
        setEntreprise('')
        setShowFormCreate(false)
    }

    return (
        <div>
            <h1>Quick Capture</h1>
            {!userName ? (
                <form action={handleTokenSubmit}>
                    <input
                        type="text"
                        value={inputToken}
                        onChange={(e) => setInputToken(e.target.value)}
                        placeholder="Enter your token"
                    />
                    <button onClick={handleTokenSubmit}>Submit</button>
                </form>
            ) : (
                <>
                    <p>Welcome, {userName}!</p>
                    <button onClick={sendRequestToExportData}>
                        Export Datas
                    </button>
                </>
            )}

            {resultStore && (
                <p>{resultStore}</p>
            )}

            {showFormCreate && (
                <form action={handleStoreData}>
                    <input
                        type="text"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                        placeholder="Enter your name"
                    />
                    <input
                        type="text"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        placeholder="Enter your email"
                    />
                    <input
                        type="text"
                        value={phone}
                        onChange={(e) => setPhone(e.target.value)}
                        placeholder="Enter your phone"
                    />
                    <input
                        type="text"
                        value={fonction}
                        onChange={(e) => setFonction(e.target.value)}
                        placeholder="Enter your fonction"
                    />
                    <input
                        type="text"
                        value={lieu}
                        onChange={(e) => setLieu(e.target.value)}
                        placeholder="Enter your lieu"
                    />
                    <input
                        type="text"
                        value={entreprise}
                        onChange={(e) => setEntreprise(e.target.value)}
                        placeholder="Enter your entreprise"
                    />
                    <button onClick={handleStoreData}>Submit</button>
                </form>
            )}

            <button onClick={resetStorage}>Reset Storage</button>
        </div>
    );
}

export default App;
