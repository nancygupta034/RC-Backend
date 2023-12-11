import Cookies from 'js-cookie';

const TokenKey = 'isLogged';

export function isLogged() {
  return Cookies.get(TokenKey) === '1';
}

export function getToken() {
  return Cookies.get('token');
}

export function setLogged(isLogged, token) {
  Cookies.set('token', token);
  return Cookies.set(TokenKey, isLogged);
}

export function removeToken() {
  return Cookies.remove(TokenKey);
}
