

// Create a new store instance.
const largeSidebar = {

    namespaced: true,
    state: {
        sidebarToggleProperties: {
            isSideNavOpen: false,
           
        }
    },
    getters: {
        getSideBarToggleProperties: state => state.sidebarToggleProperties       

    },
    // we cant use async code ---commit
    mutations: {

        toggleSidebarProperties: state =>
        (state.sidebarToggleProperties.isSideNavOpen = !state
          .sidebarToggleProperties.isSideNavOpen),
    
        
    },
    
    
   
}

// const app = createApp({ /* your root component */ })


export default largeSidebar;
