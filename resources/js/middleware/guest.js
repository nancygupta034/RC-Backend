
export default function guest(to, from, next) {
    const authUser = localStorage.getItem('vuex');
    const auth = JSON.parse(authUser);
    if (authUser && auth.auth.authenticated === true) {

        return next("/dashboard");
    } else {

        return next();
    }
}