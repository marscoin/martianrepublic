import Rodal from 'rodal';
import 'rodal/lib/rodal.css';
import NewWalletForm from '../new_wallet_form';


const CreateModal = ({modalVisible, setModalVisible, animation, loginSuccess, setLoginSuccess}) =>
{


    const handleModal = () =>
    {
        setModalVisible(!modalVisible)
    }



    return (

        <>
        <Rodal visible={modalVisible} onClose={handleModal} animation={animation} height={"675"} width={"800"} showMask>

            <NewWalletForm modalVisible={modalVisible} loginSuccess={loginSuccess} setLoginSuccess={setLoginSuccess}/>

        </Rodal>

        </>



    )
}

export default CreateModal