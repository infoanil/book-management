import axios from 'axios';
class Auth {
    constructor () {
        this.token = window.localStorage.getItem('token');
        let userData = window.localStorage.getItem('user');
         this.user = userData && userData !== 'undefined' ? JSON.parse(userData) : null;
        if (this.token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;
        }
    }
    login (token, user) {
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));
        document.cookie = `token=${token}`
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        this.token = token;
        this.user = user;
    }
    check () {
        return !! this.token;
    }
    logout () {
        window.localStorage.removeItem('token');
        window.localStorage.removeItem('user');
        document.cookie = 'token' +'=;Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        this.user = null;
    }
}
export default new Auth();
