import * as React from 'react';
import { useEffect, useState } from 'react';

import { styled, createTheme, ThemeProvider } from '@mui/material/styles';
import CssBaseline from '@mui/material/CssBaseline';
import Box from '@mui/material/Box';
import Toolbar from '@mui/material/Toolbar';
import Typography from '@mui/material/Typography';
// import Divider from '@mui/material/Divider';
// import IconButton from '@mui/material/IconButton';
// import Badge from '@mui/material/Badge';
import Container from '@mui/material/Container';
import Grid from '@mui/material/Grid';
import Paper from '@mui/material/Paper';
import Link from '@mui/material/Link';
// import MenuIcon from '@mui/icons-material/Menu';
// import ChevronLeftIcon from '@mui/icons-material/ChevronLeft';
// import NotificationsIcon from '@mui/icons-material/Notifications';
// import { mainListItems, secondaryListItems } from '../components/listItems';
// import Deposits from './Deposits';
// import logo from '../img/logo/logomarscoinwallet.png';
import { Button } from '@mui/material';
import { IoIosWallet } from 'react-icons/io'
import { RiSafe2Line } from 'react-icons/ri'


// Import sections
import CreateModal from '../components/create-modal'
import MainDrawer from './main-drawer'
import AppBar from './app-bar'
import Wallet from './wallet'

function Copyright(props) {
  return (
    <Typography variant="body2" color="text.secondary" align="center" {...props}>
      {'Copyright © '}
      <Link color="inherit" href="https://www.marscoin.org/">
        Marscoin
      </Link>{' '}
      {new Date().getFullYear()}
      {'.'}
    </Typography>
  );
}


const MyBox = styled(Paper)(({ theme }) => ({
  backgroundColor: theme.colors.primary.background,
  color: theme.colors.primary.text,
  padding: 10,
  display: 'flex',
  flexDirection: 'column',



}))

const PrimaryButton = styled(Button)(({ theme }) => ({
  backgroundColor: theme.colors.primary.background,
  color: theme.colors.primary.text,
  padding: 10,

  borderRadius: "10px",
  height: "100px",
  margin: "10px",
  fontWeight: 800,
  width: "300px",


  '&:hover':
  {
    backgroundColor: theme.colors.secondary.background,
    boxShadow: "1px 1px 20px #00000090",

  },
  '@media (max-width: 800px)': {
    width: '90%'
  }



}))

const Heading = styled('h1')(({ theme }) => ({
  color: "#333333",
  padding: theme.spacing(0),
  fontWeight: 900,
  fontSize: '35px'

}));

const SubHeading = styled('h3')(({ theme }) => ({
  color: "#333333",
  padding: theme.spacing(1),
  fontSize: '18px',
  fontWeight: 500
}));


const MarsCoinTheme = createTheme({
  colors: {
    primary: {
      background: 'rgb(40, 57, 71)',
      text: "#fff"
    },
    secondary: {
      background: '#9F2C2C',
      text: "#fff"
    }

  },
});






function DashboardContent() {
  const [loginSuccess, setLoginSuccess] = useState(false)
  const [modalVisible, setModalVisible] = useState(false)
  const [open, setOpen] = useState(false);

  // Title state
  const [heading, setHeading] = useState('Wallet')
  const [subHeading, setSubHeading] =useState('Create new local wallet OR login to an existing wallet')


  const toggleModal = () => {
    setModalVisible(!modalVisible)
  }

  useEffect(() => {

    if(loginSuccess)
    {
      setModalVisible(false)
      setHeading("")
      setSubHeading("")
    }


    


  }, [loginSuccess])


  return (
    <ThemeProvider theme={MarsCoinTheme}>
      <Box sx={{ display: 'flex' }}>

        <CssBaseline />

        <AppBar open={open} setOpen={setOpen} />


        <MainDrawer open={open} setOpen={setOpen} />



        <Box
          component="main"
          sx={{
            backgroundColor: "#c4c8cc",
            flexGrow: 1,
            height: '100vh',
            overflow: 'auto',
            flexWrap: 'wrap'
          }}
        >
          <Toolbar />
          <Container maxWidth="xl" sx={{ mt: 4, mb: 4, backgroundColor: '' }} >
            <Heading> {heading} </Heading>
            <SubHeading> {subHeading} </SubHeading>
            <Grid container spacing={2}>
              {/* Chart */}
              {!loginSuccess ? <Grid item xl={6} >

                <Paper
                  elevation={6}

                  sx={{
                    p: 3,
                    display: 'flex',
                    flexDirection: 'column',

                    alignItems: 'center',
                    justifyContent: 'center'
                  }}
                >

                  <div>
                  </div>


                  <div>
                    <PrimaryButton
                      onClick={toggleModal}
                      startIcon={<IoIosWallet size={40} color={"#fff"} />}>

                      <h3>
                        Create New Wallet
                      </h3>
                    </PrimaryButton>
                    <CreateModal modalVisible={modalVisible} setModalVisible={setModalVisible} animation={"slideUp"} loginSuccess={loginSuccess} setLoginSuccess={setLoginSuccess} />



                    <PrimaryButton startIcon={<RiSafe2Line size={40} color={"#fff"} />}>

                      <h3>
                        Login To Wallet
                      </h3>
                    </PrimaryButton>
                  </div>




                </Paper>


              </Grid> : <Wallet />}









            </Grid>
            <Copyright sx={{ pt: 4 }} />
          </Container>
        </Box>
      </Box>
    </ThemeProvider>
  );
}

export default function Dashboard() {
  return <DashboardContent />;
}