import { getInfo } from "@/api/auth";
import store from '../store';

export default function auth(to, from, next) {
  const authUser = localStorage.getItem('vuex');
  const auth = JSON.parse(authUser);
  if (authUser && auth.auth.authenticated !== true) {
    window.location.replace("/");

    return true;
  }
  else if (authUser && auth.auth.authenticated == true) {
    getInfo()
      .then(() => {
        return next();
      })
      .catch(async () => {
        await store.dispatch('auth/logout');
        window.location.replace("/");

        return true;
      });
  }

  return next();
}