import { styled, createTheme, ThemeProvider } from '@mui/material/styles';
import { useEffect, useState } from 'react';
import IconButton from '@mui/material/IconButton';
import ChevronLeftIcon from '@mui/icons-material/ChevronLeft';
import { mainListItems, secondaryListItems } from '../../components/listItems';
import Toolbar from '@mui/material/Toolbar';
import MuiDrawer from '@mui/material/Drawer';
import List from '@mui/material/List';

const drawerWidth = 240;


const Drawer = styled(MuiDrawer, { shouldForwardProp: (prop) => prop !== 'open' })(
      ({ theme, open }) => ({
        '& .MuiDrawer-paper': {
          position: 'relative',
          whiteSpace: 'nowrap',
          width: drawerWidth,
          transition: theme.transitions.create('width', {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.enteringScreen,
          }),
          boxSizing: 'border-box',
          ...(!open && {
            overflowX: 'hidden',
            transition: theme.transitions.create('width', {
              easing: theme.transitions.easing.sharp,
              duration: theme.transitions.duration.leavingScreen,
            }),
            width: theme.spacing(7),
            [theme.breakpoints.up('sm')]: {
              width: theme.spacing(9),
            },
          }),
        },
      }),
    );

const MainDrawer = ({open, setOpen}) => {



    const toggleDrawer = () => {
        setOpen(!open);
      };



    return (

        <Drawer variant="permanent" open={open}>
            <Toolbar
                sx={{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'flex-end',
                    px: [1],
                }}
            >
                <IconButton onClick={toggleDrawer}>
                    <ChevronLeftIcon />
                </IconButton>
            </Toolbar>
            <List>{mainListItems}</List>
        </Drawer>


    )


}

export default MainDrawer