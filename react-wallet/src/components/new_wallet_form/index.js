import TextField from '@mui/material/TextField'
import styled from 'styled-components'
import { Button, Checkbox, IconButton, InputLabel, Typography } from '@mui/material';
import NiceInputPassword from 'react-nice-input-password';
import { useEffect, useState } from 'react';
import { FiLock } from 'react-icons/fi'
import 'react-nice-input-password/dist/react-nice-input-password.css';
import { FaCheckCircle } from 'react-icons/fa'
import { IoIosWarning } from 'react-icons/io'
import { MdContentCopy } from 'react-icons/md'
// var Mnemonic = require('bitcore-mnemonic')



// Mnemonic import 
import Mnemonic from 'bitcore-mnemonic-litecoin'
// import bip39 from 'bip39'
const bip39 = require('bip39')
// const constants = require('bip44') 


//Styled components
const Form = styled.div`
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
    align-items: center;
    height: 100%
`
const MnemonicContainer = styled.div`
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
`
const MnemonicText = styled.p`
    background-color: rgb(40, 57, 71);
    color: white;
    box-shadow: 1px 1px 10px 1px #00000040;
    border-radius: 4px;
    padding: 12px;
    font-weight: 700;
    margin: 10px
`


const InputFields = styled.div`
    display: flex;
    flex-direction: column; 
    align-items: center;
    justify-content: space-evenly;
    height: 55%;

`

const Message = styled.div`
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-grow: grow ;

`
const Terms = styled.div`
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 8px;


`



// mui styled
const SubmitButton = styled(Button)(({ theme }) => ({
    backgroundColor: "rgb(40, 57, 71)",
    color: "white",
    padding: 10,
    borderRadius: "10px",
    height: "50px",
    margin: "10px",
    fontWeight: 800,
    width: "50%",
    '&:hover':
    {
        backgroundColor: 'rgb(40, 57, 71, .6)',
        boxShadow: "1px 1px 20px #00000090",
        border: "solid",
        borderWidth: "4px",
        borderColor: "rgb(40, 57, 71)",
    },
    '@media (max-width: 800px)': {
        width: '90%'
    }
}))



// Start New Wallet Form
const NewWalletForm = ({ modalVisible, loginSuccess, setLoginSuccess }) => {
    const [errorMessage, setErrorMessage] = useState('')
    const [terms, setTerms] = useState(false)
    const [pass, setPass] = useState({ "password": null })
    const [color, setColor] = useState('#000')
    const [match, setMatch] = useState(false)
    const [confirmPass, setConfirmPass] = useState({ "confirm": null })
    const [hints, setHints] = useState(0)
    const [everythingOk, setEverythingOk] = useState(false)
    const [icon, setIcon] = useState()
    const [mnemonic, setMnemonic] = useState()

    const handlePass = (e) => {
        setPass({ "password": e.value })
    }

    const handleConfirmPass = async (e) => {
        setConfirmPass({ "confirm": e.value })
    }

    const handleSubmit = (e) => {
        if (terms && match && hints == 4) {
            setIcon(<FaCheckCircle color={"#43ad1f"} size={30} />)
            setEverythingOk(true)
            setErrorMessage("Success!")

            // Pass state to main layout with WALLET_SUCCESS
            setTimeout(() => {
                setLoginSuccess(true)
            }, 1000)


        }
        else {
            setIcon(<IoIosWarning color={'#de1b4f'} size={30} />)
            setEverythingOk(false)
            setErrorMessage("1 Or More Requirements Missing")
        }
    }

    useEffect(() => {
        if (pass.password === null || confirmPass.confirm === null || confirmPass.confirm === "" || pass.password === "") {
            setColor("#000")
            return
        }

        if (pass.password === confirmPass.confirm) {
            setColor("#43ad1f")
            setMatch(true)
            if (hints == 4 && match) {
                console.log("Everyhting is perfecto")
            }
        } else {
            setMatch(false)
            setColor('#de1b4f')
        }

    }, [confirmPass])

    useEffect(() => {
        if (pass.password === null || confirmPass.confirm === null || confirmPass.confirm === "" || pass.password === "") {
            setColor("#000")
            return
        }

        if (pass.password === confirmPass.confirm) {
            setMatch(true)
            setColor('#43ad1f')
        }
        else {
            setMatch(false)
            setColor('#de1b4f')
        }

    }, [pass])

    useEffect(() => {
        if (!modalVisible) {
            setIcon()
            setColor('#000')
            setMatch(false)
            setPass({ "password": "" })
            setConfirmPass({ "confirm": "" })
            setTerms(false)
            setErrorMessage()
            setHints(0)
            setEverythingOk(false)
            return
        }
        setMnemonic(genMnemonic())



    }, [modalVisible])

    // ======================================================================================
    // ======================================================================================
    // ========================= CONSTANTS FOR SECURITY BAR =================================

    const levelBarCss = (level) => ({
        height: "10px",
        width: level > 0 ? `${(100 / 4) * level}%` : "100%",
        marginTop: 15,
        marginBottom: 15,
        transition: "width 0.5s ease",
        backgroundColor: ["#EFEFEF", "#de1b4f", "#fa9623", "#fce408", "#43ad1f"][level],
        borderRadius: 100
    });

    const CustomLevelBar = (levels) => {
        setHints(levels)

        return <div style={levelBarCss(hints)} />
    };


    const securityLevels = [
        {
            descriptionLabel: <Typography>1 Number</Typography>,
            validator: /.*[0-9].*/
        },
        {
            descriptionLabel: <Typography>1 Lowercase Letter</Typography>,
            validator: /.*[a-z].*/
        },
        {
            descriptionLabel: <Typography>1 Uppercase Letter</Typography>,
            validator: /.*[A-Z].*/
        },
        {
            descriptionLabel: <Typography>8 Or More Characters</Typography>,
            validator: /^.{8,}$/
        }
    ];

    // ======================================================================================
    // ======================================================================================
    // ======================================================================================


    // let generateMnemonic = async () => {
    //     try {
    //         return await bip39.generateMnemonic(256) // default to 128
    //     } catch (e) {
    //         return false
    //     }
    // }

    const genMnemonic = () => {
        // var code = new Mnemonic(Mnemonic.Words.ENGLISH);
        // var xpriv = code.toHDPrivateKey();

        // return code.toString()
        const mnem = bip39.generateMnemonic()
        console.log("mnem: ", mnem)
        return mnem

    }

    return (
        <Form>
            <MnemonicContainer>
                <h1>Mnemonic</h1>
                <span><strong>Note:</strong> Write down this phrase! If lost you cannot recover your wallet.</span>
                <MnemonicText>{mnemonic}
                    <IconButton size={"large"} color={"info"} onClick={() => { navigator.clipboard.writeText(mnemonic) }}>
                        <MdContentCopy size={25} color={'white'} />

                    </IconButton>
                </MnemonicText>


            </MnemonicContainer>

            <br />


            <InputFields>
                <h1>Setup Password</h1>

                <NiceInputPassword
                    name="password"
                    placeholder="Password"
                    value={pass.password}
                    showSecurityLevelDescription={true}
                    showSecurityLevelBar={true}
                    LabelComponent={InputLabel}
                    InputComponent={TextField}
                    onChange={handlePass}
                    renderLevelBarComponent={CustomLevelBar}
                    securityLevels={securityLevels}
                    key={mnemonic}
                    InputComponentProps={{
                        variant: 'standard',
                        InputProps: {
                            endAdornment: <FiLock size={30} color={color} />,
                        }
                    }}
                    securityLevels={securityLevels}


                />
                <NiceInputPassword
                    name="password"
                    placeholder="Re-Type Password"
                    value={confirmPass.confirm}
                    LabelComponent={InputLabel}
                    InputComponent={TextField}
                    onChange={handleConfirmPass}

                    InputComponentProps={{
                        variant: 'standard',
                        InputProps: {
                            endAdornment: <FiLock size={30} color={color} />,
                        }
                    }}
                    securityLevels={securityLevels}

                />
                <Terms>
                    <Checkbox checked={terms} onChange={() => setTerms(!terms)} /> <span>I Agree To <a href="#">Terms</a> and Conditions</span>

                </Terms>

            </InputFields>

            <Message>
                {icon}  <span style={{ color: everythingOk ? "#43ad1f" : "#de1b4f" }} >{errorMessage}</span>

            </Message>


            <SubmitButton onClick={handleSubmit}>Submit</SubmitButton>

        </Form>


    )
}

export default NewWalletForm