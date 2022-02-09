import { styled, createTheme, ThemeProvider } from '@mui/material/styles';
import { useEffect, useState } from 'react';
import IconButton from '@mui/material/IconButton';
import MenuIcon from '@mui/icons-material/Menu';
import MuiAppBar from '@mui/material/AppBar';
import Toolbar from '@mui/material/Toolbar';
import logo from '../../img/logo/logomarscoinwallet.png';
import Typography from '@mui/material/Typography';


const drawerWidth = 240;

const Bar = styled(MuiAppBar, {
    shouldForwardProp: (prop) => prop !== 'open',
})(({ theme, open }) => ({
    backgroundColor: theme.colors.primary.background,
    zIndex: theme.zIndex.drawer + 1,
    transition: theme.transitions.create(['width', 'margin'], {
        easing: theme.transitions.easing.sharp,
        duration: theme.transitions.duration.leavingScreen,
    }),
    ...(open && {
        marginLeft: drawerWidth,
        width: `calc(100% - ${drawerWidth}px)`,
        transition: theme.transitions.create(['width', 'margin'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.enteringScreen,
        }),
    }),
}));

const AppBar = ({open, setOpen}) => {


    const toggleDrawer = () => {
        setOpen(!open);
      };

    return (
    
    <Bar position="absolute" open={open}>
        <Toolbar
            sx={{
                pr: '24px', // keep right padding when drawer closed
            }}
        >
            <IconButton
                edge="start"
                color="inherit"
                aria-label="open drawer"
                onClick={toggleDrawer}
                sx={{
                    marginRight: '36px',
                    ...(open && { display: 'none' }),
                }}
            >
                <MenuIcon />
            </IconButton>
            <IconButton>
                <img src={logo} height={"50px"} />
            </IconButton>
            <Typography
                component="h1"
                variant="h6"
                color="inherit"
                noWrap
                sx={{ flexGrow: 1 }}
            >
                MarsCoin HD Wallet
            </Typography>
            {/* <IconButton color="inherit">
        <Badge badgeContent={4} color="secondary">
          <NotificationsIcon />
        </Badge>
      </IconButton> */}
        </Toolbar>
    </Bar>)

}

export default AppBar