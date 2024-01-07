/* global chrome */
import React, {useEffect, useState} from 'react';
import ReactLoading from 'react-loading';
import Input from "./components/Input";
// import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
// import {faRotateLeft} from "@fortawesome/free-solid-svg-icons";

function App() {
    const [token, setToken] = useState('');
    const [userName, setUserName] = useState('');
    const [inputToken, setInputToken] = useState('')

    const [isLoadingOpenApi, setIsLoadingOpenApi] = useState(false);
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
        restoreFromStorage();
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
        setIsLoadingOpenApi(true)
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
        setIsLoadingOpenApi(false)
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

    const handleDataChange = (e, setter) => {
        setter(e.target.value);
        saveInStorage();
    }

    // Sauvegarder l'état lors de la fermeture de la popup
    const saveInStorage = () => {
        chrome.storage.local.set({
            name,
            email,
            phone,
            fonction,
            lieu,
            entreprise,
            showFormCreate,
            inputToken
        });
    };

    // Restaurer l'état lors de l'ouverture de la popup
    const restoreFromStorage = () => {
        chrome.storage.local.get(['name', 'email', 'phone', 'fonction', 'lieu', 'entreprise', 'showFormCreate', 'inputToken'], (data) => {
            if (data) {
                setName(data.name || '');
                setEmail(data.email || '');
                setPhone(data.phone || '');
                setFonction(data.fonction || '');
                setLieu(data.lieu || '');
                setEntreprise(data.entreprise || '');
                setShowFormCreate(data.showFormCreate || false);
                setInputToken(data.inputToken || '');
            }
        });
    };

    const resetForm = () => {
        setName('');
        setEmail('');
        setPhone('');
        setFonction('');
        setLieu('');
        setEntreprise('');
        setShowFormCreate(false);
        setResultStore('')
        setShowFormCreate(false)
        saveInStorage();
    }

    return (
        <div className={"p-2 flex flex-col items-center flex-nowrap"}>
            <h1 className="w-full flex flex-col items-center font-semibold mt-1">Quick Capture</h1>
            {!userName ? (
                <form
                    className="w-full flex flex-col items-center"
                    action={handleTokenSubmit}
                >
                    <Input
                        type="text"
                        value={inputToken}
                        onChange={(e) => setInputToken(e.target.value)}
                        placeholder="Enter your token"
                    />
                    <button
                        type="submit"
                        onClick={handleTokenSubmit}
                        className="mt-2 rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-500 hover:text-white"
                    >
                        Envoyer
                    </button>
                </form>
            ) : (
                <>
                    {!showFormCreate && (
                        <div className="flex flex-col items-center">
                            <p>Hello, {userName}!</p>
                            <button
                                type="button"
                                onClick={sendRequestToExportData}
                                className="rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-500 hover:text-white"
                            >
                                Nouvelle capture
                            </button>
                        </div>
                    )}
                </>
            )}

            {resultStore && (
                <p className="flex flex-col items-center">
                    {resultStore}
                </p>
            )}

            <div className='flex flex-col items-center'>
                {isLoadingOpenApi && (
                    <div className="spinner">
                        <ReactLoading type={"spinningBubbles"} color={"#000000"} height={'20%'} width={'20%'} />
                    </div>
                )}

                {showFormCreate && (
                    <form
                        className="w-full flex flex-col items-center"
                        action={handleStoreData}>
                        <button
                            type="button"
                            onClick={resetForm}
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512">
                                <path d="M290.7 57.4L57.4 290.7c-25 25-25 65.5 0 90.5l80 80c12 12 28.3 18.7 45.3 18.7H288h9.4H512c17.7 0 32-14.3 32-32s-14.3-32-32-32H387.9L518.6 285.3c25-25 25-65.5 0-90.5L381.3 57.4c-25-25-65.5-25-90.5 0zM297.4 416H288l-105.4 0-80-80L227.3 211.3 364.7 348.7 297.4 416z"/>
                            </svg>
                        </button>
                        <Input
                            type="text"
                            value={name}
                            onChange={(e) => handleDataChange(e, setName)}
                            placeholder="Enter your name"
                        />
                        <Input
                            type="text"
                            value={email}
                            onChange={(e) => handleDataChange(e, setEmail)}
                            placeholder="Enter your email"
                        />
                        <Input
                            type="text"
                            value={phone}
                            onChange={(e) => handleDataChange(e, setPhone)}
                            placeholder="Enter your phone"
                        />
                        <Input
                            type="text"
                            value={fonction}
                            onChange={(e) => handleDataChange(e, setFonction)}
                            placeholder="Enter your fonction"
                        />
                        <Input
                            type="text"
                            value={lieu}
                            onChange={(e) => handleDataChange(e, setLieu)}
                            placeholder="Enter your lieu"
                        />
                        <Input
                            type="text"
                            value={entreprise}
                            onChange={(e) => handleDataChange(e, setEntreprise)}
                            placeholder="Enter your entreprise"
                        />
                        <button
                            type="submit"
                            className="mt-3 rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-500 hover:text-white"
                            onClick={handleStoreData}>Enregistrer
                        </button>
                    </form>
                )}
            </div>

            <button
                type="button"
                onClick={resetStorage}
                className="mt-3 rounded bg-red-900 text-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:text-red-900"
            >
                Déconnexion
            </button>
          <p className="mt-3 text-xs text-gray-500">Version 0.0.8</p>
        </div>
    );
}

export default App;
