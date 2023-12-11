import { getInfo } from "@/api/auth";

export default async function admin(to, from, next) {
    const authUser = localStorage.getItem('vuex');
    const auth = JSON.parse(authUser);
    if(authUser && auth.auth.authenticated == true) {
        if (auth.auth &&
            auth.auth.user &&
            auth.auth.user.roleData &&
            auth.auth.user.roleData.name && auth.auth.user.roleData.name == 'Admin'
        ) {
            getInfo()
                .then(() => {
                    return next();
                })
                .catch(async (error) => {
                    if(error.code == "ERR_BAD_REQUEST") {
                        await store.dispatch('auth/logout');
                        window.location.replace("/");
                    }
                    else {
                        window.location.replace("/403");
                    }
    
                    return true;
                });
        }
        else {
            window.location.replace("/403");
    
            return true;
        }
    }
    else {
        window.location.replace("/");

        return true;
    }
    

    return next();
}